<?php

namespace Cat\Builders\Components;

class ComponentBuilder
{

    private static string $namespace = 'App\Models\Components';

    public static function render(string $component, array $parameters = [])
    {
        $class = 'App\\Components\\' . self::convertToPath($component);

        $instance = new $class(...$parameters);
        $instance->render();
    }

    private static function convertToPath(string $component)
    {
        $paths = explode('.', $component);
        $paths = array_map('ucfirst', $paths);

        $className = end($paths);
        $className = explode('-', $component);
        $className = array_map('ucfirst', $className);
        $paths[count($paths) - 1] = implode('', $className);

        return implode('\\', $paths);
    }


}