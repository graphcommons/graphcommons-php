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
 * @object  GraphCommons\Thing\Hub
 * @author  Kerem Güneş <k-gun@mail.com>
 */
final class Hub extends Thing
{
    /**
     * Get.
     * @param  string $id
     * @return ?object
     * @throws GraphCommons\ClientException
     */
    public function get(string $id): ?object
    {
        return $this->api->getClient()->get('/hubs/'. $id)
            ->getResponse()->body;
    }

    /**
     * Get types.
     * @param  string $id
     * @return ?object
     * @throws GraphCommons\ClientException
     */
    public function getTypes(string $id): ?object
    {
        return $this->api->getClient()->get('/hubs/'. $id .'/types')
            ->getResponse()->body;
    }

    /**
     * Get paths.
     * @param  string $id
     * @param  array  $uriParams
     * @return ?object
     * @throws GraphCommons\ClientException
     */
    public function getPaths(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/hubs/'. $id .'/paths', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    /**
     * Get collab filter.
     * @param  string $id
     * @param  array  $uriParams
     * @return ?object
     * @throws GraphCommons\ClientException
     */
    public function getCollabFilter(string $id, array $uriParams): ?object
    {
        return $this->api->getClient()->get('/hubs/'. $id .'/collab_filter', ['uriParams' => $uriParams])
            ->getResponse()->body;
    }

    /**
     * Search graphs.
     * @param  string $id
     * @param  string $query
     * @param  array  $uriParams
     * @return array
     * @throws GraphCommons\ClientException
     */
    public function searchGraphs(string $id, string $query, array $uriParams = []): array
    {
        return (new Graph($this->api))->search($query, ['hub' => $id] + $uriParams);
    }

    /**
     * Search nodes.
     * @param  string $id
     * @param  string $query
     * @param  array  $uriParams
     * @return array
     * @throws GraphCommons\ClientException
     */
    public function searchNodes(string $id, string $query, array $uriParams = []): array
    {
        return (new Node($this->api))->search($query, ['hub' => $id] + $uriParams);
    }
}
