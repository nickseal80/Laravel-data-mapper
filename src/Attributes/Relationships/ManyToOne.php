<?php

namespace Seal\LaravelDataMapper\Attributes\Relationships;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToOne
{
    public FKConstraints $foreignKeyConstraint = FKConstraints::NO_ACTION;
}