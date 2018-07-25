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
			$like = new Like();
			$image_user = new User($image_data->user_id);

			// get the user id
			$user_id = $user->data()->id;

			// get the image id
			$image_id = Input::get('image_id');

			// get image data
			$image = new Image();
			$image_data = $image->getImageInfo($image_id);
			$image_user = new User($image_data->user_id);

			// enter like data
			try {
				$like->saveLike(array(
					'user_id' => $user_id,
					'image_id' => (int)$image_id,
					'created' => date('Y-m-d H:i:s')
				));
			} catch(Exception $e) {
				die ($e->getMessage());
				// can redirect the user to a specific page
			}

			if ($like) {
				// get image likes
				$imageLikes = $like->findImageLikes( $image_id );

				// add like count to image in gallery table
				$like->updateLikes( array(
					'likes' => $imageLikes
				), $image_id );

				if ( $image_user->data()->notify_me ) {
					// send email notification to new user
					$title   = 'Like | Notification';
					$content = '<p>Your image was just liked by ' . $user->data()->username . '. WOW!<br>
	                <br>
	                Please login to your account to view your likes.';

					Mail::send( $title, $content, $image_user->data()->email);

				}
			}
	} else {
		echo '<script type="text/javascript">alert("Like was not saved")</script>';
	}
}