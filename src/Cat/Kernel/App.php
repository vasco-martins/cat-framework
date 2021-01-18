<?php

namespace Cat\Kernel;


use Dotenv\Dotenv;
use Cat\Router\Router;
use Cat\Router\RouterException;
use Symfony\Component\HttpFoundation\Session\Session;

class App
{

    private static Session $session;

    public function __construct(public $basePath)
    {
        self::$session = new Session();
    }

    /**
     * Starts the application
     * @param Router $router
     * @throws RouterException
     */
    public function run(Router $router) {
        // Load .env file
        if(file_exists($this->basePath . '/.env')) {
            $dotenv = Dotenv::createImmutable($this->basePath);
            $dotenv->load();
        }

        self::getSessionInstance()->start();

        require $this->basePath . '/routes/web.php';
        require $this->basePath . '/routes/admin.php';

        $router->run();
    }

    public static function getSessionInstance() : Session {
        if(self::$session === null) {
            self::$session = new Session();
        }

        return self::$session;
    }

}