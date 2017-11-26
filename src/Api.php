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

use \stdClass as object; // @note This will be forbidden with PHP/7.2.

/**
 * @package GraphCommons
 * @object  GraphCommons\Api
 * @author  Kerem Güneş <k-gun@mail.com>
 */
final class Api
{
    /**
     * Client.
     * @var GraphCommons\Client
     */
    private $client;

    /**
     * Config.
     * @var array
     */
    private $config = [
        'url' => 'https://graphcommons.com/api/v1',
        'key' => null,
        'debug' => false,
        'clientOptions' => [
            'redir' => true, 'redirMax' => 3,
            'timeout' => 5,  'timeoutConnect' => 3,
        ]
    ];

    /**
     * Constructor.
     * @param string $key
     * @param bool   $debug
     * @param array  $clientOptions
     */
    public function __construct(string $key, bool $debug = false, array $clientOptions = [])
    {
        $this->config['key'] = trim($key);
        $this->config['debug'] = $debug;
        $this->config['clientOptions'] = array_merge($this->config['clientOptions'], $clientOptions);

        $this->client = new Client($this);
    }

    /**
     * Get client.
     * @return GraphCommons\Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Get config.
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Status.
     * @return ?object
     */
    public function status(): ?object
    {
        return $this->client->get('/status')->getResponse()->body;
    }

    /**
     * Search.
     * @param  string $query
     * @param  array  $uriParams
     * @return ?array
     */
    public function search(string $query, array $uriParams = []): ?array
    {
        return $this->client->get('/search', ['uriParams' => ['query' => $query] + $uriParams])
            ->getResponse()->body;
    }
}
