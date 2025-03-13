<?php

namespace Seal\LaravelDataMapper\Entity;

class Entity
{
    protected static string $table;

    private bool $useRelationships = true;

    public static function getTable(): string
    {
        return static::$table;
    }

    /**
     * @return bool
     */
    public function isUseRelationships(): bool
    {
        return $this->useRelationships;
    }

    /**
     * @param bool $useRelationships
     */
    public function setUseRelationships(bool $useRelationships): void
    {
        $this->useRelationships = $useRelationships;
    }


}
