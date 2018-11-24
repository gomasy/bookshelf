<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('asset', function ($file) {
            $paths = glob(str_replace([ "'", '"' ], '', public_path($file)), GLOB_NOSORT) ?? 'about:blank';
            usort($paths, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });

            return str_replace(public_path(), '', $paths[0] ?? 'about:blank');
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
