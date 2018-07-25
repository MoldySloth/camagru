<?php

// check if super globals contains file
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // store file properties
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // work out the file extension
    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext));

    $allowed = array('png', 'jpg', 'jpeg');

    // check if extension is allowed
    if (in_array($file_ext, $allowed)) {
        // check if there was no errors
        if ($file_error === 0) {
            // check if file is less than 2mb
            if ($file_size <= 2097152) {
                // generate a unique filename
                $file_name_new = uniqid() . '.' . $file_ext;
                $file_dest = '../resources/uploads/' . $file_name_new;

                // move the file into uploads folder
                if (move_uploaded_file($file_tmp, $file_dest)) {
                    echo $file_dest;
                }
            }
        }
    }


}