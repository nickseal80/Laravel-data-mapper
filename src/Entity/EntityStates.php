<?php

namespace Seal\LaravelDataMapper\Entity;

enum EntityStates: string
{
    case TRANSIENT = 'TRANSIENT';
    case MANAGED = 'MANAGED';
    case DETACHED = 'DETACHED';
    case REMOVED = 'REMOVED';
}
