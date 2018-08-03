<?php

namespace Common;

use Illuminate\Foundation\Application;
use RuntimeException;

class CommonAplication extends Application
{
    public function __construct(?string $basePath = null)
    {
        parent::__construct($basePath);
        $this->useDatabasePath(realpath(__DIR__ . '/database'));
    }

    public function getNamespace()
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents(realpath(base_path().'/../composer.json')), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath(app_path()) == realpath(base_path().'/../'.$pathChoice)) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }
}