<?php

namespace Seal\LaravelDataMapper\Hydrator;

class Hydrator
{
    public function hydrate(array $data, string $class)
    {
        return new $class(...$data);
    }

    public function extract(object $entity): array
    {
        return get_object_vars($entity);
    }
}
