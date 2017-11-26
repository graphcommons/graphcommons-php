<?php
declare (strict_types=1);
namespace GraphCommons\Thing;

use GraphCommons\Api;
use \stdClass as object; // @note This will be forbidden with PHP/7.2.

final class Node extends Thing
{
    public function get(string $id): ?object
    {
        return $this->api->getClient()->get('/nodes/'. $id)
            ->getResponse()->body;
    }

    public function search(string $query, array $uriParams): ?array
    {
        return $this->api->getClient()->get('/nodes/search', ['uriParams' => ['query' => $query] + $uriParams])
            ->getResponse()->body;
    }
}
