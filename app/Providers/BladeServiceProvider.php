<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('asset', function($file) {
            $file = str_replace([ "'", '"' ], '', $file);
            $path = public_path() . $file;
            $opt = file_exists($path) ? "?v=<?php echo \File::lastModified('{$path}') ?>" : '';

            return $file . $opt;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
