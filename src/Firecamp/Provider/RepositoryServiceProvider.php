<?php

namespace Firecamp\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class RepositoryServiceProvider
 *
 * @package Firecamp\Provider
 */
class RepositoryServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     *
     * @return null
     */
    public function register(Application $app)
    {
        if (!isset($app['repository.repositories'])) {
            return;
        }

        foreach ($app['repository.repositories'] as $label => $class) {
            $app[$label] = $app->share(
                function ($app) use ($class) {
                    return new $class($app['db']);
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
