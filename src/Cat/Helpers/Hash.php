<?php


namespace Cat\Helpers;


class Hash
{

    /**
     * Hash a text
     * @param string $text
     * @param string $algo
     * @return string
     */
    public static function make(string $text, string $algo = PASSWORD_ARGON2I) : string {
        return password_hash($text, $algo);
    }

    /**
     * Verify if match
     * @param string $text
     * @param string $check
     * @return bool
     */
    public static function check(string $text, string $check) : bool {
        return password_verify($text, $check);
    }
}