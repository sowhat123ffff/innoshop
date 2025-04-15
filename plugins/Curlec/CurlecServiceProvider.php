<?php

namespace Plugin\Curlec;

use Illuminate\Support\ServiceProvider;

class CurlecServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes only if not cached
        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Routes/front.php';
        }
    }

    public function register()
    {
        // You can bind services here if needed
    }
}
