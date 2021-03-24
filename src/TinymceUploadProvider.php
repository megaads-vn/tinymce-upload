<?php

namespace Megaads\TinymceUpload;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Megaads\TinymceUpload\Middleware\UploadMiddleware;
class TinymceUploadProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';
        $router = App::make(Router::class);
        $router->aliasMiddleware('mce_upload', UploadMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
