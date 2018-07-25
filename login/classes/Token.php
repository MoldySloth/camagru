<?php

// cross site request forgery protection, allow us to check if the token has been set on a form and it matches the current user session token
// generate a token
// check if a token is valid and exists, delete the token
// a token is generated for each refresh of the page

class Token {
    // medthod to generate a token
    public static function generate() {
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }

    // check if a token is valid and exists
    public static function check($token) {
        $tokenName = Config::get('session/token_name');

        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return TRUE;
        }

        return FALSE;
    }
}