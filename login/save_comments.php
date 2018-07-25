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
		// check if the token is good
//		if(Token::check(Input::get('token'))) {
		// create new like instance
		$comment = new Comment();
		$image_user = new User($image_data->user_id);

		// get the user id
		$user_id = $user->data()->id;

		// get the image id
		$image_id = Input::get('image_id');

		// get image data
		$image = new Image();
		$image_data = $image->getImageInfo($image_id);
		$image_user = new User($image_data->user_id);

		// get the comment
		$image_comment = Input::get('image_comment');


		// enter like data
		try {
			$comment->saveComment(array(
				'user_id' => $user_id,
				'image_id' => $image_id,
				'comment' => $image_comment,
				'created' => date('Y-m-d H:i:s')
			));
		} catch(Exception $e) {
			die ($e->getMessage());
			// can redirect the user to a specific page
		}

		if ($comment) {
			if ( $image_user->data()->notify_me ) {
				// send email notification to new user
				$title   = 'Comment | Notification';
				$content = '<p>Your image was just commented on by ' . $user->data()->username . '. WOW!<br>
	            The comment was:<br>' . $image_comment . '
	            <br>
	            <br>
	            Please login to your account to view your comments.';

				Mail::send( $title, $content, $image_user->data()->email);

			}
		}

	} else {
		echo '<script type="text/javascript">alert("Comment was not saved")</script>';
	}
}