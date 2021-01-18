<?php

use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;

if(!function_exists('getBasePath')) {
    function getBasePath(): string
    {
        global $basePath;
        return $basePath;
    }
}

if(!function_exists('router')) {
    /**
     * Returns router object
     */
    function router(): \Cat\Router\Router
    {
        global $router;
        return $router;
    }
}

if(!function_exists('dd')) {
    /**
     * Dump and die
     * @param $arr
     */
    #[NoReturn] function dd(mixed $arr): void
    {
        echo '<div style="background-color:#2b2a27; color:#d0cfcd; line-height:1.2em; font-weight:normal; font:14px Menlo, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:100000; text-align: left;">';
        if(!isset($arr)) {
            echo 'Null';
        } else {
            var_dump((array) $arr);
        }

        echo '</div>';
        die;
    }
}

if(!function_exists('config')) {
    /**
     * Returns the requested configuration value
     * @param string $parameter
     * @param string $default
     * @return mixed
     */
    function config(string $parameter, string $default = ''): mixed
    {

        $file =  explode('.', $parameter);

        if($file[0] == 'global') {
            return globalConfig($file[1], $default);
        }

        $configFile = require getBasePath() . '/config/' . $file[0] . '.php';

        array_shift($file);

        $param = $configFile;

        foreach($file as $key) {
            if(!array_key_exists($key, $param)) {
                return $default;
            }
            $param = $param[$key];
        }

        return $param;
    }

}

if(!function_exists('globalConfig')) {
    function globalConfig(string $name, $default = '') {
        $config = \App\Models\Option::find($name, 'name');

        if(count($config) == 0) {
            return $default;
        }

        return $config[0]->value;
    }
}

if(!function_exists('inc')) {
    /**
     * Include the specified view
     * @param string $name
     * @param array $data
     */
    function inc(string $name, array $data = [])
    {
        global $router;

        $name = str_replace('.', '/', $name);
        extract($data);

        require getBasePath() . '/resources/views/' . $name . '.view.php';
    }

}

if(!function_exists('view')) {
    /**
     * Returns the specified view
     * @param string $name
     * @param array $data
     */
    function view(string $name, array $data = []): \Symfony\Component\HttpFoundation\Response
    {
        global $router;

        $name = str_replace('.', '/', $name);
        extract($data);

        require getBasePath() . '/resources/views/' . $name . '.view.php';

        return new \Symfony\Component\HttpFoundation\Response();
    }

}

if(!function_exists('response')) {
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function response(): \Symfony\Component\HttpFoundation\Response
    {
        return new \Symfony\Component\HttpFoundation\Response();
    }
}

if(!function_exists('assets')) {
    function assets(string $path) : string
    {
        return  config('app.url') . '/' . $path;
    }
}

if(!function_exists('env')) {
    function env($parameter, $default = null) : string
    {
        return $_ENV[$parameter] ?? $default;
    }
}

if(!function_exists('session')) {
    function session(): \Symfony\Component\HttpFoundation\Session\Session
    {
        return \Cat\Kernel\App::getSessionInstance();
    }
}


if(!function_exists('redirect')) {
    function redirect($url, $session = []): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        foreach($session as $key => $value) {
            session()->getFlashBag()->add($key, $value);
        }

        $res = new \Symfony\Component\HttpFoundation\RedirectResponse($url);


        return $res->send();
    }
}



if(!function_exists('back')) {
    function back($session = []): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        return redirect($request->headers->get('referer'), $session);
    }
}


if(!function_exists('error')) {
    function error(string $field): string
    {
        $errors = session()->getFlashBag()->get('error');

        if(!array_key_exists($field, $errors)) return '';

        return $errors[$field];
    }
}

if(!function_exists('errors')) {
    function errors(): array
    {
        return session()->getFlashBag()->get('errors')[0] ?? [];
    }
}


if(!function_exists('auth')) {
    #[Pure] function auth() : \Cat\Auth\Auth
    {
        return new \Cat\Auth\Auth();
    }
}

if(!function_exists('isActiveRoute')) {
    function isActiveRoute(array|string $routes, mixed $output = 'active') {
        $active = router()->currentRouteName();
        foreach((array) $routes as $pattern) {

            /**
             * Código retirado da libraria Illuminate/Support/Str
             */
            if ($pattern === $active) {
                return $output;
            }

            $pattern = preg_quote($pattern, '#');

            $pattern = str_replace('\*', '.*', $pattern);

            if (preg_match('#^'.$pattern.'\z#u', $active) === 1) {
                return $output;
            }

        }
    }
}

if(!function_exists('miau')) {
    function miau(string $message)
    {
        \Cat\Kernel\Miau::send($message);
    }
}


if(!function_exists('truncate')) {
    #[Pure] function truncate($string, $limit, $break=".", $pad="...")
    {
        if(strlen($string) <= $limit) return $string;

        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;
    }
}


