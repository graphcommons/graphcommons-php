<?php
declare (strict_types=1);
namespace GraphCommons;

use \stdClass as object; // @note This will be forbidden with PHP/7.2.

final class Client
{
    private $api;
    private $request;
    private $response;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function __call(string $method, array $arguments = []): self
    {
        $methods = ['head', 'get', 'post', 'put', 'delete'];
        if (in_array($method, $methods)) {
            array_unshift($arguments, strtoupper($method));
            return call_user_func_array([$this, 'send'], $arguments);
        }

        throw new \BadMethodCallException('Accepted methods: ' . join(',', $methods) .'.');
    }

    public function getApi(): Api
    {
        return $this->api;
    }

    public function getRequest(): ?object
    {
        return $this->request;
    }

    public function getResponse(): ?object
    {
        return $this->response;
    }

    // HTTP Message <https://tools.ietf.org/html/rfc2616#section-4>
    private function createMessageObject(): object
    {
        $object = new \stdClass();
        $object->headers = [];
        $object->body = null;
        return $object;
    }

    private function send(string $method, string $uri, array $args = []): self
    {
        $apiConfig = $this->api->getConfig();
        // prs($apiConfig,1);
        if (empty($apiConfig['url'])) {
            throw new ClientException('API URL is required (config.url)');
        }
        if (empty($apiConfig['key'])) {
            throw new ClientException('API key is required (config.key)');
        }

        $this->request = $this->createMessageObject();
        $this->response = $this->createMessageObject();

        $url = sprintf('%s/%s', $apiConfig['url'], trim($uri, '/ '));
        if (!empty($args['uriParams'])) {
            $url .= '?' . http_build_query($args['uriParams']);
        }

        $headers = $args['headers'] ?? [];
        $body    = $args['body'] ?? null;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($body && ($method == 'PUT' || $method == 'POST')) {
            $body = (string) json_encode($body);
            $headers['Content-Type'] = 'application/json';
            $headers['Content-Length'] = strlen($body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $headers['Accept'] = 'application/json';
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

        @ [$headers, $body] = $result;
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

            throw new ClientException('HTTP error (' . $errorMessage . ')', $errorCode);
        }

        return $this;
    }

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

    private function parseBody(?string $body): ?object
    {
        $return = json_decode($body, false, 512, JSON_BIGINT_AS_STRING);
        if (json_last_error()) {
            throw new ClientException('JSON error (' . json_last_error_msg() . ')');
        }

        return $return;
    }
}
