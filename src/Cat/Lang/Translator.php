<?php

namespace Cat\Lang;

use Cat\Kernel\App;

class Translator
{

    private static string $langField = 'LANG';

    public static function translate(string $key): string
    {
        if (!self::getLanguage()) return $key;

        $file = explode('.', $key);

        $translationFile = require getBasePath() . '/resources/lang/' . $file[0] . '.php';

        array_shift($file);

        $param = $translationFile;

        foreach ($file as $key) {
            if (!array_key_exists($key, $param)) {
                return $key;
            }
            $param = $param[$key];
        }

        return $param;

    }

    public static function getLanguage()
    {
        return App::getSessionInstance()->get(self::$langField);
    }

    public static function setLanguage(string $languageCode): void
    {
        App::getSessionInstance()->set(self::$langField, $languageCode);
    }

    public static function clearLanguage()
    {
        App::getSessionInstance()->remove(self::$langField);
    }

    public static function getDefaultLanguage()
    {
        return config('lang.default', null);
    }


}