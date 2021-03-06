<?php

namespace Cat\Cache;

class HTMLCache
{

    public static function start($key, $duration)
    {
        $file = self::getPath($key);

        // Serve from the cache if it is younger than $file
        if (file_exists($file) && time() - $duration < filemtime($file)) {
            readfile($file);
            exit;
        }
        ob_start();
    }

    private static function getPath($key): string
    {
        return getBasePath() . 'cache/html/cached-' . $key . '.html';
    }

    public static function end($key)
    {
        $cached = fopen(self::getPath($key), 'w');
        fwrite($cached, ob_get_contents());
        fclose($cached);
        ob_end_flush();
    }

}