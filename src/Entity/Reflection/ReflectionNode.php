<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use Illuminate\Support\Str;

abstract class ReflectionNode
{
    private readonly string $serial;

    public function __construct()
    {
        $this->serial = Str::uuid();

        $this->initialize();
    }

    abstract public function initialize();

    /**
     * @return string
     */
    public function getSerial(): string
    {
        return $this->serial;
    }
}