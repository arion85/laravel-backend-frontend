<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;

abstract class TestCase extends BaseTestCase
{
    protected string $side;
    public function createApplication()
    {
        array_push($_SERVER['argv'], $this->side);
        ++$_SERVER['argc'];

        $app = require Application::inferBasePath().'/common/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}

