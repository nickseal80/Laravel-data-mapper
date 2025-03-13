<?php

namespace Seal\LaravelDataMapper\Attributes\joins;

use Attribute;

#[Attribute]
class JoinColumn
{
    public function __construct(
        public string $columnName,
        public string $referencedColumnName
    ) {}
}