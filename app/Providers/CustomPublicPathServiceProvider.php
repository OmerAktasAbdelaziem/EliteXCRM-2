<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomPublicPathServiceProvider extends ServiceProvider
{
    public function register()
    {
        $publicPath = $this->app['config']->get('filesystems.public_path');

        // Bind the custom public path to the public_path() function
        $this->app->bind('path.public', function () use ($publicPath) {
            return base_path($publicPath);
        });
    }

    public function boot()
    {
        //
    }
}
