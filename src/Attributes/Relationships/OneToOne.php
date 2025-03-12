<?php

namespace Seal\LaravelDataMapper\Attributes\Relationships;

use Attribute;

#[Attribute]
class OneToOne
{
    public function __construct(
        public string $join,
        public string $leftJoin,
        public string $rightJoin,
        public string $innerJoin,

        public string $on,
    ) {}
}