<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Console\Input\ArgvInput;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$app = Application::configure(basePath: dirname(__DIR__))->create();

$app->make(LoadConfiguration::class)->bootstrap($app);

$app->instance('app.side', 'common');

if($app->runningInConsole() || $app->runningUnitTests()){
    $app_cli_side = match ((new ArgvInput())->getParameterOption('--side') ?: null){
        null=>'common',
        'be'=>'backend',
        'fe'=>'frontend'
    };
    $adm_prefURL='';
}else{
    $side = Request::capture()->host();
    $adm_prefURL = app('config')->get('app.app_admin_prefixurl');
}

if (str_starts_with($side,"{$adm_prefURL}." ) || $side=='backend') {
    $app->instance('app.side', 'backend');
}else if($side=='common'){
    $app->instance('app.side', 'common');
}else{
    $app->instance('app.side', 'frontend');
}

$app_side = $app->get('app.side');

$app->useEnvironmentPath(dirname(__DIR__,2));

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\RegisterProviders', function (Application $app){

    $app = app();

//    if($app->runningInConsole() || $app->runningUnitTests()){
//        $side = $app->get('app.cli.side');
//        $adm_prefURL='';
//    }else{
//        $side = $app->make('request')->host();
//        $adm_prefURL = $app->make('config')->get('app.app_admin_prefixurl');
//    }

//    if (str_starts_with($side,"{$adm_prefURL}." ) || $side=='backend') {
//        $app->instance('app.side', 'backend');
//    }else if($side=='common'){
//        $app->instance('app.side', 'common');
//    }else{
//        $app->instance('app.side', 'frontend');
//    }
//
//    $app_side = $app->get('app.side');

    $app->setBasePath(dirname(__DIR__,2));
    $app->instance('path.side',$app->basePath($app_side));

    $app->useConfigPath($app->basePath('common'.DS.'config'));
    $app->useDatabasePath($app->basePath('common'.DS.'database'));

    if($app_side == 'frontend'){
        $app->usePublicPath($app->basePath('public'));
    }else{
        $app->usePublicPath($app->basePath($app_side.DS.'public'));
    }

    $app_path_side = $app->get('path.side');

    $app->useAppPath($app_path_side.DS.'app');
    $app->useBootstrapPath($app_path_side.DS.'bootstrap');
    $app->useLangPath($app_path_side.DS.'lang');
    $app->useStoragePath($app_path_side.DS.'storage');

    $app->instance('path.resources',$app_path_side.DS.'resources');

    if ($app_side != 'common') {
        $app->get(ApplicationBuilder::class)
            //->withProviders(require $app->bootstrapPath('providers.php'))
            ->withMiddleware(function (Middleware $middleware) {
                //
            })
            ->withExceptions(function (Exceptions $exceptions) {
                //
            })
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
