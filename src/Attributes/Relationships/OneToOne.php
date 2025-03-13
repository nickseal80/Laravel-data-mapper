<?php

namespace Seal\LaravelDataMapper\Attributes\Relationships;

use Attribute;

#[Attribute]
class OneToOne
{
    public function __construct(
        public FKConstraints $foreignKeyConstraint = FKConstraints::CASCADE
    ) {}
}