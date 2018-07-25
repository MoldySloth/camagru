<?php
// allows the user to login, logout, perform actions
// use the DB class a lot to check user information and update info 

class User {
    private $_db;
    private $_data;
    private $_sessionName;
    private $_cookieName;
    private $_isLoggedIn;

    // get an instance of the db, can pass in a user... aka to grab a users information or a current user in the session
    public function __construct($user = null) {
        $this->_db = DB::getInstance();

        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        // check if the session exist and the user is logged in
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                // grabbing the current user data of the current user that is logged in
                if ($this->find($user)) {
                    // set a flag of is logged in
                    $this->_isLoggedIn = true;
                } else {
                    // process logout
                }
            }
        } else {
            // the user has been defined, also a users data of a user that isn't logged in
            $this->find($user);
        }
    }

    // update a users information
    public function update($fields = array(), $id = null) {
        // if the user is logged in and an id hasn't been found then grab the id. preventing an admins id from being grabbed
        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }

        if(!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating your profile.');
        }
    }

    // ability to create a user
    public function create($fields = array()) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem creating the account.');
        }
    }

    // can also use this method to find a user by their id
    public function find($user = null) {
        if($user) {
            // should use only alpha numeric for username... still to do**
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $user));

            // the data from the database using the database wrapper functionality, user has been found
            if ($data->count()) {
                // the data property will contain all of the users data
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    // permission for a user
    public function hasPermission($key) {
        $group = $this->_db->get('groups', array('id', '=', $this->data()->group));
        // check if user is in a group
        if($group->count()) {
            // extract the permissions as per the group
            // json decode function in php will return an object but we have decoded it into a php array
            $permissions = json_decode($group->first()->permissions, true);

            if($permissions[$key] == true) {
                return true;
            }
        }
        return false;
    }

    // logging in a user
    public function login($username = null, $password = null, $remember = false) {
        // check if the username and password hasn't been defined and if the user exists aka is the user already logged in
        if (!$username && !$password && $this->exists()) {
            // set a session for the user if user data exists
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            // check if user exists
            $user = $this->find($username);
            if ($user) {
                // check the password
                if($this->data()->password === Hash::make($password, $this->data()->salt)) {

                    $active = $this->data()->active;

                    // Check the user for validation
                    if ($active) {
                        // confirm active status and continue login
                        // store the users id in the session
                        Session::put($this->_sessionName, $this->data()->id);

                        // if the checkbox is ticked all of this will be run
                        if ($remember) {
                            // generate a hash, check that the hash doesn't already exist in the database and then insert the hash into the database
                            // the hash will be looked up every time the user enters the page (cookie set is stored on a computer)
                            $hash = Hash::unique();

                            // checking if we already have a hash stored in the database for that user
                            $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                            // if there is no hash... then insert (only want one record in the table per user)
                            if (!$hashCheck->count()) {
                                $this->_db->insert('users_session', array(
                                    'user_id' => $this->data()->id,
                                    'hash' => $hash
                                ));
                            } else {
                                // set the hash to the hash that is already in the database
                                $hash = $hashCheck->first()->hash;
                            }

                            // store a cookie with the cookie name from config file
                            Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                        }
                        return true;
                    } else {
                        // check session data for hash against confirm_account table in DB
                        $keyCheck = $this->_db->get('confirm_account', array('user_id', '=', $this->data()->id));

                        // If there is no user in DB then user is not registered
                        if (!$keyCheck->count()) {
                            Session::flash('home', 'Please register your account or check your emails for a validation request');
                            Redirect::to('index.php');
                        } else {

                            $key_hash = $keyCheck->first()->key_hash;

                            $url_hash = Input::get('hash_key');

                            // Compare hash in DB to current session hash in URL
                            if ($key_hash === $url_hash) {
                                // If the hashes match then activate the user
                                //update... using try so that we can throw an exception inside our user method if it fails
                                try {
                                    $this->_db->update('users', $this->data()->id, array(
                                        'active' => 1
                                    ));

                                    Session::put($this->_sessionName, $this->data()->id);

                                    // if the checkbox is ticked all of this will be run
                                    if ($remember) {
                                        // generate a hash, check that the hash doesn't already exist in the database and then insert the hash into the database
                                        // the hash will be looked up every time the user enters the page (cookie set is stored on a computer)
                                        $hash = Hash::unique();

                                        // checking if we already have a hash stored in the database for that user
                                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                                        // if there is no hash... then insert (only want one record in the table per user)
                                        if (!$hashCheck->count()) {
                                            $this->_db->insert('users_session', array(
                                                'user_id' => $this->data()->id,
                                                'hash' => $hash
                                            ));
                                        } else {
                                            // set the hash to the hash that is already in the database
                                            $hash = $hashCheck->first()->hash;
                                        }

                                        // store a cookie with the cookie name from config file
                                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                                        return true;
                                    }

                                    Session::flash('home', 'Your profile has been activated');
                                    Redirect::to('index.php');

                                } catch(Exception $e) {
                                    // getMessage method is part of the Exception object within php
                                    die($e->getMessage());
                                }
                                return true;
                            } else {
                                // if not valid then error and flash message to verify user email
                                Session::flash('home', 'Please check your emails for a validation request!');
                                Redirect::to('index.php');
                            }
                            return false;
                        }
                    }
                }
            }
        }
        return false;
    }

    // check if a user exists already
    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    // to logout a user
    public function logout() {
        //remove the current user session from the database if user has logged out
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data() {
        return $this->_data;
    }

    // getter of data for logged in status
    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }
}