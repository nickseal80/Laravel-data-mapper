<?php

namespace Seal\LaravelDataMapper\Contracts;

use Seal\LaravelDataMapper\DataMapping\Internal\DataMapper;
use Seal\LaravelDataMapper\Entity\Entity;

interface DataMapperInterface
{
    public function forEntity(string $entityClass): self;
    public function getFields(array $fields): static;
    public function getRelationships(array $relationships): static;
    public function findById(int $id): static;
    public function getOne(string $order = DataMapper::FIRST):Entity;
    public function getMany(): Entity;
    public function save(object $entity);
    public function delete(object $entity);
}
