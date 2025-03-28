<?php

namespace Seal\LaravelDataMapper\Attributes\Column;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
    public function __construct(
        public Type  $type,
        public int $length = -1,

        public bool  $unsigned = false,
        public bool  $autoIncrement = false,
        public bool  $nullable = false,
        public bool  $unique = false,
        public bool  $primaryKey = false,

        public string $name = '',
        public mixed $default = null,
    ) {}
}


