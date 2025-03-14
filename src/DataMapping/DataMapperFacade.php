<?php

namespace Seal\LaravelDataMapper\DataMapping;

use Illuminate\Support\Facades\Facade;
/**
 * @method static find(int $id, string $entityClass)
 */
class DataMapperFacade extends Facade
{
    public const DATA_MAPPING_FACADE_ACCESSOR = 'data.mapper';

    public static function forEntity(string $entityClass)
    {
        return app(self::getFacadeAccessor())->forEntity($entityClass);
    }

    protected static function getFacadeAccessor(): string
    {
        return self::DATA_MAPPING_FACADE_ACCESSOR; // Этот ключ должен совпадать с тем, что в контейнере
    }
}