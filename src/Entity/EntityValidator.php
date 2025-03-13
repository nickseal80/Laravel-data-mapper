<?php

namespace Seal\LaravelDataMapper\Entity;

use PHPUnit\Util\Reflection;
use ReflectionClass;
use ReflectionException;
use Seal\LaravelDataMapper\Exceptions\EntityException;

class EntityValidator implements Validator
{
    private ReflectionClass $reflectionClass;
    private string $tableName;

    /**
     * @throws EntityException|ReflectionException
     */
    public function validate(string $entityClass)
    {
        $this->reflectionClass = new ReflectionClass($entityClass);
        $properties = $this->reflectionClass->getProperties();

        if (count($properties) <= 0) {
            throw new EntityException("Entity does not contain any fields");
        }

        $this->tableName = $this->getTableName();

//        foreach ($properties as $property) {
//
//        }
    }

    private function getTableName(): string
    {
        return $this->reflectionClass->getStaticPropertyValue('table');
    }
}