<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

class Application extends Illuminate\Foundation\Application
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var string
     */
    private $runtimeDir;

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath()
    {
        return $this->getCacheDir() . '/config.php';
    }

    /**
     * Get the path to the routes cache file.
     *
     * @return string
     */
    public function getCachedRoutesPath()
    {
        return $this->getCacheDir() . '/routes.php';
    }

    /**
     * Get the path to the cached "compiled.php" file.
     *
     * @return string
     */
    public function getCachedCompilePath()
    {
        return $this->getCacheDir() . '/compiled.php';
    }

    /**
     * Get the path to the cached services.json file.
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        return $this->getCacheDir() . '/services.json';
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        if (null === $this->cacheDir) {
            $this->cacheDir = $this->getRuntimeDir() . '/cache';
            if (!is_dir($this->cacheDir)) {
                mkdir($this->cacheDir, 0777, true);
            }
        }

        return $this->cacheDir;
    }

    /**
     * @return string
     */
    public function getRuntimeDir()
    {
        if (null === $this->runtimeDir) {
            $runtimeDirConfig = __DIR__ . '/runtime_dir_config.php';
            if (is_file($runtimeDirConfig)) {
                $this->runtimeDir = require $runtimeDirConfig;
                if (!is_dir($this->runtimeDir)) {
                    mkdir($this->runtimeDir, 0777, true);
                }
            } else {
                $this->runtimeDir = $this->basePath() . '/bootstrap';
            }
        }

        return $this->runtimeDir;
    }
}

$app = new Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
