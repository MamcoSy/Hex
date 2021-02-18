<?php

declare(strict_types=1);

namespace Hex\Router;

use Exception;

class Router implements RouterInterface
{
    // Routing table
    protected array $routes     = [];

    // Route parameters
    protected array $parameters = [];

    // Controller suffix
    protected string $controllerSuffix = 'Controller';

    /**
     * @inheritdoc
     */
    public function add(string $route, array $parameters): void
    {
        $this->routes[$route] = $parameters;
    }

    /**
     * @inheritdoc
     */
    public function dispatch(string $url): void
    {
        if ($this->match($url))
        {
            $controllerString = $this->parameters['_controller'];
            $controllerString = $this->toUpperCamelCase($controllerString);
            $controllerString = $this->addNamespace($controllerString);

            if (class_exists($controllerString))
            {
                $controllerObject = new $controllerString();
                $action           = $this->parameters['_action'];
                $action           = $this->toCamelCase($action);

                if (method_exists($controllerObject, $action) && is_callable([$controllerObject, $action]))
                {
                    call_user_func([$controllerObject, $action], $this->parameters);
                }
                else
                {
                    throw new Exception();
                }
            }
            else
            {
                throw new Exception();
            }
        }
        else
        {
            throw new Exception();
        }
    }

    /**
     * Matching the incoming url with the routing table
     *
     * @param string $url
     *
     * @return boolean
     */
    private function match(string $url): bool
    {
        foreach ($this->routes as $route => $parameters)
        {
            if (preg_match($route, $url, $matches))
            {
                foreach ($matches as $key => $value)
                {
                    if (is_string($key))
                    {
                        $cleanParameters[$key] = $value;
                    }
                }
                $this->parameters = $cleanParameters;

                return true;
            }
        }

        return false;
    }

    /**
     * Transform the given string to upper camel case
     *
     * @param string $string
     *
     * @return string
     */
    private function toUpperCamelCase(string $string): string
    {
        return str_replace(' ', '', ucfirst(str_replace('-', ' ', $string)));
    }

    /**
     * Transform the given string to camel case
     *
     * @param string $string
     *
     * @return string
     */
    private function toCamelCase(string $string): string
    {
        return lcfirst($this->toUpperCamelCase($string));
    }

    /**
     * Adding the namespace for the given controller
     *
     * @param string $controllerString
     *
     * @return string
     */
    private function addNamespace(string $controllerString): string
    {
        $defaultNamespace = 'App\Controller\\';
        if (array_key_exists('_namespace', $this->parameters))
        {
            $defaultNamespace .= $this->parameters['_namespace'];
        }

        return $defaultNamespace . '\\' . $controllerString;
    }
}
