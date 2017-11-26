<?php
declare (strict_types=1);
namespace GraphCommons;

use \stdClass as object; // @note This will be forbidden with PHP/7.2.

final class Graph
{
    private $api;
    private $data = [];

    public function __construct(Api $api, array $data = [])
    {
        $this->api = $api;
        $this->data = $data;
    }

    public function check(string $id): bool
    {
        return $this->api->getClient()->head('/graphs/' . $id)->getResponse()->code == 200;
    }

    public function get(string $id): ?object
    {
        return $this->api->getClient()->get('/graphs/' . $id)->getResponse()->body;
    }

    public function create(array $data): ?object
    {
        return $this->api->getClient()->post('/graphs', ['body' => $data])->getResponse()->body;
    }
}
