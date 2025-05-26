<?php

namespace Seal\LaravelDataMapper\DataMapping;

use Illuminate\Support\Facades\Facade;
use Seal\LaravelDataMapper\DataMapping\Criteria\Criteria;
use Seal\LaravelDataMapper\DataMapping\Internal\DataMapper;

/**
 * @method findById(int $id)
 * @method getFields(array $field): static
 * @method withRelationship(string $relationEntityClass): static
 *
 * @mixin DataMapper
 */
class DataMapperFacade extends Facade
{
    public const DATA_MAPPING_FACADE_ACCESSOR = 'data.mapper';

    public static function forEntity(string $entityClass, callable $callback)
    {
        $criteria = new Criteria($entityClass);
        $callback($criteria);
        return app(self::getFacadeAccessor())->forEntity($entityClass, $criteria);
    }

    protected static function getFacadeAccessor(): string
    {
        return self::DATA_MAPPING_FACADE_ACCESSOR;
    }
}