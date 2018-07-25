<?php

class Comment {
	// Variable to save comments data from database
	private $_db;
	private $_data;

	// Instantiate the class and set the path... magic method construct
	public function __construct() {
		// get BD instance
		$this->_db = DB::getInstance();
	}

	// update a comment
	public function updateComment($fields = array(), $image_id = null) {
		if(!$this->_db->update('gallery', $image_id, $fields)) {
			throw new Exception('There was a problem updating your comment.');
		}
	}

	// save comment into db
	public function saveComment($fields = array()) {
		if (!$this->_db->insert('image_comments', $fields)) {
			throw new Exception('There was a problem adding this comment to the database');
		}
	}

	// find if user has comments
	public function findUserComments($user_id = null) {
		if ( $user_id ) {
			$field      = 'user_id';
			$comments      = $this->_db->get( 'image_comments', array( $field, '=', $user_id ) );
			$userComments = $comments->results();

			return ( count( $comments ) ) ? $userComments : false;
		}
	}

	// find all image comments
	public function findImageComments($image_id = null) {
		if ($image_id) {
			$field      = 'image_id';
			$comments      = $this->_db->get( 'image_comments', array( $field, '=', $image_id ) );
			$imageComments = $comments->results();

			return ( count( $comments ) ) ? $imageComments : false;
		}
	}

	// find all image likes
	public function checkUserLikes($image_id = null, $user_id = null) {
		$userLikes = $this->findUserLikes($user_id);

		foreach ($userLikes as $like) {
			if ($image_id === $like->image_id) {
				return true;
			}
		}
		return false;
	}

	public function data() {
		return $this->_data;
	}

}