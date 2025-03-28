<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use Illuminate\Support\Str;

abstract class ReflectionNode
{
    public const SERIAL = 'L202503290009';

    protected string $uuid;

    public function __construct()
    {
        $this->uuid = Str::uuid();
        $this->initialize();
    }

    abstract public function initialize();

    public function getUuid(): string
    {
        return $this->uuid;
    }
}