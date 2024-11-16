<?php

namespace KunalKanani\Maskable;

use Illuminate\Support\ServiceProvider;

class MaskableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/maskable.php' => config_path('maskable.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/maskable.php', 'maskable'
        );
    }
}
