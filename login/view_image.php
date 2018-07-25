<?php

require_once 'core/init.php';

if (Session::exists('home')) {
	echo '<script type="text/javascript">alert("' . Session::flash('home') . '")</script>';
}

// create a new user object
$user = new User();

// check if user is logged in
if ($user->isLoggedIn()) {
	echo "the user is logged in";
	// create image array
	$image = new Image();

	$image_id = Input::get('image');
	echo "the image id is: " . $image_id;

	// get the image info
	$image_info = $image->getImageInfo($image_id);

	// get image comments


//	}
} else {
	echo "the user is not logged in";
	die();
}