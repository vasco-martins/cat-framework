<?php


namespace Cat\Helpers;


use JetBrains\PhpStorm\Pure;

class SessionErrors
{

    public function __construct(private array $errors)
    {
    }

    #[Pure] public function has($field): bool
    {
        return array_key_exists($field, $this->errors);
    }

    public function message($field): string
    {
        return $this->errors[$field];
    }

    public function all(): array
    {
        return $this->errors;
    }

}