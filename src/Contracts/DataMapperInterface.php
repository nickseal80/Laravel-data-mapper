<?php

namespace Seal\LaravelDataMapper\Contracts;

interface DataMapperInterface
{
    public function find(int $id);
    public function save(object $entity);
    public function delete(object $entity);
}
