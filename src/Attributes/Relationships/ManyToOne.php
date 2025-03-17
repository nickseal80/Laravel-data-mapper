<?php

namespace Seal\LaravelDataMapper\Attributes\Relationships;

use Attribute;

#[Attribute]
class ManyToOne
{
    public FKConstraints $foreignKeyConstraint = FKConstraints::NO_ACTION;
}