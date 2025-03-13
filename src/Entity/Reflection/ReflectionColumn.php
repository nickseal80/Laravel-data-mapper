<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use ReflectionAttribute;
use ReflectionProperty;
use Seal\LaravelDataMapper\Attributes\Column\Column;
use Seal\LaravelDataMapper\Attributes\Column\Type;
use Seal\LaravelDataMapper\Utils\CodeStyle;
use Seal\LaravelDataMapper\Utils\ReflectionUtil;

class ReflectionColumn extends ReflectionNode
{
    private ReflectionProperty $property;
    private readonly string $name;
    private readonly string $columnName;
    private readonly string $columnType;
    private array $properties = [];

    public function __construct(ReflectionProperty $property)
    {
        $this->property = $property;
        parent::__construct();
    }

    public function initialize(): void
    {
        $this->name = $this->property->getName();
        $this->getProperties();
    }

    private function getProperties()
    {
        $properties = ReflectionUtil::getPropAttribute($this->property, Column::class);
        $this->setColumnName($properties);
        foreach ($properties->getArguments() as $property => $value) {
            if ($value instanceof Type) {
                $this->columnType = $value->value;
            }

            if ($value === true) {
                $this->properties[] = $property;
            }
        }
    }

    private function setColumnName(ReflectionAttribute $properties)
    {
        $columnName = null;

        foreach ($properties->getArguments() as $property => $value) {
            if ($property === 'name') {
                $columnName = $value;
            }
        }

        if (!$columnName) {
            $columnName = CodeStyle::camelToSnake($this->name);
        }

        $this->columnName = $columnName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function getColumnType(): string
    {
        return $this->columnType;
    }
}