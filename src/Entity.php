<?php

namespace Seal\LaravelDataMapper;

class Entity
{
    protected static string $table;

    public static function getTable(): string
    {
        return static::$table;
    }
}
