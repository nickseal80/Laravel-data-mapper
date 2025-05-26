<?php

namespace Seal\LaravelDataMapper\DataMapping\Criteria;

use Illuminate\Support\Facades\Log;
use ReflectionException;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionEntity;
use Seal\LaravelDataMapper\Entity\Reflection\ReflectionRelationship;
use Seal\LaravelDataMapper\Exceptions\EntityException;

class Criteria
{
    protected string $entityClass;

    private array $props = [];

    public function __construct(string $entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @param array $properties
     * ['fieldName1' => 'fieldAlias1', 'fieldName2' ...] or ['fieldName1', 'fieldName2', ... ]
     *
     * @return Criteria
     */
    public function getProperties(array $properties): Criteria {
        $fieldSet = new FieldSet();
        foreach ($properties as $property => $alias) {
            $fieldSet->add($property, $alias);
        }

        return $this;
    }
}