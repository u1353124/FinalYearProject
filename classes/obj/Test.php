<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Testset data object
 *
**/

class Test {
	private $_db;
	private $_data;

	//Constructor function
	public function __construct($test = null) {
		$this->_db = DB::getInstance();

		//get version
		if(!$test) {
			//error
		} else {
			//Get the version
			$this->find($test);
		}
	}

	//Function to insert a test object into the database
	public function create($fields = array()) {
		if(!$this->_db->insert('test', $fields)) {
			throw new Exception('There was a problem creating the test.');
		}
	}

	//Function to find a test in the database by id
	public function find($test = null) {
		if($test) {
			$data = $this->_db->get('test', array('id', '=', $test));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Function to check if a test object was returned from the database
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	//Function to return the object data
	public function data() {
		return $this->_data;
	}

	//Function to find all tests by testsetid
	public function findAll($testsetid) {
		$data = DB::getInstance()->query("SELECT * FROM test WHERE testsetid='$testsetid'");
		return $data->results();
	}
}