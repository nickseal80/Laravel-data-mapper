<?php

namespace Seal\LaravelDataMapper\Hydrator;

use ReflectionClass;
use ReflectionException;
use Seal\LaravelDataMapper\Utils\CodeStyle;

class Hydrator
{
    /**
     * @throws ReflectionException
     */
    public function hydrate(array $data, string $class)
    {
        $reflection = new ReflectionClass($class);
        $object = $reflection->newInstanceWithoutConstructor(); // Создаем объект без конструктора

        foreach ($data as $key => $value) {
            // Проверяем, есть ли сеттер для этого свойства
            $setter = 'set' . ucfirst(CodeStyle::snakeToCamel($key)); // Формируем имя метода-сеттера

            if ($reflection->hasMethod($setter)) {
                $method = $reflection->getMethod($setter);
                $method->invoke($object, $value); // Вызываем метод-сеттер
            }
        }

        return $object;
    }

    public function extract(object $entity): array
    {
        return get_object_vars($entity);
    }
}
