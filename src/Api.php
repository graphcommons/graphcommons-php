<?php
declare (strict_types=1);
namespace GraphCommons;

class Api extends ApiCaller
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

    public final function getClient(): Client
    {
        return $this->client;
    }

    public final function getConfig(): array
    {
        return $this->config;
    }
}
