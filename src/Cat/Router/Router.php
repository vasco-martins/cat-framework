<?php


namespace Cat\Router;


class Router
{

    private array $routes = [];
    private array $namedRoutes = [];
    public Route $currentRoute;

    public function __construct(private string $url) {
    }

    public function get(string $path, mixed $callable, string $name = null) {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post(string $path, mixed $callable, string $name = null) {
        return $this->add($path, $callable, $name, 'POST');
    }

    public function delete(string $path, mixed $callable, string $name = null) {
        return $this->add($path, $callable, $name, 'DELETE');
    }

    public function add(string $path, mixed $callable, $name, string $method) {
        $route = new Route($path, $callable, $name);
        $this->routes[$method][] = $route;

        if(is_string($callable) && $name === null) {
            $name = $callable;
        }

        if($name) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    public function run() {

        // Check if route is valid
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if($route->match($this->url)) {
                $this->currentRoute = $route;
                return $route->call();
            }
        }

        throw new RouterException('No matching routes');
    }

    public function url(string $name, array $params = []) {

        if(!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matched ' . $name);
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }

    public function currentRouteName() {
        return $this->currentRoute->name;
    }


}