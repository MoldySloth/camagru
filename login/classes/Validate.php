<?php

// allows to validate and check if validation is passed
class Validate {
    // check if it has passed or not
    // are there errors and store the errors
    // create and instance of the database and then set that in the constuctor
    private $_passed = FALSE,
            $_errors = array(),
            $_db = null;

    // called when the validate class is instantiated
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    // passing in the data we want to check, post or get and an array of the rules
    public function check($source, $items = array()) {
        // list through all the rules we have defined check against data and then add errors where needed
        foreach ($items as $item => $rules) {
            // the item is each item and then array of rules that govern each item within
            // iterate through the set of rules in a nest foreach loop
            foreach ($rules as $rule => $rule_value) {
                // grab the value of each of these items
                $value = trim($source[$item]);
                // sanitising the item value
                $item = escape($item);
                // check if it is required
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                            break;
                        case 'address':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$value} email is not valid");
                            }
                            break;
                    }
                }

            }
        }

        if (empty($this->_errors)) {
            $this->_passed = TRUE;
        }

        return $this;
    }

    // add an error to the errors array
    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
}