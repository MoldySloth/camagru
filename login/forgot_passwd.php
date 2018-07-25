<?php

require_once 'core/init.php';

//$user = new User();
//
//// check if the user is logged in
//if(!$user->isLoggedIn()) {
//    Redirect::to('index.php');
//}

if (Session::exists('home')) {
    echo '<script type="text/javascript">alert("' . Session::flash('home') . '")</script>';
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/styling.css">
    <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
</head>
<body>

<?php

// check if the input actually exists
if(Input::exists()) {
    //check the token
    if(Token::check(Input::get('token'))) {
        // validate the user and email input as required
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            // rules that we will pass through in order to validate input
            'username' => array('required' => true),
            'email' => array('required' => true)
        ));

        if($validation->passed()) {
            // check to see if the user and email are in the database and are active
            // Instantiate a new user
            $user = new User(Input::get('username'));

            if(!$user->exists()) {
                Session::flash('home', 'Sorry account was not found');
                Redirect::to('index.php');
            } else {
                // check to see that email matches
                if (($user->data()->email) === (Input::get('email'))) {
                    echo 'emails match';

                    // send email to update password
                    $title = 'Forgot password';
                    $content = '<p>You have forgotten your password!<br>
                    Not to worry, you can update your password by pressing the url link below.<br>
                    <br>
                    Please click this link to change your password:';
                    $link = 'http://localhost:8080/camagru/login/change_passwd.php?username=';
                    $username = Input::get('username');
                    $email = Input::get('email');
                    $key = $user->data()->password;

                    Mail::send($title, $content, $link, $username, $email, $key);

                    Session::flash('home', 'You have been registered check your emails for a update password request!');
                    Redirect::to('index.php');
                } else {
                    echo '<script type="text/javascript">alert("An error occurred please try again")</script>';
                }
            }
        } else {
            // loop through errors
            foreach($validation->errors() as $error) {
                echo '<script type="text/javascript">alert("' . $error . '")</script>';
            }
        }
    }
}

?>

<form action="" method="post">
    <fieldset>
        <h1>Forgot password?</h1>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" autocomplete="off">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" autocomplete="off">
        </div>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <button type="submit">Email me</button>
    </fieldset>
</form>
<script src="../JS/script.js"></script>
</body>
</html>

