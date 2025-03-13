<?php

namespace Seal\LaravelDataMapper;

use Illuminate\Support\ServiceProvider;
use Seal\LaravelDataMapper\Entity\Entity;
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

        $this->mergeConfigFrom(__DIR__ . '/../config/columnProperties.php', 'columnProperties');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/columnProperties.php' => config_path('columnProperties.php'),
        ], 'config');
    }
}
