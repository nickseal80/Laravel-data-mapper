<?php

namespace Seal\LaravelDataMapper\Hydrator;

use ReflectionClass;
use ReflectionException;
use Seal\LaravelDataMapper\Entity\Entity;
use Seal\LaravelDataMapper\Utils\CodeStyle;
use Seal\LaravelDataMapper\Utils\ReflectionUtil;

class Hydrator
{
    /**
     * @throws ReflectionException
     */
    public function hydrate(array $data, string $entityClass, ?array $relationEntityClasses = null)
    {
        $reflection = new ReflectionClass($entityClass);
        $object = $reflection->newInstanceWithoutConstructor(); // Создаем объект без конструктора

        foreach ($data as $key => $value) {
            if ($this->hasSetter($key, $reflection)) {
                $method = $reflection->getMethod(ReflectionUtil::getAccessor($key, ReflectionUtil::SET_ACCESSOR));
                $typeName = $method->getParameters()[0]->getType()->getName();
                if (!is_a($typeName, 'Seal\LaravelDataMapper\Entity\Entity', true)) {
                    $method->invoke($object, $value); // Вызываем метод-сеттер
                } else {
                    $relationEntity = $this->hydrate($data, $typeName);
                    $method->invoke($object, $relationEntity);
                }
            }
        }

        return $object;
    }

    private function hasSetter(string $property, ReflectionClass $reflectionClass): bool
    {
        $setter = ReflectionUtil::getAccessor($property, ReflectionUtil::SET_ACCESSOR);
        return $reflectionClass->hasMethod($setter);
    }

    public function extract(object $entity): array
    {
        return get_object_vars($entity);
    }
}
