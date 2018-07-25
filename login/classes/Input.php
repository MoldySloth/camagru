<?php

// allows us to work with input and data

class Input{
    // method to check if any data exists, if data has been provided
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return (!empty($_POST)) ? TRUE : FALSE;
            break;
            case 'get':
                return (!empty($_GET)) ? TRUE : FALSE;
            break;
            default:
                return FALSE;
            break;
        }
    }

    // static method to retrieve an item
    public static function get($item) {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return ''; // if data is not available, return an empty string
    }
}