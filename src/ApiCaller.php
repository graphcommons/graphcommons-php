<?php
declare (strict_types=1);
namespace GraphCommons;

use \stdClass as object; // @note This will be forbidden with PHP/7.2.

abstract class ApiCaller
{
    public final function status(): ?object
    {
        return $this->client->get('/status')->getResponse()->body;
    }

    public final function getGraph(string $id): ?object
    {
        return $this->client->get('/graphs/' . $id)->getResponse()->body;
    }
}
