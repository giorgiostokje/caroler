<?php

declare(strict_types=1);

namespace GiorgioStokje\Caroler\Laravel;

use GiorgioStokje\Caroler\Caroler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Service Provider for Laravel
 *
 * @package GiorgioStokje\Caroler\Laravel
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'caroler');

        App::bind('caroler', function () {
            return new Caroler(config('caroler.token'), config('caroler'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('caroler.php'),
            dirname(__FILE__, 3) . '/examples/Commands/Dice.php' => app_path('Caroler/Commands/Dice.php'),
            dirname(__FILE__, 3) . '/examples/Laravel/Console/Commands/CarolerSing.php'
                => app_path('Console/Commands/CarolerSing.php'),
        ]);
    }
}
