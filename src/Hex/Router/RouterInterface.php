<?php

declare(strict_types=1);

namespace Hex\Router;

interface RouterInterface
{
    /**
     * Adding new route to the routing table
     *
     * @param string $route
     * @param array  $parameters
     */
    public function add(string $route, array $parameters): void;

    /**
     * Dispatch route and create controller object
     * and then execute the appropriate method
     *
     * @param string $url
     */
    public function dispatch(string $url): void;
}
