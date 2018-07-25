<?php

class Image {
	// Define the path or allow the path to be set
	public $path;
	// Variable to save image data from database
	private $_db;
	private $_data;

	// Instantiate the class and set the path... magic method construct
	public function __construct() {
		// default path
		$this->path = __DIR__ . '/resources/uploads';
		// get BD instance
		$this->_db = DB::getInstance();
	}

	public function setPath($path) {
		// clean any '/' char after directory
		if (substr($path, -1) === '/') {
			$path = substr($path, 0, -1);
		}
		$this->path = $path;
	}

	// Generic function to get the contents of a directory that can only be accessed in this class
	private function getDirectory($path) {
		return scandir($path);
	}

    // save image into db
    public function saveImage($fields = array()) {
        if (!$this->_db->insert('gallery', $fields)) {
            throw new Exception('There was a problem adding this image to the database');
        }
    }

    // find images for user in database
	public function getUserGallery($user_id = null) {
		// getting all user images
		if($user_id === 'all') {
			// find all images saved for this all registered users
			$field = 'user_id';
			$images = $this->_db->get('gallery', array($field, '>=', 1));
			$imageResults = $images->results();

			return (count($images)) ? $imageResults : false;
		}
		if($user_id) {
			// find all images saved for this user
			$field = 'user_id';
			$images = $this->_db->get('gallery', array($field, '=', $user_id));
			$imageResults = $images->results();

			return (count($images)) ? $imageResults : false;
		}
		return false;
	}

    // find the user folder and make sure there are images in it
	public function getUserFolder($extensions = array('jpg', 'png')) {
		$galleryFolder = $this->getDirectory($this->path);
		$i = 0;

		foreach ($galleryFolder as $index => $image) {
			$extension = strtolower(end(explode('.', $image)));
			// Check to see if image is a valid image, if not, remove from array
			if (!in_array($extension, $extensions)) {
				unset($galleryFolder[$index]);
			} else {
				$image_info = explode('.', $image);
				$image_name = $image_info[0];
				$galleryFolder[$index] = array(
					'image_name' => $image_name,
					'image_id' => $i
				);
				$i = $i + 1;
			}
		}

		return (count($galleryFolder)) ? $galleryFolder : false;
	}

	public function getImageInfo($image_id = null) {
			echo "the image id in image class = " . $image_id;
		if($image_id && $image_id !== null) {
			// find all images saved for this image_id
			$field = 'id';
			$image = $this->_db->get('gallery', array($field, '=', $image_id));
			$imageResults = $image->first();

			return (count($image)) ? $imageResults : false;
		}
		return false;
	}

    public function data() {
        return $this->_data;
    }

}