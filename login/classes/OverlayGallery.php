<?php

class OverlayGallery {
    // Define the path or allow the path to be set
    public $path;

    // Instantiate the class and set the path... magic method construct
    public function __construct() {
        // default path
        $this->path = __DIR__ . '/resources/overlay_images';
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

    // get the overlay images in the overlay folder
    public function getOverlayImages($extensions = array('jpg', 'png')) {
        $images = $this->getDirectory($this->path);
        $i = 0;

        foreach ($images as $index => $image) {
            $temp = explode('.', $image);
	        $temp_extension = end($temp);
	        $extension = strtolower($temp_extension);
            $information = explode('_', $image);

            // Check to see if image is a valid image, if not, remove from array
            if (!in_array($extension, $extensions)) {
                unset($images[$index]);
            } else {
                $images[$index] = array(
                    'full' => $this->path . '/' . $image,
                    'thumb' => $this->path . '/thumb/' . $image,
                    'image_id' => $i,
                    'filter' => $information[1]
                );
                $i = $i + 1;
            }
        }

        return (count($images)) ? $images : false;
    }
}