<?php


namespace Cat\Auth;

class Auth
{
    public static function login($id) {
        session()->set('APP_LOGIN_ID', $id);
    }

    public static function logout() {
        session()->remove('APP_LOGIN_ID');
    }

    public static function user() {
        $id = session()->get('APP_LOGIN_ID');
        return config('auth.model')::find($id);
    }

}