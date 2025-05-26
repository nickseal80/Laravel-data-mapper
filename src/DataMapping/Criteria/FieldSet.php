<?php

namespace Seal\LaravelDataMapper\DataMapping\Criteria;

class FieldSet
{
    private array $fields = [];

    public function add($fieldName, $alias = null): void
    {
        $this->fields[] = [];
    }

    public function getFields(): array
    {
        return $this->fields;
    }


}