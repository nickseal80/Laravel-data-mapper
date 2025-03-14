<?php

namespace Seal\LaravelDataMapper\DataMapping\Internal;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;
use ReflectionException;
use Seal\LaravelDataMapper\Contracts\DataMapperInterface;
use Seal\LaravelDataMapper\Entity\Entity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

abstract class DataMapper implements DataMapperInterface
{
    protected DatabaseManager $db;
    protected Hydrator $hydrator;
    protected string $table;
    protected string $entityClass;

    private Builder $builder;

    public function __construct(DatabaseManager $db, Hydrator $hydrator)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
    }

    /**
     * @throws ReflectionException
     */
    public function setEntityClass(string $entityClass)
    {
        $this->entityClass = $entityClass;
        $this->initBuilder();
    }

    /**
     * @throws ReflectionException
     */
    private function initBuilder()
    {
        $refEntity = new ReflectionEntity($this->entityClass);
        $this->table = $refEntity->takeTableName();
        $this->builder = $this->db->table($this->table);
    }

    /*
    |--------------------------------------------------------------------------
    | Query methods
    |--------------------------------------------------------------------------
    */

    /**
     * @throws ReflectionException
     */
    public function forEntity(string $entityClass): self
    {
        $clone = clone $this;
        $clone->setEntityClass($entityClass);
        return $clone;
    }

    /*
    |--------------------------------------------------------------------------
    | Read methods
    |--------------------------------------------------------------------------
    */

    public function getFields(array|string $fields): static
    {
        $this->builder->select($fields);
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public function findById(int $id): ?Entity
    {
        $data = $this->builder
            ->where('id', $id)
            ->first();

        if ($data) {
            return $this->hydrator->hydrate((array)$data, $this->entityClass);
        }

        return null;
    }

    public function save(object $entity): bool
    {
        $data = get_object_vars($entity);

        return $this->db->table($this->table)->updateOrInsert(['id' => $data['id']], $data);
    }

    public function delete(object $entity): int
    {
        return $this->db->table($this->table)->where('id', $entity->id)->delete();
    }
}
