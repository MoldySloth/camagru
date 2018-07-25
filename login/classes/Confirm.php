<?php

// Confirmation allows the user to confirm their account

Class Confirm {

    protected $_db;
    protected $_data;

    // Creates an instance of the DB
    public function __construct($user = null) {
        $this->_db = DB::getInstance();
    }

    // ability to create a user confirmation
    public function create($fields = array()) {
        if (!$this->_db->insert('confirm_account', $fields)) {
            throw new Exception('There was a problem adding the user to the confirmation table.');
        }
    }


    static public function send($user_id, $email, $key) {
        $to = $email;
        $subject = 'Signup | Verification';
        $message = '
        <html>
        <head>
            <title>Signup ! Verification</title>
        </head>
        <body>
        <p>Thank you for signing up!<br>
        Your account has been created, you can login and activate your account by pressing the url below.<br>
        <br>
        Please click this link to activate your account:<br>
        <a href="http://localhost:8080/camagru/login/login.php?user_id='.$user_id. '&hash_key='.$key. '">LINK HERE</a><br></p>
        
        </body>
        </html>
        ';

        $headers = 'Form: the Camagru Team' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";  // Set from headers

        mail($to, $subject, $message, $headers); // send the mail
    }
}