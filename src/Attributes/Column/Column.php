<?php

namespace Seal\LaravelDataMapper\Attributes\Column;

use Attribute;

#[Attribute]
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

        public mixed $default = null,
    ) {}
}


