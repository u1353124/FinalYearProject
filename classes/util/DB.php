<?php

/**
 * Mark Greenbank - U1353124
 *
 * Wrapper class for other classes to query the database
 * Uses the singleton design pattern
 * 
**/

class DB {

	private static $_instance = null; //Holds the DB instance
	private $_pdo; //Holds PDO object
	private $_query; //Holds the query to be executed
	private $_error = false;
	private $_results; //Holds queried results
	private $_count = 0; //Holds the count of results

	//Constructor function
	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),Config::get('mysql/username'), Config::get('mysql/password'));
		} catch (PDOException $e) {
			if($GLOBALS['debug']) {

			}
			die($e->getMessage());
		}
	}

	//Function to return the DB instance or create a new one
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	//Function to query the database and set the result and count fields
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$i = 1;
			if(count($params)) {
				//Bind any parameters to the query
				foreach($params as $param) {
					$this->_query->bindValue($i, $param);
					$i++;
				}
			}

			//If the query executes successfully
			//Set the result and count fields
			//otherwise set the error
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
		return $this;
	}

	// Function to build the sql query
	private function action($action, $table, $where = array()) {
		if(count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=');
			$field = $where[0];
			$operator =$where[1];
			$value = $where[2];

			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}

	// Function to retrieve data from a database table
	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	// Function to delete from a database table
	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	//Function to insert data into the database
	public function insert($table, $fields = array()) {
		if(count($fields)) {
			//Define the keys of the fields
			$keys = array_keys($fields);
			$values = null;
			$i = 1;

			//Add a ? to the values for binding later
			foreach($fields as $field) {
				$values .= '?';
				if($i < count($fields)) {
					$values .= ', ';
				}
				$i++;
			}

			// Create Insert SQL in the format INSERT INTO table (`field`,`anotherfield`,`andanother`) VALUES(?, ?, ?);
			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys). "`) VALUES ({$values})";

			if(!$this->query($sql, $fields)->error()) {
				return true;
			}
		}
		return false;
	}

	//Function to update data in the database
	public function update($table, $id, $fields) {
		$set = '';
		$i = 1;

		//Add a ? to the values for binding later
		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($i < count($fields)) {
				$set .= ', ';
			}
			$i++;
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id = ${id}";

		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}

	// Return results of a query
	public function results() {
		return $this->_results;
	}

	// Uses the results function and returns only the first row
	public function first() {
		return $this->results()[0];
	}

	// Function to return any errors
	public function error() {
		return $this->_error;
	}

	// Function to return the number of rows returned from the query
	public function count() {
		return $this->_count;
	}

}

?>