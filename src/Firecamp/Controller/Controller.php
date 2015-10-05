<?php

namespace Firecamp\Controller;

use Doctrine\DBAL\Driver\Connection;
use Firecamp\Repository\AbstractRepository;
use Monolog\Logger;
use Silex\Application;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Serializer\Serializer;

/**
 * Default Controller Class to extend
 *
 * @package Firecamp\Controller
 */
class Controller
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get a service from dependency injection
     *
     * @param string $service The service string identifier to look for
     *
     * @return mixed
     */
    public function get($service)
    {
        return $this->app[$service];
    }

    /**
     * Return the silex application
     *
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Return the doctrine service
     *
     * @return Connection
     */
    public function getDoctrine()
    {
        return $this->app['db'];
    }

    /**
     * Return the debug state of the silex application
     *
     * @return boolean
     */
    public function getDebug()
    {
        return $this->app['debug'];
    }

    /**
     * Return the silex application
     *
     * @return FormFactory
     */
    public function getFormFactory()
    {
        return $this->app['form.factory'];
    }

    /**
     * Return the silex application
     *
     * @return Logger
     */
    public function getLogger()
    {
        return $this->app['logger'];
    }

    /**
     * Return the current request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->app['request'];
    }

    /**
     * Return a repository
     *
     * @param string $repository
     *
     * @return AbstractRepository
     */
    public function getRepository($repository)
    {
        return $this->app[$repository];
    }

    /**
     * Return the security service
     *
     * @see http://silex.sensiolabs.org/doc/providers/security.html
     *
     * @return SecurityContext
     */
    public function getSecurity()
    {
        return $this->app['security'];
    }

    /**
     * Return the serializer service
     *
     * @see http://silex.sensiolabs.org/doc/providers/serializer.html
     *
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->app['serializer'];
    }

    /**
     * Return the current session
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->app['session'];
    }

    /**
     * Return the url generator
     *
     * @return \Symfony\Component\Routing\Generator\UrlGenerator﻿
     */
    public function getUrlGenerator()
    {
        return $this->app['url_generator'];
    }

    /**
     * Return the current session
     *
     * @return \Symfony\Component\Validator\Validator﻿﻿
     */
    public function getValidator()
    {
        return $this->app['validator'];
    }

    /**
     * Shortcut to app->redirect
     * Redirects the user to another URL.
     *
     * @param string  $url    The URL to redirect to
     * @param integer $status The status code (302 by default)
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return $this->app->redirect($url, $status);
    }

    /**
     * Shortcut to twig->render
     * Renders a template.
     *
     * @param string $name    The template name
     * @param array  $context An array of parameters to pass to the template
     *
     * @return string The rendered template
     */
    public function render($name, array $context = array())
    {
        return $this->getTwig()->render($name, $context);
    }

    /**
     * Return the twig service
     *
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->app['twig'];
    }

    /**
     * Convert the class to a string
     *
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }

    /**
     * Get client ip of the request
     * This one is here because request->getClientIp() doesn't work as expected :(
     *
     * @return string
     */
    protected function getClientIp()
    {
        $ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED']) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR']) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED']) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
