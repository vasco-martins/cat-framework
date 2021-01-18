<?php


namespace Cat\Validators;


use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Request;

abstract class Validator
{

    public abstract static function handle(Request $request) : array;

    #[Pure] public static function sanitize(mixed $value): string {
        return htmlspecialchars($value);
    }

    public static function only(Request $request, array $params) : array {
        $data = [];
        foreach($params as $key => $param) {
            $data[$param] = self::sanitize($request->get($param));
        }

        return $data;
    }

}