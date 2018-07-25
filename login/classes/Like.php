<?php

class Like {
	// Variable to save image data from database
	private $_db;
	private $_data;

	// Instantiate the class and set the path... magic method construct
	public function __construct() {
		// get BD instance
		$this->_db = DB::getInstance();
	}

	// update a image likes
	public function updateLikes($fields = array(), $image_id = null) {
		if(!$this->_db->update('gallery', $image_id, $fields)) {
			throw new Exception('There was a problem updating your likes.');
		}
	}

	// save like into db
	public function saveLike($fields = array()) {
		if (!$this->_db->insert('image_likes', $fields)) {
			throw new Exception('There was a problem adding this like to the database');
		}
	}

	// find if user has likes
	public function findUserLikes($user_id = null) {
		if ( $user_id ) {
			$field      = 'user_id';
			$likes      = $this->_db->get( 'image_likes', array( $field, '=', $user_id ) );
			$userLikes = $likes->results();

			return ( $likes->count() ) ? $userLikes : false;
		}
	}

	// find all image likes
	public function findImageLikes($image_id = null) {
		if ($image_id) {
			$field      = 'image_id';
			$likes      = $this->_db->get( 'image_likes', array( $field, '=', $image_id ) );

			return ($likes->count());
		}
	}

	// find all image likes
	public function checkUserLikes($image_id = null, $user_id = null) {
		if ($user_id) {
			$userLikes = $this->findUserLikes($user_id);
			if ($userLikes) {
				foreach ($userLikes as $like) {
					if ($image_id === $like->image_id) {
						return true;
					}
				}
			}
			return false;
		}
		return false;
	}

	public function data() {
		return $this->_data;
	}

}