<?php

namespace Seal\LaravelDataMapper\DataMapping;

use Illuminate\Support\Facades\Facade;

class DataMapperFacade extends Facade
{
    public const DATA_MAPPING_FACADE_ACCESSOR = 'data.mapper';

    protected static function getFacadeAccessor(): string
    {
        return self::DATA_MAPPING_FACADE_ACCESSOR; // Этот ключ должен совпадать с тем, что в контейнере
    }
}