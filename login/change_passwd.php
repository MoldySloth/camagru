<?php

require_once 'core/init.php';

$user = new User();

if (Session::exists('home')) {
    echo '<script type="text/javascript">alert("' . Session::flash('home') . '")</script>';
}
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
    <link rel="stylesheet" type="text/css" href="../css/styling.css">
    <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
</head>
<body>
<form action="" method="post">
    <h1>Change password</h1>
    <fieldset>
        <legend><span class="number">1</span>Enter new password to update</legend>

<?php
// check if the user is logged in
if(!$user->isLoggedIn()) {
    // Check to see if username exists
    if(!$username = Input::get('username')) {
        Redirect::to('index.php');
    } else {
        ?>
                <div class="field">
                    <label for="password_new">New password</label>
                    <input type="password" name="password_new" id="password_new">
                </div>

                <div class="field">
                    <label for="password_new_again">New password again</label>
                    <input type="password" name="password_new_again" id="password_new_again">
                </div>

        <?php
        // check if the input actually exists
        if (Input::exists()) {
            //check the token

            if (Token::check(Input::get('token'))) {
                // validate the current password and if password new and again match as well as if they are all required
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'password_new' => array(
                        'required' => true,
                        'min' => 6
                    ),
                    'password_new_again' => array(
                        'required' => true,
                        'min' => 6,
                        'matches' => 'password_new'
                    )
                ));

                if ($validation->passed()) {
                    $user = new User(Input::get('username'));

                    // Check if user exists
                    if(!$user->exists()) {
                        Session::flash('home', 'An error has occurred, please try again');
                        Redirect::to('forgot_passwd.php');
                    } else {
                        // change the user password matches the current password
                        if (Input::get('hash_key') !== $user->data()->password) {
                            echo '<script type="text/javascript">alert("An error has occurred, please try again")</script>';
                        } else {
                            // the password matches so update
                            // generate a new salt when password is updated
                            $salt = Hash::salt(32);
                            $user->update(array(
                                'password' => Hash::make(Input::get('password_new'), $salt),
                                'salt' => $salt
                            ), $user->data()->id);

                            Session::flash('home', 'Your password has been updated');
                            Redirect::to('profile.php');
                        }
                    }
                } else {
                    // loop through errors
                    foreach ($validation->errors() as $error) {
                        echo '<script type="text/javascript">alert("' . $error . '")</script>';
                    }
                }
            }
        }
    }
} else {
    ?>
            <div class="field">
                <label for="password_current">Current password</label>
                <input type="password" name="password_current" id="password_current">
            </div>

            <div class="field">
                <label for="password_new">New password</label>
                <input type="password" name="password_new" id="password_new">
            </div>

            <div class="field">
                <label for="password_new_again">New password again</label>
                <input type="password" name="password_new_again" id="password_new_again">
            </div>

    <?php

    // check if the input actually exists
    if (Input::exists()) {
        //check the token
        if (Token::check(Input::get('token'))) {
            // validate the current password and if password new and again match as well as if they are all required
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'password_current' => array(
                    'required' => true,
                    'min' => 6
                ),
                'password_new' => array(
                    'required' => true,
                    'min' => 6
                ),
                'password_new_again' => array(
                    'required' => true,
                    'min' => 6,
                    'matches' => 'password_new'
                )
            ));

            if ($validation->passed()) {
                // change the user password matches the current password
                if (Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
                    echo '<script type="text/javascript">alert("Your current password is wrong.")</script>';
                } else {
                    // the password matches so update
                    // generate a new salt when password is updated
                    $salt = Hash::salt(32);
                    $user->update(array(
                        'password' => Hash::make(Input::get('password_new'), $salt),
                        'salt' => $salt
                    ));

                    Session::flash('home', 'Your password has been updated');
                    Redirect::to('index.php');
                }

            } else {
                // loop through errors
                foreach ($validation->errors() as $error) {
                    echo '<script type="text/javascript">alert("' . $error . '")</script>';
                }
            }
        }
    }
}
?>

            <button type="submit">Update password</button>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
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


