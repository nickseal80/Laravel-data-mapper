<?php

namespace Seal\LaravelDataMapper\Hydrator;

use ReflectionException;
use ReflectionNamedType;
use Seal\LaravelDataMapper\Entity\Entity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionRelationship;
use Seal\LaravelDataMapper\Utils\ReflectionUtil;

class Hydrator
{
    /**
     * @throws ReflectionException
     */
    public function hydrate(array $data, string $entityClass, ?array $relationEntityClasses = null)
    {
        $reflectionEntity = new ReflectionEntity($entityClass);
        $table = $reflectionEntity->getTableName();

        $reflection = $reflectionEntity->getReflectionClass();
        $object = $reflection->newInstanceWithoutConstructor(); // Создаем объект без конструктора


        if ($relationEntityClasses && count($relationEntityClasses) > 0) {
            foreach ($relationEntityClasses as $className) {

                /* @var ReflectionRelationship $relationship */
                foreach ($reflectionEntity->getRelationships() as $relationship) {
                    if ($className === $relationship->getEntityClassName()) {
                        $data[$relationship->getName()] = null;
                    }
                }
            }
        }

        foreach ($data as $key => $value) {
            $propertyName = preg_replace("/^{$table}_(.*)/", '$1', $key);
            $setterName = ReflectionUtil::getAccessor($propertyName, ReflectionUtil::SET_ACCESSOR);

            if (!$reflection->hasMethod($setterName)) {
                continue; // Пропускаем, если сеттера нет
            }

            $method = $reflection->getMethod($setterName);
            $parameter = $method->getParameters()[0] ?? null;

            if (!$parameter || !($parameter->getType() instanceof ReflectionNamedType)) {
                continue; // Если нет типа, просто пропускаем
            }

            $type = $parameter->getType()->getName();

            // Проверяем, является ли целевой класс наследником Entity
            if (is_a($type, Entity::class, true)) {
                $value = $this->hydrate($data, $type); // Рекурсивно создаем вложенную сущность
            }

            $method->invoke($object, $value); // Вызываем сеттер
        }

        return $object;
    }

    public function extract(object $entity): array
    {
        return get_object_vars($entity);
    }
}
