<?php
// deal with 404 errors or redirect to a specific page (abstracting the header function)

class Redirect {
    public static function to($location = null) {
        // check if the location has been defined
        if ($location) {
            // ability to pass in an error include a template to display a 404 error
            // a redirect to path is never numeric
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        exit();
                        break;
                }
            }
            header('Location: ' . $location);
            exit();
        }
    }
}