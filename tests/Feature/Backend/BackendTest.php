<?php

namespace Feature\Backend;

use Tests\TestCase;

class BackendTest extends TestCase
{
    public static string $side;
    private static array $old_server;

    private string $laravel_base_path;
    public static function setUpBeforeClass(): void
    {
        error_reporting(E_ALL);
        self::$old_server = $_SERVER;
        parent::$side = '--side=be';

        parent::setUpBeforeClass();
    }
    protected function assertPreConditions():void
    {
        $this->laravel_base_path = $this->app->basePath();
    }
    public function test_backend(): void
    {
        $response = $this->get('http://adm.lbf.loc');

        $response->assertStatus(200);
    }
    public function test_make_provider()
    {
        $PROVIDER_NAME= 'TestServiceProvider';
        $service_providers_file = $this->app->bootstrapPath('providers.php');
        $provider_file = $this->app->path('Providers'.DIRECTORY_SEPARATOR.$PROVIDER_NAME.'.php');

        $artisan_comm = "php {$this->laravel_base_path}/artisan make:provider {$PROVIDER_NAME} --side=be";

        copy($service_providers_file, $service_providers_file.'_copy');

        exec($artisan_comm);

        $arr_provides = require $service_providers_file;

        $this->assertContains('Backend\App\Providers\TestServiceProvider', $arr_provides);
        $this->assertFileExists($provider_file);

        rename($service_providers_file.'_copy',$service_providers_file);
        unlink($provider_file);
    }
    public static function tearDownAfterClass():void
    {
        $_SERVER = self::$old_server;
    }
}
