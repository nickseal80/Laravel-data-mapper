<?php

namespace Seal\LaravelDataMapper\Attributes\joins;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class JoinColumn
{
    public function __construct(
        public string $columnName,
        public string $referencedColumnName,
        public JoinType $joinType = JoinType::JOIN,
    ) {}
}