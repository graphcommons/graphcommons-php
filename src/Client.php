<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Graph Commons & contributors.
 *     <http://graphcommons.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
declare (strict_types=1);

namespace GraphCommons;

/**
 * @package GraphCommons
 * @object  GraphCommons\Client
 * @author  Kerem Güneş <k-gun@mail.com>
 */
final class Client
{
    /**
     * Api.
     * @var GraphCommons\Api
     */
    private $api;

    /**
     * Request.
     * @var object
     */
    private $request;

    /**
     * Response.
     * @var object
     */
    private $response;

    /**
     * Constructor.
     * @param GraphCommons\Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Call magic.
     * @param  string $method
     * @param  array  $arguments
     * @return self
     * @throws GraphCommons\ClientException, BadMethodCallException
     */
    public function __call(string $method, array $arguments = []): self
    {
        // add shortcut methods
        $methods = ['head', 'get', 'post', 'put', 'delete'];
        if (in_array($method, $methods)) {
            array_unshift($arguments, strtoupper($method));
            // proxify all methods to send()
            return call_user_func_array([$this, 'send'], $arguments);
        }

        throw new \BadMethodCallException('Accepted methods: '. join(',', $methods) .'.');
    }

    /**
     * Get api.
     * @return GraphCommons\Api
     */
    public function getApi(): Api
    {
        return $this->api;
    }

    /**
     * Get request.
     * @return ?object
     */
    public function getRequest(): ?object
    {
        return $this->request;
    }

    /**
     * Get response.
     * @return ?object
     */
    public function getResponse(): ?object
    {
        return $this->response;
    }

    /**
     * Create message object.
     * @return object
     * @refence HTTP Message <https://tools.ietf.org/html/rfc2616#section-4>
     */
    private function createMessageObject(): object
    {
        $object = new \stdClass();
        $object->headers = [];
        $object->body = null;
        return $object;
    }

    /**
     * Send.
     * @param  string $method
     * @param  string $uri
     * @param  array  $arguments
     * @return self
     * @throws GraphCommons\ClientException
     */
    private function send(string $method, string $uri, array $arguments = []): self
    {
        $apiConfig = $this->api->getConfig();
        if (empty($apiConfig['url'])) {
            throw new ClientException('API URL is required (config.url)');
        }
        if (empty($apiConfig['key'])) {
            throw new ClientException('API key is required (config.key)');
        }

        $this->request = $this->createMessageObject();
        $this->response = $this->createMessageObject();

        $url = sprintf('%s/%s', $apiConfig['url'], trim($uri, '/ '));
        if (!empty($arguments['uriParams'])) {
            $url .= '?'. http_build_query($arguments['uriParams']);
        }

        $headers = $arguments['headers'] ?? [];
        $body    = $arguments['body'] ?? null;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($body && ($method == 'PUT' || $method == 'POST')) {
            $body = (string) json_encode($body);
            $headers['Content-Type'] = 'application/json';
            $headers['Content-Length'] = strlen($body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $headers['Accept'] = 'application/json';
        // @note: No GZip support yet (on the API side).
        $headers['Accept-Encoding'] = 'gzip';
        $headers['Authentication'] = $apiConfig['key'];
        $headers['User-Agent'] = 'GraphCommons-PHP';
        foreach ($headers as $key => $value) {
            $headers[] = sprintf('%s: %s', $key, trim((string) $value));
            unset($headers[$key]); // drop string key
        }

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $apiConfig['clientOptions']['redir']);
        curl_setopt($ch, CURLOPT_MAXREDIRS, $apiConfig['clientOptions']['redirMax']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $apiConfig['clientOptions']['timeout']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $apiConfig['clientOptions']['timeoutConnect']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);


        $result = curl_exec($ch);
        if ($result === false) {
            throw new ClientException(curl_error($ch), curl_errno($ch));
        }
        $resultInfo = curl_getinfo($ch);

        curl_close($ch);

        if ($apiConfig['debug']) {
            printf("Call: %s %s\n\n", $method, $url);
            printf("%s\n\n%s\n\n%s\n\n", $resultInfo['request_header'], $body, $result);
        }

        $this->request->method = $method;
        $this->request->uri = $uri;
        $this->request->headers = $this->parseHeaders($resultInfo['request_header']);
        $this->request->body = $body;

        $result = explode("\r\n\r\n", $result);
        // drop redirect etc. headers
        while (count($result) > 2) {
            array_shift($result);
        }

        @ [$headers, $body] = array_filter($result);
        if ($body) {
            // @note: No GZip support yet (on the API side).
            if (($this->response->headers['content-encoding'] ?? '') == 'gzip') {
                $body = gzdecode($body);
            }
            $body = $this->parseBody($body);
        }

        $this->response->code =@ (int) $resultInfo['http_code'];
        $this->response->headers = $this->parseHeaders($headers);
        $this->response->body = $body;

        // @see: https://graphcommons.github.io/api-v1/#errors
        if ($this->response->code == 0 || $this->response->code >= 400) {
            $errorCode = $this->response->code;
            if (isset($this->response->body->msg)) {
                $errorMessage = $this->response->body->msg;
            } elseif (isset($this->response->headers[0])) {
                $errorMessage = preg_replace('~HTTP/(?:[^\s]+) (.+)~s', '\1', $this->response->headers[0]);
            } else {
                $errorMessage = 'Unknown error';
            }

            throw new ClientException('HTTP error ('. $errorMessage .')', $errorCode);
        }

        return $this;
    }

    /**
     * Parse headers.
     * @param  string $headers
     * @return array
     */
    private function parseHeaders(?string $headers): array
    {
        $return = [];

        $headers = (array) explode("\r\n", trim((string) $headers));
        if ($headers) {
            // pick first line
            $return[0] = array_shift($headers);

            foreach ($headers as $header) {
                @ [$name, $value] = explode(':', $header, 2);
                if ($name === null) {
                    continue;
                }
                $name = strtolower($name);
                $value = trim((string) $value);

                // handle multi-headers as array
                if (isset($return[$name])) {
                    $return[$name] = array_merge((array) $return[$name], [$value]);
                } else {
                    $return[$name] = $value;
                }
            }

            ksort($return);
        }

        return $return;
    }

    /**
     * Parse body.
     * @param  string $body
     * @return any
     */
    private function parseBody(?string $body)
    {
        $return = json_decode($body, false, 512, JSON_BIGINT_AS_STRING);
        if (json_last_error()) {
            throw new ClientException('JSON error ('. json_last_error_msg() .')');
        }

        return $return;
    }
}
