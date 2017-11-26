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

namespace GraphCommons\Thing;

use GraphCommons\Api;
use \stdClass as object; // @note This will be forbidden with PHP/7.2.

/**
 * @package GraphCommons
 * @object  GraphCommons\Thing\Graph
 * @author  Kerem Güneş <k-gun@mail.com>
 */
final class Graph extends Thing
{
    /**
     * Statuses.
     * @const int
     */
    public const STATUS_DRAFT     = 0,
                 STATUS_PUBLISHED = 1,
                 STATUS_PRIVATE   = 2;

    /**
     * Signals.
     * @const string
     */
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

    /**
     * Check.
     * @param  string $id
     * @return bool
     */
    public function check(string $id): bool
    {
        return $this->api->getClient()->head('/graphs/'. $id)
            ->getResponse()->code == 200;
    }

    /**
     * Get.
     * @param  string $id
     * @return ?object
     */
    public function get(string $id): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id)
            ->getResponse()->body;
    }

    /**
     * Create.
     * @param  array $body
     * @return ?object
     */
    public function create(array $body): ?object
    {
        return $this->api->getClient()->post('/graphs', ['body' => $body])
            ->getResponse()->body;
    }

    /**
     * Update.
     * @param  string $id
     * @param  array  $graph
     * @return ?object
     */
    public function update(string $id, array $graph): ?object
    {
        return $this->api->getClient()->put('/graphs/'. $id, ['body' => ['graph' => $graph]])
            ->getResponse()->body;
    }

    /**
     * Clear.
     * @param  string $id
     * @return ?object
     */
    public function clear(string $id): ?object
    {
        return $this->api->getClient()->put('/graphs/'. $id .'/clear')
            ->getResponse()->body;
    }

    /**
     * Create signal.
     * @param  string $id
     * @param  array  $signals
     * @return ?object
     */
    public function createSignal(string $id, array $signals): ?object
    {
        return $this->api->getClient()->put('/graphs/'. $id .'/add', ['body' => ['signals' => $signals]])
            ->getResponse()->body;
    }

    /**
     * Get types.
     * @param  string $id
     * @return ?object
     */
    public function getTypes(string $id): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/types')
            ->getResponse()->body;
    }

    /**
     * Get edges.
     * @param  string $id
     * @param  array  $uriParams
     * @return ?object
     */
    public function getEdges(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/edges', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    /**
     * Get paths.
     * @param  string $id
     * @param  array  $uriParams
     * @return ?object
     */
    public function getPaths(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/paths', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    /**
     * Get collab filter.
     * @param  string $id
     * @param  array  $uriParams
     * @return ?object
     */
    public function getCollabFilter(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/graphs/'. $id .'/collab_filter', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    /**
     * Search.
     * @param  string $query
     * @param  array  $uriParams
     * @return array
     */
    public function search(string $query, array $uriParams = []): array
    {
        return (array) $this->api->getClient()->get('/graphs/search', ['uriParams' => ['query' => $query] + $uriParams])
            ->getResponse()->body;
    }

    /**
     * Delete.
     * @param  string $id
     * @note   API side does not return body yet (reported).
     * @return ?object
     */
    public function delete(string $id): ?object
    {
        return $this->api->getClient()->delete('/graphs/'. $id)
            ->getResponse()->body;
    }
}
