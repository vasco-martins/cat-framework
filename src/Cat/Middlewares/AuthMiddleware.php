<?php


namespace Cat\Middlewares;


use Cat\Auth\Auth;

class AuthMiddleware implements Middleware
{
    public static function handle(): void
    {
        if(Auth::user() == null) {
            redirect(router()->url('auth.login'));
        }
    }
}