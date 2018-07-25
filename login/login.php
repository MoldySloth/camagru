<?php

require_once 'core/init.php';

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
// check if input exists if the form has been submitted
if(Input::exists()) {
    // check token as per supplied by the form
    if (Token::check(Input::get('token'))) {
        // validate input
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        // check if validation passes
        if ($validation->passed()) {

            // Instantiate a new user
            $user = new User();

            // remember me functionality if it is on or not (using a ternary operator) and then pass through in our log in method
            $remember = (Input::get('remember') === 'on') ? true : false;

            // process a login by creating a login variable, utilising the user object and login method and passing through username and password
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);
            // check if login is successful
            if ($login) {
                Redirect::to('index.php');
            } else {
                echo '<script type="text/javascript">alert("Sorry log in has failed")</script>';
            }

        } else {
            foreach ($validation->errors() as $error) {
                echo '<script type="text/javascript">alert("' . $error . '")</script>';
            }
        }
    }
}

?>

    <form action="" method="post">
        <h1>Log in</h1>
        <fieldset>
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" autocomplete="off">
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="off">
                <p><a href="forgot_passwd.php">Forgot your password?</a></p>
            </div>
            <div>
                <label for="remember">
                    <input type="checkbox" name="remember" id="remember"> Remember me
                </label>
            </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <button type="submit">Log in</button>
            <p>Not a member? <a href="register.php">Register</a></p>
        </fieldset>
    </form>
    <script src="../JS/script.js"></script>
</body>
</html>

