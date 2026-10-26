<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function get(string $path, array|string|callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array|string|callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, array|string|callable $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, array|string|callable $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    public function add(string $method, string $path, array|string|callable $handler): void
    {
        // Convert route parameters like {id} and optional parameters like {id?} into regex patterns
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\?\}/', '(?P<$1>[^\/]*)?', $path);
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^\/]+)', $pattern);
        $pattern = "#^" . $pattern . "$#D";

        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function dispatch(string $uri, string $method): mixed
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Filter out numeric keys to keep only named parameters
                $params = array_filter($matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);

                return $this->callHandler($route['handler'], $params);
            }
        }

        // 404 Handler
        http_response_code(404);
        echo "404 - Page Not Found";
        return null;
    }

    protected function callHandler(array|string|callable $handler, array $params): mixed
    {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        if (is_string($handler)) {
            $segments = explode('@', $handler);
            $controller = "App\\Controllers\\" . $segments[0];
            $method = $segments[1];

            if (class_exists($controller) && method_exists($controller, $method)) {
                $instance = new $controller();
                return call_user_func_array([$instance, $method], $params);
            }
        } elseif (is_array($handler)) {
            [$controller, $method] = $handler;
            if (is_string($controller)) {
                $controller = "App\\Controllers\\" . $controller;
            }

            if (class_exists($controller) && method_exists($controller, $method)) {
                $instance = new $controller();
                return call_user_func_array([$instance, $method], $params);
            }
        }

        throw new \Exception("Invalid route handler or controller method not found.");
    }
}
