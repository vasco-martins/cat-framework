<?php

namespace Cat\Builders\Components;

class ComponentBuilder
{

    private static string $namespace = 'App\Models\Components';

    public static function render(string $component, array $parameters = [])
    {
        $class = 'App\\Models\\Components\\' . self::convertToPath($component);

        $instance = new $class(...$parameters);
        $instance->render();
    }

    private static function convertToPath(string $component)
    {
        $paths = explode('.', $component);
        $paths = array_map('ucfirst', $paths);

        return implode('\\', $paths);
    }


}