<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $paths = [
            env('CACHE_PATH', '/tmp/framework/cache/data'),
            env('APP_LOG', '/tmp/laravel.log'),
        ];

        foreach ($paths as $path) {
            try {
                if (!File::exists($path)) {
                    File::makeDirectory(dirname($path), 0755, true);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
