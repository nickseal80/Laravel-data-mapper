<?php

namespace Seal\LaravelDataMapper\DataMapping\Criteria;

use ReflectionException;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionRelationship;

class Criteria
{
    protected string $entityClass;

    private array $conditions = [];
    private array $joins = [];
    private array $order = [];
    private ?int $limit = null;

    public function __construct(string $entityClass) {}

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->conditions[] = ['type' => 'where', 'boolean' => 'and', 'condition' => [$column, $operator, $value]];
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public function withRelationships(array $relations): self
    {
        foreach ($relations as $relation) {
            $this->addJoinFromEntity($relation);
        }
        return $this;
    }

    /**
     * @throws ReflectionException
     */
    private function addJoinFromEntity(string $relation): void
    {
        $reflectionEntity = new ReflectionEntity($this->entityClass);

        /* @var ReflectionRelationship $relationship */
        foreach ($reflectionEntity->getRelationships() as $relationship) {
            if ($relationship->getName() !== $relation) {
                continue;
            }

            $relationEntity = new ReflectionEntity($relationship->getEntityClassName());

            $this->joins[] = [
                'table' => $relationEntity->getTableName(),
                'leftColumn' => $reflectionEntity->getTableName() . '.' . $relationship->getColumnName(),
                'operator' => '=',
                'rightColumn' => $relationEntity->getTableName() . '.' . $relationship->getReferencedColumnName(),
                'type' => $relationship->getJoinType(),
            ];
        }
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->order[$column] = $direction;
        return $this;
    }

    public function limit(int $value): self
    {
        $this->limit = $value;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'joins' => $this->joins,
            'where' => $this->conditions,
            'order' => $this->order,
            'limit' => $this->limit,
        ];
    }
}