<?php

namespace Seal\LaravelDataMapper\Entity;

interface Validator
{
    public function validate(string $entityClass);
}