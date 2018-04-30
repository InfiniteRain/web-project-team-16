<?php

namespace WebTech\Hospital;

/**
 * Router class.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class Router
{
    /**
     * @var bool Whether or not the request was handled already.
     */
    private static $isHandled = false;

    /**
     * @var array Stores registered routes.
     */
    private static $registeredRoutes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * Handles the request.
     *
     * @throws \Exception
     */
    public static function handle()
    {
        if (self::$isHandled) {
            return;
        }

        $basePath = implode(
            '/',
            array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)
        );
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basePath));
        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $uri = '/' . trim($uri, '/');

        self::loadRoutes();

        $rendered = false;
        foreach (self::$registeredRoutes[$_SERVER['REQUEST_METHOD']] as $route => $handle) {
            if (preg_match($route, $uri, $matches) === 1) {
                $controllerHandle = explode('@', $handle);
                $controllerClassName = __NAMESPACE__ . '\Controllers\\' . $controllerHandle[0];
                $controller = new $controllerClassName;

                echo call_user_func_array(
                    [$controller, $controllerHandle[1]],
                    array_merge([$_REQUEST], array_slice($matches, 1))
                );

                $rendered = true;
                break;
            }
        }

        if (!$rendered) {
            throw new \Exception("Route matching {$_SERVER['REQUEST_METHOD']} -> {$uri} does not exist.");
        }

        self::$isHandled = true;
        Session::setRedirectData([]);
    }

    /**
     * Registers a GET route.
     *
     * @param string $routeRegex
     * @param string $handle
     * @throws \Exception
     */
    public static function registerGet(string $routeRegex, string $handle)
    {
        self::register('GET', $routeRegex, $handle);
    }

    /**
     * Registers a POST route.
     *
     * @param string $routeRegex
     * @param string $handle
     * @throws \Exception
     */
    public static function registerPost(string $routeRegex, string $handle)
    {
        self::register('POST', $routeRegex, $handle);
    }

    /**
     * Registers a route.
     *
     * @param string $method
     * @param string $routeRegex
     * @param string $handle
     * @throws \Exception
     */
    private static function register(string $method, string $routeRegex, string $handle)
    {
        if (!in_array($method, ['GET', 'POST'])) {
            throw new \Exception("Invalid method {$method}.");
        }

        $controllerHandle = explode('@', $handle);
        $controller = __NAMESPACE__ . "\Controllers\\{$controllerHandle[0]}";

        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} is not found.");
        }

        if (!method_exists($controller, $controllerHandle[1])) {
            throw new \Exception("Controller {$controller} does not have method {$controllerHandle[1]}.");
        }

        if (isset(self::$registeredRoutes[$method][$routeRegex])) {
            throw new \Exception("Route {$method} -> {$routeRegex} already exists.");
        }

        self::$registeredRoutes[$method][$routeRegex] = $handle;
    }

    /**
     * Parses the routes.php files, registering all the routes.
     */
    private static function loadRoutes()
    {
        require_once __DIR__ . '/../routes.php';
    }
}
