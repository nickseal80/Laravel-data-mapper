<?php

namespace Seal\LaravelDataMapper\DataMapping\Builder;

use Seal\LaravelDataMapper\DataMapping\Criteria\Criteria;

class LDMBuilder
{
    private string $select;
    private string $from;
    private string $condition;

    public function __construct(private Criteria $criteria) {}

    private function parseCriteria()
    {
        //
    }
}