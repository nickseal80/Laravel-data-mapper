<?php

namespace Seal\LaravelDataMapper\Attributes\Relationships;

enum FKConstraints: string
{
    case CASCADE = 'CASCADE';
    case RESTRICT = 'RESTRICT';
    case SET_NULL = 'SET_NULL';
    case NO_ACTION = 'NO_ACTION';
}