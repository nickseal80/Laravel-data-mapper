<?php

namespace Seal\LaravelDataMapper\DataMapping\Internal;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;
use Seal\LaravelDataMapper\Attributes\Column\Column;
use Seal\LaravelDataMapper\Contracts\DataMapperInterface;
use Seal\LaravelDataMapper\DataMapping\Criteria\Criteria;
use Seal\LaravelDataMapper\Entity\Entity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionRelationship;
use Seal\LaravelDataMapper\Hydrator\Hydrator;
use Seal\LaravelDataMapper\Utils\CodeStyle;

abstract class DataMapper implements DataMapperInterface
{
    public const FIRST = 'first';
    public const LAST = 'last';

    protected DatabaseManager $db;
    protected Hydrator $hydrator;
    protected string $table;
    protected string $entityClass;
    protected ReflectionEntity $reflectionEntity;
    protected array $relationships;
    protected Criteria $criteria;

    private Builder $builder;

    public function __construct(DatabaseManager $db, Hydrator $hydrator)
    {
        $this->db = $db;
        $this->hydrator = $hydrator;
    }

    /**
     * @throws ReflectionException
     */
    public function forEntity(string $entityClass, Criteria $criteria): self
    {

        $clone = clone $this;
        $clone->criteria = $criteria;
        $clone->setEntityClass($entityClass);
        return $clone;
    }

    /**
     * @throws ReflectionException
     */
    public function setEntityClass(string $entityClass)
    {
        $this->entityClass = $entityClass;
        $this->reflectionEntity = new ReflectionEntity($entityClass);
        $this->initBuilder();
        $this->applyCriteria();
    }

    /**
     * @throws ReflectionException
     */
    private function initBuilder()
    {
        $refEntity = new ReflectionEntity($this->entityClass);
        $this->table = $refEntity->setTableName();
        $this->builder = $this->db->connection()->table($this->table);
    }

    public function applyCriteria()
    {
        // Добавляем JOIN'ы из аннотаций
        foreach ($this->criteria->toArray()['joins'] as $join) {
            $method = $join['type'];
            $this->builder->$method($join['table'], $join['leftColumn'], $join['operator'], $join['rightColumn']);
        }

        // WHERE условия
        foreach ($this->criteria->toArray()['where'] as $condition) {
            $this->builder->{$condition['boolean']}(...$condition['condition']);
        }

        // Сортировка
        foreach ($this->criteria->toArray()['order'] as $column => $direction) {
            $this->builder->orderBy($column, $direction);
        }

        // Лимит
        if ($this->criteria->toArray()['limit']) {
            $this->builder->limit($this->criteria->toArray()['limit']);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Query methods
    |--------------------------------------------------------------------------
    */



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

    /* @throws ReflectionException
     * @var array<string> $relationships
     */
    public function withRelationships(array $relationships): static
    {
        foreach ($relationships as $relationship) {
            $this->addRelationship($relationship);
            $this->relationships[] = $relationship;
        }
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public function addRelationship(string $relationshipClassName)
    {
        /* @var ReflectionRelationship $reflectionRelationship */
        $reflectionRelationship = $this->reflectionEntity->getRelationship($relationshipClassName);
        $joinType = CodeStyle::snakeToCamel(strtolower($reflectionRelationship->getJoinType()));

        $reflectionRelationEntity = new ReflectionEntity($reflectionRelationship->getEntityClassName());
        $relationTableName = $reflectionRelationEntity->getTableName();

        $this->addAliasedColumns($this->reflectionEntity->getTableName());
        $this->addAliasedColumns($relationTableName);

        $this->builder->$joinType(
            $relationTableName,
            $this->reflectionEntity->getTableName() . '.' .$reflectionRelationship->getColumnName(),
            '=',
            $relationTableName . '.' .$reflectionRelationship->getReferencedColumnName()
        );
    }

    private function addAliasedColumns(string $table)
    {
        $columns = $this->db->connection()->getSchemaBuilder()->getColumnListing($table);
        foreach ($columns as $column) {
            $this->builder->addSelect("$table.$column as {$table}_{$column}");
        }
    }

    public function findById(int $id): static
    {
        $this->builder->where($this->reflectionEntity->getTableName() . '.id', $id);
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
        dd($data);
        return $this->hydrate($data);
    }

    /**
     * @throws ReflectionException
     */
    public function getAll(): Entity
    {
        $data = $this->builder->get();
        // TODO: обработать коллекцию $data для гидрации
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
        return $this->hydrator->hydrate((array)$data, $this->entityClass, $this->relationships);
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

    /**
     * @return Criteria
     */
    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }


}
