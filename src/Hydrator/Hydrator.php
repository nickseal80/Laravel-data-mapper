<?php

namespace Seal\LaravelDataMapper\Hydrator;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use Seal\LaravelDataMapper\Entity\Entity;
use Seal\LaravelDataMapper\Utils\ReflectionUtil;

class Hydrator
{
    /**
     * @throws ReflectionException
     */
    public function hydrate(array $data, string $entityClass)
    {
        // TODO: Добавить проверку на одноимённые поля главной и приджоиниваемой сущности(тей)

        $reflection = new ReflectionClass($entityClass);
        $object = $reflection->newInstanceWithoutConstructor(); // Создаем объект без конструктора

        foreach ($data as $key => $value) {
            $setterName = ReflectionUtil::getAccessor($key, ReflectionUtil::SET_ACCESSOR);

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
