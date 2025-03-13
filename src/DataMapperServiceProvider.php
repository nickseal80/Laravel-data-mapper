<?php

namespace Seal\LaravelDataMapper;

use Illuminate\Support\ServiceProvider;
use Seal\LaravelDataMapper\DataMapping\DataMapper;
use Seal\LaravelDataMapper\DataMapping\DataMapperFacade;
use Seal\LaravelDataMapper\Entity\Entity;
use Seal\LaravelDataMapper\Hydrator\Hydrator;

class DataMapperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DataMapperFacade::DATA_MAPPING_FACADE_ACCESSOR, function ($app, Entity $entity) {
            return new DataMapper(
                $app['db'],
                new Hydrator(),
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
