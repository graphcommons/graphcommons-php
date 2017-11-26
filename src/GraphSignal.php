<?php
declare (strict_types=1);
namespace GraphCommons;

final class GraphSignal
{
    public const CREATE_NODE      = 'node_create',
                 CREATE_EDGE      = 'edge_create',
                 DELETE_NODE      = 'node_delete',
                 DELETE_EDGE      = 'edge_delete',
                 UPDATE_NODE      = 'node_update',
                 UPDATE_EDGE      = 'edge_update',
                 UPDATE_NODE_TYPE = 'nodetype_update',
                 UPDATE_EDGE_TYPE = 'edgetype_update',
                 DELETE_NODE_TYPE = 'nodetype_delete',
                 DELETE_EDGE_TYPE = 'edgetype_delete';
}
