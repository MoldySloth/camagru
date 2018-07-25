<?php

require_once 'core/init.php';

if (Session::exists('home')) {
    echo '<script type="text/javascript">alert("' . Session::flash('home') . '")</script>';
}

// create a new user object
$user = new User();

// check if user is logged in
if ($user->isLoggedIn()) {
// check if the input actually exists
    if (Input::exists()) {
        // create image array
        $image = new Image();

	    // get the user id
	    $user_id = $user->data()->id;
	    // if user id folder doesn't exist in uploads folder, create folder
	    if (!file_exists('../resources/uploads/' . $user_id)) {
		    echo "no file exists";
		    mkdir('../resources/uploads/' . $user_id);
	    }

        // check to see if file isset
        if (isset($_FILES['file_input'])) {
            // make sure there are no errors
            if($_FILES['file_input']['error'] == 0) {
                // Gather all the required data
                $fileName = $_FILES['file_input']['name'];
                $photo_blob = file_get_contents($_FILES['file_input']['tmp_name']);
                $photo =
                file_put_contents('../resources/uploads/' . $user_id . '/' . $fileName, $photo_blob);
	            $photo_url = '../resources/uploads/' . $user_id . '/' . $fileName;
            } else {
                echo 'An error occurred while the file was loading. ' . 'Error code: ' . intval($_FILES['file_input']['error']);
            }
        } else {
            echo 'Error! A file was not sent!';
        }

        // check for photo data input
        if ($photo_obj = Input::get('photo')) {
            // get info of specified image
            list($width_x, $height_x, $type_x) = getimagesize($photo_obj);
            list($imgType, $photo_obj) = explode(';', $photo_obj);
            list(, $imgExt) = explode('/', $imgType);
            list(, $photo_obj) = explode(',', $photo_obj);
            $photoName = uniqid() . '.' . $imgExt;
            $photo_blob = base64_decode($photo_obj);

            // save new image in
            file_put_contents('../resources/uploads/' . $user_id . '/' . $photoName, $photo_blob);

            $photo_url = '../resources/uploads/' . $user_id . '/' . $photoName;

        } else {
            echo "no photo was found";
        }

		// get overlay url
        $overlay_url = Input::get('overlay');
	    // get tag for filtering
        $tag = Input::get('tag');

        // save image to gallery table
        try {
            $image->saveImage(array(
                'user_id' => $user_id,
                'image_url' => $photo_url,
                'image_overlay' => $overlay_url,
                'tags' => $tag,
                'created' => date('Y-m-d H:i:s')
            ));
        } catch (Exception $e) {
            die ($e->getMessage());
            // can redirect user to new page if needed
        }
    }
    else {
        echo '<script type="text/javascript">alert("An error occurred while the file was being uploaded. Error code: ' . intval($_FILES['uploaded_file']['error']) . '")</script>';
    }
}