<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('asset', function ($file) {
            $paths = glob(str_replace([ "'", '"' ], '', public_path($file)), GLOB_NOSORT);
            usort($paths, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });

            return str_replace(public_path(), '', $paths[0] ?? 'about:blank');
        });
    }
}
