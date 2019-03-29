<?php

namespace Common;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Mix;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\PackageManifest;

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

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        parent::registerBaseBindings();

        $PackageManifest = app()->make(PackageManifest::class);
        $PackageManifest->basePath = dirname($this->basePath());
        $PackageManifest->vendorPath = dirname($this->basePath()).DIRECTORY_SEPARATOR.'vendor';
    }
}