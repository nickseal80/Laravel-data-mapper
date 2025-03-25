<?php

namespace Seal\LaravelDataMapper\Utils;

use Reflection;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class ReflectionUtil
{
    public const GET_ACCESSOR = 'get';
    public const SET_ACCESSOR = 'set';

    public static function getPropAttribute(ReflectionProperty $reflection, string $attributeClass): ?ReflectionAttribute
    {
        foreach ($reflection->getAttributes() as $attribute) {
            if ($attribute->getName() === $attributeClass) {
                return $attribute;
            }
        }

        return null;
    }

    public static function getAccessor(string $property, string $accessor): string
    {
        return $accessor . ucfirst(CodeStyle::snakeToCamel($property));
    }
}