<?php

/**
 * TODO: remove
 * Display all errors
 * ROZANNNEEEEE
 * PLEASEEE
 * REMOVE THIS
 * WHEN DEPLOYING
 * THANK YOU
 *
 */
ini_set('display_errors', E_ALL);

include '../config/database.php';
// included on every page... to auto load classes
// start session
// set config file
// auto load classes
// include the functions 

session_start();

// set config which is a global variable, with a an array of different config names
// create config class... to pull information

// storage area with database properties localhost(DNS lookup will take time)

$GLOBALS['config'] = array(
    'mysql' => array(
        'dsn' => $DB_DSN,
        'user' => $DB_USER,
        'pass' => $DB_PASSWORD,
        'opt' => $DB_OPT,
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 86400
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
);

// auto load classes, by using a php function that is run every time a class is accessed, 
// then from the argument list of this function take the class name

spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

// check to see if the user is logged in
if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    // do a hash check
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}