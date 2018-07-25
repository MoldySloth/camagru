<?php
// Mail class to create and send mail

class Mail {

    static public function send($title, $content, $email, $user = null, $link = null, $key = null) {
        if ($link !== null) {
            $link_url = '<a href = "' . $link . $user. '&hash_key=' . $key . '" > LINK HERE </a ><br ></p >';
        } else {
            $link_url = '';
        }
        $to = $email;
        $subject = $title;
        $message = '
        <html>
        <head>
            <title>' . $title . '</title>
        </head>
        <body>'
        . $content . '<br>' . $link_url .
        '</body>
        </html>
        ';

        $headers = 'Form: the Camagru Team' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";  // Set from headers

        mail($to, $subject, $message, $headers); // send the mail
    }


}