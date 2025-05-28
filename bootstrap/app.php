<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Console\Input\ArgvInput;

const DS = DIRECTORY_SEPARATOR;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\RegisterProviders', function (Application $app){
    if(!app()->runningInConsole()){
        $request = app('request');
        $side = $request->host();
    }else{
        $input = (new ArgvInput);
        $side = $input->getParameterOption('--side');
    }

    $config = app('config');

    $adm_prefURL = $config->get('app.app_admin_prefixurl');

    if (preg_match("/^{$adm_prefURL}.*/", $side)) {
        $app->instance('app.side', 'backend');
    }else{
        $app->instance('app.side', 'frontend');
    }

    $app->instance('path.side',$app->basePath($app->get('app.side')));
    $app->useAppPath($app->get('path.side').DS.'app');
    $app->useBootstrapPath($app->get('path.side').DS.'bootstrap');
    $app->instance('path.resources',$app->get('path.side').DS.'resources');

    $app->get(ApplicationBuilder::class)
        //->withProviders(require $app->bootstrapPath('providers.php'))
        ->withRouting(
            web: $app->get('path.side').DS.'routes'.DS.'web.php',
            commands: $app->get('path.side').DS.'routes'.DS.'console.php',
            health: '/up',

        );
});

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\BootProviders', function (Application $app){
    $view = app('view');
    $view->addLocation(app()->get('path.side').DS.'views');
});

return $app;
