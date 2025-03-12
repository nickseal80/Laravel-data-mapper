<?php

namespace Seal\LaravelDataMapper;

use Illuminate\Support\ServiceProvider;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

class DataMapperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DataMapper::class, function ($app, Entity $entity) {
            $tableName = $entity::getTable();

            return new DataMapper(
                $app['db'],
                new Hydrator(),
                $tableName,
                $entity::class
            );
        });
    }

    public function boot()
    {
        // Публикация конфигов, миграций и т.д.
    }
}
