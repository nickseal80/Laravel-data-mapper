<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Seal\LaravelDataMapper\Attributes\Column\Column;
use Seal\LaravelDataMapper\Attributes\Relationships\ManyToMany;
use Seal\LaravelDataMapper\Attributes\Relationships\OneToMany;
use Seal\LaravelDataMapper\Attributes\Relationships\OneToOne;
use Seal\LaravelDataMapper\Exceptions\EntityException;

class ReflectionEntity extends ReflectionNode
{
    private ReflectionClass $reflectionClass;
    private string $tableName;
    private array $columns = [];
    private array $relationships = [];

    /**
     * @throws ReflectionException
     */
    public function __construct(string $entityClass)
    {
        $this->reflectionClass = new ReflectionClass($entityClass);
        parent::__construct();
    }

    /**
     * @throws EntityException
     */
    public function initialize(): void
    {
        $this->tableName = $this->takeTableName();

        $columns = $this->takeColumns();
        if (count($columns) <= 0) {
            throw new EntityException("Entity does not contain any columns");
        }

        foreach ($columns as $column) {
            $this->columns[] = new ReflectionColumn($column);
        }

        $relationships = $this->takeRelationships();
        if (count($relationships) > 0) {
            foreach ($relationships as $relationship) {
                $this->relationships[] = new ReflectionRelationship($relationship);
            }
        }
    }

    public function takeTableName(): string
    {
        return $this->reflectionClass->getStaticPropertyValue('table');
    }

    /**
     * @return array<ReflectionProperty>
     */
    private function takeColumns(): array
    {
        $columns = [];
        $properties = $this->reflectionClass->getProperties();

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(Column::class);
            if (!empty($attributes)) {
                $columns[] = $property;
            }
        }

        return $columns;
    }

    private function takeRelationships(): array
    {
        $relations = [];
        $properties = $this->reflectionClass->getProperties();

        foreach ($properties as $property) {
            $attributes = (
                $property->getAttributes(OneToOne::class) ||
                $property->getAttributes(OneToMany::class) ||
                $property->getAttributes(ManyToMany::class)
            );
            if (!empty($attributes)) {
                $relations[] = $property;
            }
        }

        return $relations;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }
}