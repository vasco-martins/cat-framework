<?php


namespace Cat\Validators;


use Cat\Database\Database;
use Cat\Database\DB;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Validate
{

    /**
     * Return if givem param is a string
     * @param mixed $text
     * @return bool
     */
    public static function isString(mixed $value) : bool {
        return is_string($value);
    }

    /**
     * Return if given param is an integer
     * @param mixed $value
     * @return bool
     */
    public static function isInteger(mixed $value) : bool {
        return is_integer($value);
    }

    /**
     * Returns if given param is an array
     * @param mixed $value
     * @return bool
     */
    public static function isArray(mixed $value) : bool {
        return is_array($value);
    }

    /**
     * Get if length is greater than
     * @param string $value
     * @param int $min
     * @param bool $include
     * @return int
     */
    public static function minLength(string $value, int $min, bool $include = true) : int {
        return $include ? ($value >= $min) : ($value > $min);
    }

    /**
     * Get if length is min than
     * @param string $value
     * @param int $min
     * @param bool $include
     * @return int
     */
    public static function maxLength(string $value, int $min, bool $include = true) : int {
        return $include ? ($value <= $min) : ($value < $min);
    }

    /**
     * Get if length is min than
     * @param string $value
     * @param int $min
     * @param int $max
     * @param bool $include
     * @return int
     */
    public static function inBetween(string $value, int $min, int $max,  bool $include = true) : int {
        return $include ? ($value <= $min && $value >= $max) : ($value < $min && $value > $max);
    }

    /**
     * Validate email
     * @param string $value
     * @return bool
     */
    public static function isEmail(string $value): bool {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validate email
     * @param string|int $value
     * @param string $table
     * @param string $field
     * @param string|null $ignore
     * @return bool
     */
    public static function exists(string|int $value, string $table, string $field = 'id', string|null $ignore = null): bool {

        $query = 'SELECT * FROM ' . $table . ' WHERE ' . $field . ' = ?';

        $res = Database::instance()->prepare($query,[$value], one:  false);

        if(count($res) == 0) return false;

        if(!$ignore) return true;


        $exists = false;

        foreach($res as $value) {
            if($value->id != $ignore) {
                $exists = true;
            }
        }

        return $exists;
    }

    /**
     * Returns if file is an image
     * @param string $mime
     * @param string[] $mimes
     * @return bool
     */
    #[Pure] public static function isMimeImage(string $mime, array $mimes = ['jpeg', 'gif', 'png', 'jpg']) : bool {
        return in_array($mime, $mimes);
    }

    /**
     * Validate Uploaded Image File
     * @param UploadedFile|null $file
     * @param bool $required
     * @param int $maxSize
     * @return string|null
     */
    public static function validateUploadedImage(UploadedFile|null $file, bool $required = true, int $maxSize = 2000) : null|string {

        if(!$file && $required) {
            return 'Campo obrigatório';
        }

        if(!$file) {
            return null;
        }

        if(!Validate::isMimeImage($file->guessExtension())) {
            return 'Extensão inválida. Por favor insira uma ficheiro png, gif ou jpg';
        }

        if($file->getSize() > ($maxSize * 1000)) {
            return 'O ficheiro é demasiado grande.';
        }

        return null;
    }
}