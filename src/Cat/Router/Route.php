<?php


namespace Cat\Router;


use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Request;

class Route
{

    private array $matches = [];
    private array $params = [];

    #[Pure] public function __construct(private string $path, private mixed $callable, public string|null $name = null) {
        $this->path = trim($path, '/');
    }

    public function with(string $param, string $regex) {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    public function match($url) {
        $url = trim($url, '/');
        // Replace url variables
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";

        if(!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;

        return true;
    }

    public function call() {
        $request = Request::createFromGlobals();
        $this->matches[] = $request;

        if(is_string($this->callable)) {
            $params = explode('@', $this->callable);
            $controller = "\\App\\Http\\Controllers\\" . $params[0];
            $controller = new $controller;

            return call_user_func_array([$controller, $params[1]], $this->matches);

        }
        return call_user_func_array($this->callable, $this->matches);
    }

    private function paramMatch($match) {
        if(isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }

        return '([^/]+)';
    }

    public function getUrl($params) {
        $path = $this->path;
        foreach($params as $k=>$v) {
            $path = str_replace(":$k", "$v", $path);
        }
        return assets($path);
    }

}