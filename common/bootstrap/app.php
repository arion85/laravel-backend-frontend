<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\ApplicationBuilder;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Console\Input\ArgvInput;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\View\Factory as ViewFactory;
use Symfony\Component\Finder\Finder;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$app = Application::configure(basePath: dirname(__DIR__,2))->create();

// Fixed paths
$app->useEnvironmentPath($app->basePath());
$app->usePublicPath($app->basePath('public'));

// Common Fixed Paths
$app->useStoragePath($app->basePath('common'.DS.'storage'));
$app->useConfigPath($app->basePath('common'.DS.'config'));
$app->useDatabasePath($app->basePath('common'.DS.'database'));

// Mutable Paths
$app->useAppPath($app->basePath('common'.DS.'app'));
$app->useBootstrapPath($app->basePath('common'.DS.'bootstrap'));
$app->useLangPath($app->basePath('common'.DS.'lang'));

$app->instance('path.resources',$app->basePath('common'.DS.'resources'));

$app->make(LoadConfiguration::class)->bootstrap($app);

if($app->runningInConsole() || $app->runningUnitTests()){
    $app_cli_side = match ((new ArgvInput())->getParameterOption('--side') ?: null){
        null=>'common',
        'be'=>'backend',
        'fe'=>'frontend'
    };
    $app->instance('app.side', $app_cli_side);
}else{
    $side = Request::capture()->host();

    $adm_prefURL = app('config')->get('app.app_admin_prefixurl');
    if (str_starts_with($side,"{$adm_prefURL}." )) {
        $app->instance('app.side', 'backend');
    }else{
        $app->instance('app.side', 'frontend');
    }
}

if($app->get('app.side') != 'common'){
    $app->useAppPath($app->basePath($app->get('app.side').DS.'app'));
    $app->useBootstrapPath($app->basePath($app->get('app.side').DS.'bootstrap'));
    $app->useLangPath($app->basePath($app->get('app.side').DS.'lang'));

    $app->get(ApplicationBuilder::class)
        ->withRouting(
            web: $app->basePath($app->get('app.side') . DS . 'routes' . DS . 'web.php'),
            commands: $app->basePath($app->get('app.side') . DS . 'routes' . DS . 'console.php'),
            health: '/up',
        );
}

$app->get(ApplicationBuilder::class)
    ->withProviders(require $app->basePath('common'.DS.'bootstrap'.DS.'providers.php'))
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withMiddleware(function (Middleware $middleware) {
        //
    });

$app->afterResolving(ViewFactory::class, function ($view){
    $app=app();
    $view->addLocation($app->basePath($app->get('app.side').DS.'views'));
});

$app->afterBootstrapping('Illuminate\Foundation\Bootstrap\LoadConfiguration', function (Application $app){
//Loading Config Files for needed side of the site
    $conf_files = [];
    $side_conf_path = $app->basePath($app->get('app.side').DS.'config');
    foreach (Finder::create()->files()->name('*.php')->in($side_conf_path) as $file) {
        //$directory = $this->getNestedDirectory($file, $side_conf_path);

        $conf_files[/*$directory.*/basename($file->getRealPath(), '.php')] = $file->getRealPath();
    }
    ksort($conf_files, SORT_NATURAL);
    foreach ($conf_files as $name => $path) {
        $config = (fn () => require $path)();
        $config = is_array($config)?$config:[];
        $old_repo_config = config($name)?:[];
        $new_repo_config = array_replace_recursive($old_repo_config, $config);
        config([$name => $new_repo_config]);
    }
});

return $app;
