<?php
declare (strict_types=1);
namespace GraphCommons;

final class GraphSignal
{
    public const CREATE_NODE      = 'create_node',
                 CREATE_EDGE      = 'create_edge',
                 DELETE_NODE      = 'delete_node',
                 DELETE_EDGE      = 'delete_edge',
                 UPDATE_NODE      = 'update_node',
                 UPDATE_EDGE      = 'update_edge',
                 UPDATE_NODE_TYPE = 'update_node_type',
                 UPDATE_EDGE_TYPE = 'update_edge_type',
                 DELETE_NODE_TYPE = 'delete_node_type',
                 DELETE_EDGE_TYPE = 'delete_edge_type';
}
