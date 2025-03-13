<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use ReflectionProperty;

class ReflectionColumnProperty
{
    public function __construct(ReflectionProperty $property)
    {
        dd('property');
    }
}