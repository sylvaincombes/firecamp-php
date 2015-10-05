<?php

namespace Firecamp\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class ControllerServiceProvider
 *
 * @package Firecamp\Provider
 */
class ControllerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     *
     * @return null
     */
    public function register(Application $app)
    {
        if (!isset($app['controller.controllers'])) {
            return;
        }

        foreach ($app['controller.controllers'] as $label => $class) {
            $app[$label] = $app->share(
                function ($app) use ($class) {
                    return new $class($app);
                }
            );
        }
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
