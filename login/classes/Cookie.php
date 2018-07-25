<?php
// allows us to deal with cookies
// static methods like get or put, store and check if they exist

class Cookie {
    // check if a cookie actually exists
    public static function exists($name) {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    // get the value of a cookie
    public static function get($name) {
        return $_COOKIE[$name];
    }

    public static function put($name, $value, $expiry) {
        // set the cookie time appended to current time
        if (setcookie($name, $value, time() + $expiry, '/')) {
            // if it works return true
            return true;
        }
        return false;
    }

    public static function delete($name) {
        // to delete a cookie you reset it with a negative value or null or empty string, aka uses the put method
        self::put($name, '', time() - 1);
    }

}