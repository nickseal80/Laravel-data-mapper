<?php

namespace Seal\LaravelDataMapper\DataMapping\Criteria;

class Criteria
{
    public function getFields(array $fields): void
    {
        print_r('Criteria::getFields()');
    }
}