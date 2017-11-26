<?php
declare (strict_types=1);
namespace GraphCommons;

use \stdClass as object; // @note This will be forbidden with PHP/7.2.

final class Api
{
    protected $client;
    protected $config = [
        'url' => 'https://graphcommons.com/api/v1',
        'key' => null,
        'debug' => false,
        'clientOptions' => [
            'redir' => true, 'redirMax' => 3,
            'timeout' => 5,  'timeoutConnect' => 3,
        ]
    ];

    public function __construct(string $key, bool $debug = false, array $clientOptions = [])
    {
        $this->config['key'] = trim($key);
        $this->config['debug'] = $debug;
        $this->config['clientOptions'] = array_merge($this->config['clientOptions'], $clientOptions);

        $this->client = new Client($this);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function status(): ?object
    {
        return $this->client->get('/status')->getResponse()->body;
    }
}
