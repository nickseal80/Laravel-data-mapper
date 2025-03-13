<?php

namespace Seal\LaravelDataMapper\Attributes\joins;

enum JoinType: string
{
    case JOIN = 'JOIN';
    case LEFT_JOIN = 'LEFT_JOIN';
    case RIGHT_JOIN = 'RIGHT_JOIN';
    case INNER_JOIN = 'INNER_JOIN';
}
