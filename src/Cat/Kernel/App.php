<?php

namespace Cat\Kernel;


use Cat\Lang\Translator;
use Cat\Router\Router;
use Cat\Router\RouterException;
use Dotenv\Dotenv;
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
    public function run(Router $router)
    {
        $this->loadEnvironment();
        self::getSessionInstance()->start();
        $this->loadTranslation();

        require $this->basePath . '/routes/web.php';

        $router->run();
    }

    private function loadEnvironment()
    {
        if (!file_exists($this->basePath . '/.env')) return;

        $dotenv = Dotenv::createImmutable($this->basePath);
        $dotenv->load();
    }

    public static function getSessionInstance(): Session
    {
        if (self::$session === null) {
            self::$session = new Session();
        }

        return self::$session;
    }

    private function loadTranslation()
    {
        if (Translator::getLanguage()) return;

        Translator::setLanguage(Translator::getDefaultLanguage());
    }

}