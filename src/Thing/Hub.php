<?php
declare (strict_types=1);
namespace GraphCommons\Thing;

use GraphCommons\Api;
use \stdClass as object; // @note This will be forbidden with PHP/7.2.

final class Hub extends Thing
{
    public function get(string $id): ?object
    {
        return $this->api->getClient()->get('/hubs/'. $id)
            ->getResponse()->body;
    }
}
