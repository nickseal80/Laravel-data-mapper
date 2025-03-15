<?php

namespace Seal\LaravelDataMapper\DataMapping\Criteria;

use App\Entities\User;

class Criteria
{
    public function getFields(array $fields): void
    {
        print_r('Criteria::getFields()');
        $user = new User();
    }
}