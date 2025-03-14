<?php

namespace Seal\LaravelDataMapper\Entity;

class Entity
{
    protected static string $table;

    protected string $state = EntityStates::TRANSIENT->value;
    public static function getTable(): string
    {
        return static::$table;
    }
}
