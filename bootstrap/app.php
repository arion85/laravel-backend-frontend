<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

const DS = DIRECTORY_SEPARATOR;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\BootProviders', function (Application $app){
    $request = app('request');
    $config = app('config');

    $adm_prefURL = $config->get('app.app_admin_prefixurl');

    if (preg_match("/^{$adm_prefURL}.*/", $request->host())) {
        $side = 'backend';
    }else{
        $side = 'frontend';
    }

    $app->instance('path.side',$app->basePath($side));
    $app->instance('path.resources',$app->get('path.side').DS.'resources');

    $app->get(ApplicationBuilder::class)
        ->withRouting(
            web: $app->get('path.side').DS.'routes'.DS.'web.php',
            commands: $app->get('path.side').DS.'routes'.DS.'console.php',
            health: '/up',

        );
    $view = app('view');
    $view->addLocation(app()->get('path.side').DS.'views');
});

return $app;
