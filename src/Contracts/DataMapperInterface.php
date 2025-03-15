<?php

namespace Seal\LaravelDataMapper\Contracts;

interface DataMapperInterface
{
    public function forEntity(string $entityClass): self;
    public function getFields(array $fields): static;
    public function findById(int $id);
    public function save(object $entity);
    public function delete(object $entity);
}
