<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Seal\LaravelDataMapper\Attributes\Column\Column;
use Seal\LaravelDataMapper\Exceptions\EntityException;

class ReflectionEntity extends ReflectionNode
{
    private ReflectionClass $reflectionClass;
    private string $tableName;
    private array $columns = [];

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
}