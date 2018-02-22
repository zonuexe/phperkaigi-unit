<?php

namespace 通貨;

abstract class Currency
{
    /** @var Currency */
    private $instances = [];

    private function __construct() {}

    /**
     * @return static
     */
    public static function getInstance()
    {
        return $instances[static::class] = $instances[static::class] ?? new static;;
    }
}
