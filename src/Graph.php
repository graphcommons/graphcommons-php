<?php
declare (strict_types=1);
namespace GraphCommons;

use \stdClass as object; // @note This will be forbidden with PHP/7.2.

final class Graph
{
    public const STATUS_DRAFT     = 0,
                 STATUS_PUBLISHED = 1,
                 STATUS_PRIVATE   = 2;

    public const SIGNAL_CREATE_NODE      = 'node_create',
                 SIGNAL_CREATE_EDGE      = 'edge_create',
                 SIGNAL_DELETE_NODE      = 'node_delete',
                 SIGNAL_DELETE_EDGE      = 'edge_delete',
                 SIGNAL_UPDATE_NODE      = 'node_update',
                 SIGNAL_UPDATE_EDGE      = 'edge_update',
                 SIGNAL_UPDATE_NODE_TYPE = 'nodetype_update',
                 SIGNAL_UPDATE_EDGE_TYPE = 'edgetype_update',
                 SIGNAL_DELETE_NODE_TYPE = 'nodetype_delete',
                 SIGNAL_DELETE_EDGE_TYPE = 'edgetype_delete';

    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function check(string $id): bool
    {
        return $this->api->getClient()->head('/graphs/'. $id)
            ->getResponse()->code == 200;
    }

    public function get(string $id): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id)
            ->getResponse()->body;
    }

    public function create(array $body): ?object
    {
        return $this->api->getClient()->post('/graphs', ['body' => $body])
            ->getResponse()->body;
    }

    public function update(string $id, array $graph): ?object
    {
        return $this->api->getClient()->put('/graphs/'. $id, ['body' => ['graph' => $graph]])
            ->getResponse()->body;
    }

    public function clear(string $id): ?object
    {
        return $this->api->getClient()->put('/graphs/'. $id .'/clear')
            ->getResponse()->body;
    }

    public function createSignal(string $id, array $signals): ?object
    {
        return $this->api->getClient()->put('/graphs/'. $id .'/add', ['body' => ['signals' => $signals]])
            ->getResponse()->body;
    }

    public function getTypes(string $id): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/types')
            ->getResponse()->body;
    }

    public function getEdges(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/edges', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    public function getPaths(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/paths', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    public function getCollabFilter(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/collab_filter', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    public function search(string $query, array $uriParams): ?array
    {
        return $this->api->getClient()->get('/graphs/search', ['uriParams' => ['query' => $query] + $uriParams])
            ->getResponse()->body;
    }

    // @note API side does not return body yet (reported).
    public function delete(string $id): ?object
    {
        return $this->api->getClient()->delete('/graphs/'. $id)
            ->getResponse()->body;
    }
}
