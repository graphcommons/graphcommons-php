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
