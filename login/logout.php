<?php

require_once 'core/init.php';

// create a new user object to use the user functionality in the user class
$user = new User();
$user->logout();

Redirect::to('index.php');