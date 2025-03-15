<?php

namespace Seal\LaravelDataMapper\DataMapping\Internal;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;
use Seal\LaravelDataMapper\Contracts\DataMapperInterface;
use Seal\LaravelDataMapper\Entity\Entity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

abstract class DataMapper implements DataMapperInterface
{
    public const FIRST = 'first';
    public const LAST = 'last';

    protected DatabaseManager $db;
    protected Hydrator $hydrator;
    protected string $table;
    protected string $entityClass;
    protected ReflectionEntity $reflectionEntity;

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
        $this->reflectionEntity = new ReflectionEntity($entityClass);
        dd($this->reflectionEntity);
        $this->initBuilder();
    }

    /**
     * @throws ReflectionException
     */
    private function initBuilder()
    {
        $refEntity = new ReflectionEntity($this->entityClass);
        $this->table = $refEntity->takeTableName();
        $this->builder = $this->db->connection()->table($this->table);
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

    /*
     * public function getFields(Criteria $criteria): static
     * {
     *      $this->builder->select($criteria->toQuery);
     * }
     *
     * //STUB
     */
    public function getFields(array $fields): static
    {
        $this->builder->select($fields);
        return $this;
    }

    public function getRelationships(array $relationships): static
    {
        //...
        return $this;
    }

    public function findById(int $id): static
    {
        $this->builder->where('id', $id);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Data methods
    |--------------------------------------------------------------------------
    | methods of obtaining data
    */

    /**
     * @throws ReflectionException
     */
    public function getOne(string $order = self::FIRST):Entity
    {
        $data = $this->builder->$order();
        return $this->hydrate($data);
    }

    /**
     * @throws ReflectionException
     */
    public function getMany(): Entity
    {
        $data = $this->builder->get();
        return $this->hydrate($data);
    }

    #[NoReturn]
    public function getQuery()
    {
        dd($this->builder->toSql(), $this->builder->getBindings());
    }

    /**
     * @throws ReflectionException
     */
    private function hydrate($data)
    {
        return $this->hydrator->hydrate((array)$data, $this->entityClass);
    }

    public function save(object $entity): bool
    {
        $data = get_object_vars($entity);

        return $this->builder->updateOrInsert(['id' => $data['id']], $data);
    }

    public function delete(object $entity): int
    {
        return $this->builder->where('id', $entity->id)->delete();
    }


}
