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
        $frameworkVersion = $this->getFrameworkVersion();
        if ($frameworkVersion == 5.2) {
            $router->pushMiddlewareToGroup('mce_upload', UploadMiddleware::class);
        } else if ($frameworkVersion > 5.2) {
            $router->aliasMiddleware('mce_upload', UploadMiddleware::class);
        }
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

    /**
     * Get current laravel framework version
     * 
     * @return retval
     */
    private function getFrameworkVersion() {
        $filePath = __DIR__ . '/../../../../composer.json';
        $composer = NULL;
        $retval = 0;
        if (file_exists($filePath)) {
            $composer = file_get_contents($filePath);
            if (!empty($composer)) {
                $composer = json_decode($composer);
                foreach ($composer->require as $key => $val) {
                    if ($key == 'laravel/framework') {
                        $retval = $val;
                        break;
                    }
                }
            }
        }
        return (double) $retval;
    }
}
