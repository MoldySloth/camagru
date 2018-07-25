<?php

// allows us to deal with php sessions, set a session, check if it exists

class Session {
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? TRUE : FALSE;
    }

    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    public static function get($name) {
        return $_SESSION[$name];
    }

    // unset it if it exists
    public static function delete($name) {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    // flashing a session is to put a message to someone and then when they refresh it doesn't exist anymore
     public static function flash($name, $string = '') {
        // check it the session exists
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
     }
}