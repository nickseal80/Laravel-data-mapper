<?php

namespace Seal\LaravelDataMapper\Entity\Reflection;

use ReflectionProperty;
use Seal\LaravelDataMapper\Attributes\Column\Column;
use Seal\LaravelDataMapper\Attributes\Column\Type;
use Seal\LaravelDataMapper\Utils\ReflectionUtil;

class ReflectionColumn extends ReflectionNode
{
    private ReflectionProperty $property;
    private string $name;
    private string $columnName;
    private string $columnType;
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
        foreach ($properties->getArguments() as $property => $value) {
            if ($value instanceof Type) {
                $this->columnType = $value->value;
            }
            if ((bool)$value === true) {
                print_r($property);
            }
        }

        dd(config('columnProperties.catalog'));
    }

}