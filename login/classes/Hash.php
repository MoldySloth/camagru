<?php
// allow to generate a variety of different hashes

class Hash {
    // one way 256? and a salt

    // will make a hash from a string and then  append a salt 
    public static function make($string, $salt = '') {
        // hash the string and then concatenate on the salt that we provide, otherwise the salt is just an empty string
        return hash('sha256', $string . $salt);
    }

    // create a salt of a particular length
    public static function salt($length) {
        // returns a bunch of rubbish characters
        return utf8_encode(random_bytes($length));
    }

    // basically just making and returning a hash
    public static function unique() {
        return self::make(uniqid());
    }
}