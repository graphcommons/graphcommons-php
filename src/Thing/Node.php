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
 * @object  GraphCommons\Thing\Node
 * @author  Kerem Güneş <k-gun@mail.com>
 */
final class Node extends Thing
{
    /**
     * Get.
     * @param  string $id
     * @return ?object
     */
    public function get(string $id): ?object
    {
        return $this->api->getClient()->get('/nodes/'. $id)
            ->getResponse()->body;
    }

    /**
     * Search.
     * @param  string $query
     * @param  array  $uriParams
     * @return ?object
     */
    public function search(string $query, array $uriParams = []): array
    {
        return (array) $this->api->getClient()->get('/nodes/search', ['uriParams' => ['query' => $query] + $uriParams])
            ->getResponse()->body;
    }
}
