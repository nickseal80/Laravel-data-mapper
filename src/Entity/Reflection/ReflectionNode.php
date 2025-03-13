<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use Illuminate\Support\Str;

abstract class ReflectionNode
{
    private readonly string $guid;

    public function __construct()
    {
        $this->guid = Str::uuid();

        $this->initialize();
    }

    abstract public function initialize();

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }
}