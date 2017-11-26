<?php
declare (strict_types=1);
namespace GraphCommons\Thing;

use GraphCommons\Api;
use \stdClass as object; // @note This will be forbidden with PHP/7.2.

abstract class Thing
{
    protected $api;

    public final function __construct(Api $api)
    {
        $this->api = $api;
    }

    public final function getApi(): Api
    {
        return $this->api;
    }
}
