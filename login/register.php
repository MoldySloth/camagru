<?php
    require_once 'core/init.php';

    if (Input::exists()) {
        // check if the token is good
        if(Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                // rules that we will pass through in order to validate input
                'username' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 20,
                    'unique' => 'users'
                ),
                'password' => array(
                    'required' => true,
                    'min' => 6
                ),
                'password_again' => array(
                    'required' => true,
                    'matches' => 'password'
                ),
                'email' => array(
                    'required' => true,
                    'address' => true
                ),
            ));

            if ($validation->passed()) {
                // instantiate the user class
                $user = new User();

                $salt = Hash::salt(32);
                $email = Input::get('email');
                $username = Input::get('username');


                // enter user data
                try {
                    $user->create(array(
                        'username' => Input::get('username'),
                        'password' => Hash::make(Input::get('password'), $salt),
                        'salt' => $salt,
                        'name' => '',
                        'email' => $email,
                        'profile_img' => '../resources/icons/DOMO_profile_default.jpg',
                        'joined' => date('Y-m-d H:i:s'),
                        'group' => 1
                    ));
                } catch(Exception $e) {
                    die ($e->getMessage());
                    // can redirect the user to a specific page
                }

                // if user was added... add to confirmation table
                if ($user) {
                    // instantiate the confirmation class
                    $confirm = new Confirm();
                    $user = new User($username);

                    $data = $user->data(); // create a data set for user data
                    // create a random key
                    $key = Hash::make($email, $data->salt);
                    $user_id = $data->id;

                    // add confirmation row
                    try {
                        $confirm->create(array(
                            'user_id' => $user_id,
                            'key_hash' => $key,
                            'email' => $email
                        ));
                    } catch(Exception $e) {
                        die ($e->getMessage());
                        // can redirect to a specific page
                    }

                    if ($confirm) {
                        // send email notification to new user
                        $title = 'Sign up | Validation';
                        $content = '<p>Thank you for signing up!<br>
        Your account has been created, you can login and activate your account by pressing the url link below.<br>
        <br>
        Please click this link to activate your account:';
                        $link = 'http://localhost:8080/camagru/login/login.php?user_id=';

                        Mail::send($title, $content, $email, $user_id, $link, $key);

                        Session::flash('home', 'You have been registered check your emails for a validation request!');
                        Redirect::to('index.php');

                    } else {
                        echo '<script type="text/javascript">alert("Email could not be sent. Confirmation was not")</script>';
                    }

                } else {
                    echo '<script type="text/javascript">alert("User was not created")</script>';

                }
             } else {
                foreach ($validation->errors() as $error) {
                    echo '<script type="text/javascript">alert("' . $error . '")</script>';
                }
            }
        }
    }

?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration</title>
        <link rel="stylesheet" type="text/css" href="../css/styling.css">
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <form action="" method="post">
            <h1>Register</h1>
            <fieldset>
                <legend><span class="number">1</span>Your basic info</legend>
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
                </div>
                <div class="field">
                    <label for="password">Choose a password</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="field">
                    <label for="password_again">Enter your password again</label>
                    <input type="password" name="password_again" id="password_again">
                </div>
                <div class="field">
                    <label for="email">Enter your email address</label>
                    <input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="off">
                </div>
            </fieldset>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <fieldset>
                <button type="submit">Register</button>
                <p>Already a member? <a href="login.php">Log in</a></p>
            </fieldset>
        </form>
        <script src="../JS/script.js"></script>
    </body>
</html>

