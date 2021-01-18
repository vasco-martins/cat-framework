<?php


namespace Cat\Kernel;


use JetBrains\PhpStorm\Pure;

class Miau
{


    public static function send(string $message): void {
        if(self::createCurrentFolder()) {
            file_put_contents(self::getCurrentPath(), self::buildLogLine($message), FILE_APPEND | LOCK_EX);
        }
    }

    #[Pure] private static function buildLogLine(string $message): string {
        return '[' . date("Y-m-d H:i:s"). '] ' . $message . "\n";
    }

    /**
     * Creates the current folder if doesn't exist
     * @return bool
     */
    private static function createCurrentFolder() : bool {
        return is_dir(self::getCurrentFolder()) || mkdir(self::getCurrentFolder(), 0777, true);
    }

    /**
     * Gets the current folder
     * @return string
     */
    private static function getCurrentFolder() : string {
        return self::getPath() . '/' . date('Y-M');
    }

    private static function getCurrentPath() : string {
        return self::getCurrentFolder() . '/' . date('d-M') . '.log';
    }

    /**
     * Gets the base path
     * @return string
     */
    private static function getPath() : string {
        return getBasePath() . '/miaus';
    }

}