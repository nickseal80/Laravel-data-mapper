<?php

namespace Seal\LaravelDataMapper\Attributes\Relationships;

use Attribute;

#[Attribute]
class OneToMany
{
    public function __construct(
        public string $arrayOf,
        public FKConstraints $foreignKeyConstraint = FKConstraints::CASCADE,
    ) {}
}