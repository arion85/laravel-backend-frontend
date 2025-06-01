<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Console\Input\ArgvInput;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\PackageManifest;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$app = Application::configure(basePath: dirname(__DIR__,2))->create();

$app->useEnvironmentPath(dirname(__DIR__,2));
$app->useAppPath($app->basePath('common'.DS.'app'));
$app->useBootstrapPath($app->basePath('common'.DS.'bootstrap'));
$app->useLangPath($app->basePath('common'.DS.'lang'));
$app->useStoragePath($app->basePath('common'.DS.'storage'));
$app->useConfigPath($app->basePath('common'.DS.'config'));
$app->useDatabasePath($app->basePath('common'.DS.'database'));
$app->usePublicPath($app->basePath('common'.DS.'public'));
$app->instance('path.resources',$app->basePath('common'.DS.'resources'));

$app->afterResolving(PackageManifest::class, function ($pack_manifest){
    $s=$pack_manifest;
});

if($app->runningInConsole() || $app->runningUnitTests()){
    $app_cli_side = match ((new ArgvInput())->getParameterOption('--side') ?: null){
        null=>'common',
        'be'=>'backend',
        'fe'=>'frontend'
    };
    $app->instance('app.side', $app_cli_side);
}else{
    $side = Request::capture()->host();
    $app->make(LoadConfiguration::class)->bootstrap($app);

    $adm_prefURL = app('config')->get('app.app_admin_prefixurl');
    if (str_starts_with($side,"{$adm_prefURL}." )) {
        $app->instance('app.side', 'backend');
    }else{
        $app->instance('app.side', 'frontend');
    }


}

//$app->get(ApplicationBuilder::class)
//    ->withProviders(require $app->basePath($app->get('app.side').DS.'bootstrap'.DS.'providers.php'))
//    ->withExceptions(function (Exceptions $exceptions) {
//        //
//    })
//    ->withMiddleware(function (Middleware $middleware) {
//        //
//    });

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\RegisterProviders', function (Application $app){
    $app_side = $app->get('app.side');

    $app->useStoragePath($app->basePath($app_side.DS.'storage'));
    $app->useAppPath($app->basePath($app_side.DS.'app'));
    $app->useBootstrapPath($app->basePath($app_side.DS.'bootstrap'));

    $app->get(ApplicationBuilder::class)
        ->withRouting(
            web: $app->basePath($app_side . DS . 'routes' . DS . 'web.php'),
            commands: $app->basePath($app_side . DS . 'routes' . DS . 'console.php'),
            health: '/up',
        );
});

$app->beforeBootstrapping('Illuminate\Foundation\Bootstrap\BootProviders', function (Application $app){
    $app = app();
    $view = app('view');
    $view->addLocation($app->basePath($app->get('app.side').DS.'views'));
});

return $app;
