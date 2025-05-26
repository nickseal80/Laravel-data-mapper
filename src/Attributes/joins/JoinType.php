<?php

namespace Seal\LaravelDataMapper\Attributes\joins;

enum JoinType: string
{
    case JOIN = 'join';
    case LEFT_JOIN = 'leftJoin';
    case RIGHT_JOIN = 'rightJoin';
    case INNER_JOIN = 'innerJoin';
}
