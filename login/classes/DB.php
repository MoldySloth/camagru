<?php

// database wrapper working with PDO database objects to connect to a mysql database
// abstracted way to work with the DB
// using a get instance static method... so that we dont need to connect to the db every time whe want to use it. If you were using a constructor method you would have to reconnect every time

class DB {
    private static $_instance = null;
    private $_pdo;  //store pdo object to use elsewhere
    private $_query;   //is the last query that is executed
    private $_error = FALSE;  //represents whether there is an error or not aka if the query failed
    private $_results;  //sore our results set
    private $_count = 0;  //the count or results aka are there any results returned?
    private function __construct() {
        try {
            $this->_pdo = new PDO(Config::get('mysql/dsn'), Config::get('mysql/user'), Config::get('mysql/pass'), Config::get('mysql/opt')); 
        }   catch(PODExeception $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        //if using twice on a page it will just return the set instance
        return self::$_instance;
    }

    // generic query method that takes two arguments, the query string and an array or parammeters to include as binded values in PDO
    // built the functionality to query a database by binding, aka to remove the possiblity to injections, native to PDO but we have abstracted it
    public function query($sql, $params = array()) {
        //rest back to false because we perform multiple queries
        $this->_error = FALSE;
        //performing an assignment to a variable and then checking it in an if statement
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            //check if the param exists
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            
            //execute the query anyway
            if ($this->_query->execute()) {
                //working with an object and not an array, aka the values in the columns to represent as an object 
                $this->_results = $this->_query->fetchALL(PDO::FETCH_OBJ);
                //methods on PDO
                $this->_count = $this->_query->rowCount();
            } else {
                //set the error
                $this->_error = TRUE;
            }
        }

        return $this;

    }

    // method to perform a specific action, select, delete... define a table and then a specific field
    public function action($action, $table, $where = array()) {
        if (count($where) === 3) {
            //define the operators
            $operators = array('=', '>', '<', '>=', '<='); 

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            //if operator is valid the construct the query
            if (in_array($operator, $operators)) {  
                // bind on the value in the query
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return FALSE;

    }

    public function get($table, $where) {
        return $this->action('SELECT *',$table, $where);
    }

    public function delete($table, $where) {
        return $this->action('DELETE',$table, $where);
    }

    public function insert($table, $fields = array()) {
        $keys = array_keys($fields);
        $values = '';
        $x = 1;

        foreach ($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

        if (!$this->query($sql, $fields)->error()) {
            return TRUE;
        }
        return FALSE;
    }

    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= $name . ' = ?';
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return TRUE;
        }

        return FALSE;
    }

    public function results() {
        return $this->_results;
    }

    public function first() {
        return $this->results()[0];
    }

    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }
}