<?php

require_once 'core/init.php';

$user = new User();

// determine if the user is logged in or not
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/styling.css">
    <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
</head>


<?php
// check it the token exists to prevent CSRF
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        // validate stuff to same standard as new user
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'name' => array(
                'min' => 2,
                'max' => 50
            ),
            'email' => array(
                'address' => true
            )
        ));

        // check if validation is passed
        if ($validate->passed()) {
            // update username
            if (Input::get('username')) {
                //update... using try so that we can throw an exception inside our user method if it fails
                try {
                    $user->update(array(
                        'username' => Input::get('username')
                    ));

                    Session::flash('home', 'Your profile has been updated');
                    Redirect::to('profile.php');

                } catch (Exception $e) {
                    // getMessage method is part of the Exception object within php
                    die($e->getMessage());
                }
            }
            // update name
            if (Input::get('name')) {
                //update... using try so that we can throw an exception inside our user method if it fails
                try {
                    $user->update(array(
                        'name' => Input::get('name')
                    ));

                    Session::flash('home', 'Your profile has been updated');
                    Redirect::to('profile.php');

                } catch (Exception $e) {
                    // getMessage method is part of the Exception object within php
                    die($e->getMessage());
                }
            }
            // update email
            if (Input::get('email')) {
                //update... using try so that we can throw an exception inside our user method if it fails
                try {
                    $user->update(array(
                        'email' => Input::get('email')
                    ));

                    Session::flash('home', 'Your profile has been updated');
                    Redirect::to('profile.php');

                } catch (Exception $e) {
                    // getMessage method is part of the Exception object within php
                    die($e->getMessage());
                }
            }

            //check against current status email notifications in DB
            if (Input::get('email_notifications') !== $user->data()->notify_me) {
                // check to see if email notifications is on or off
                $email_me = (Input::get('email_notifications') === 'on') ? 1 : 0;
                //update... using try so that we can throw an exception inside our user method if it fails
                try {
                    $user->update(array(
                        'notify_me' => $email_me
                    ));

                    Session::flash('home', 'Your profile has been updated');
                    Redirect::to('profile.php');

                } catch (Exception $e) {
                    // getMessage method is part of the Exception object within php
                    die($e->getMessage());
                }
            }
        } else {
            // loop through errors that are returned
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}

?>

    <body>
    <form action="" method="post">
        <h1>Update user information</h1>
        <fieldset>
            <legend><span class="number">1</span>Your basic info</legend>
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" autocomplete="off">
            </div>
            <div class="field">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name); ?>">
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" autocomplete="off">
            </div>
            <div>
                <label for="email_notifications">
                    <input type="checkbox" name="email_notifications" id="email_notifications" <?php echo $user->data()->notify_me?'checked="checked"':''; ?>> Receive email notifications
                </label>
            </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <button type="submit">Update</button>
        </fieldset>
    </form>
    <div class="container">
        <div class="link">
            <a href="profile.php" class="link">Back</a>
        </div>
    </div>
    <script src="../JS/script.js"></script>
    </body>
</html>