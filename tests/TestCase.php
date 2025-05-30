<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;

abstract class TestCase extends BaseTestCase
{
    public static string $side;
    public function createApplication()
    {
        if (isset(self::$side) && !is_null(self::$side)) {
            $_SERVER['argv'][] = self::$side;
            ++$_SERVER['argc'];
        }

        $app = require Application::inferBasePath().'/common/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}

