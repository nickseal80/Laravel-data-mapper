<?php

namespace Seal\LaravelDataMapper\Utils;

use Reflection;
use ReflectionAttribute;
use ReflectionObject;
use ReflectionProperty;

class ReflectionUtil
{
    public static function getPropAttribute(ReflectionProperty $reflection, string $attributeClass): ?ReflectionAttribute
    {
        foreach ($reflection->getAttributes() as $attribute) {
            if ($attribute->getName() === $attributeClass) {
                return $attribute;
            }
        }

        return null;
    }
}