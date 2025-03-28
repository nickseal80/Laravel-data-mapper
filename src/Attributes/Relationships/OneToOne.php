<?php

namespace Seal\LaravelDataMapper\Attributes\Relationships;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class OneToOne
{
    public function __construct(
        public FKConstraints $foreignKeyConstraint = FKConstraints::CASCADE
    ) {}
}