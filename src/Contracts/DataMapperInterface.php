<?php

namespace Seal\LaravelDataMapper\Contracts;

use Seal\LaravelDataMapper\DataMapping\Criteria\Criteria;
use Seal\LaravelDataMapper\DataMapping\Internal\DataMapper;
use Seal\LaravelDataMapper\Entity\Entity;

interface DataMapperInterface
{
    public function forEntity(string $entityClass, Criteria $criteria): self;
    public function getFields(array $fields): static;
    public function withRelationships(array $relationships): static;
    public function findById(int $id): static;
    public function getOne(string $order = DataMapper::FIRST):Entity;
    public function getAll(): Entity;
    public function save(object $entity);
    public function delete(object $entity);
    public function getQuery();
    public function getCriteria(): Criteria;
}
