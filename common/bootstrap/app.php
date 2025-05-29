<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Console\Input\ArgvInput;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$app = Application::configure(basePath: dirname(__DIR__,1))
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

$app_cli_side = match ((new ArgvInput())->getParameterOption('--side') ?: null){
    null=>'common',
    'be'=>'backend',
    'fe'=>'frontend'
};

$app->instance('app.cli.side', $app_cli_side);

$app->useEnvironmentPath(dirname(__DIR__,2));

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\RegisterProviders', function (Application $app){

    $app = app();

    if(!$app->runningInConsole() || $app->runningUnitTests()){
        $side = $app->make('request')->host();
        $adm_prefURL = $app->make('config')->get('app.app_admin_prefixurl');
    }else{
        $side = $app->get('app.cli.side');
        $adm_prefURL='';
    }

    if (str_starts_with($side,"{$adm_prefURL}." ) || $side=='backend') {
        $app->instance('app.side', 'backend');
    }else if($side=='common'){
        $app->instance('app.side', 'common');
    }else{
        $app->instance('app.side', 'frontend');
    }

    $app->setBasePath(dirname(__DIR__,2));
    $app->instance('path.side',$app->basePath($app->get('app.side')));

    $app->useConfigPath($app->basePath('common'.DS.'config'));
    $app->useDatabasePath($app->basePath('common'.DS.'database'));

    $app->useAppPath($app->get('path.side').DS.'app');
    $app->useBootstrapPath($app->get('path.side').DS.'bootstrap');
    $app->useLangPath($app->get('path.side').DS.'lang');
    $app->useStoragePath($app->get('path.side').DS.'storage');

    $app->instance('path.resources',$app->get('path.side').DS.'resources');

    if ($app->get('app.side') != 'common') {
        $app->get(ApplicationBuilder::class)
            //->withProviders(require $app->bootstrapPath('providers.php'))
            ->withRouting(
                web: $app->get('path.side') . DS . 'routes' . DS . 'web.php',
                commands: $app->get('path.side') . DS . 'routes' . DS . 'console.php',
                health: '/up',

            );
    }
});

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\BootProviders', function (Application $app){
    $view = app('view');
    $view->addLocation(app()->get('path.side').DS.'views');
});

return $app;
