<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Testrun data object
 *
**/

class Testrun {
	private $_db;
	private $_data;

	//Constructor function
	public function __construct($testrun = null) {
		$this->_db = DB::getInstance();

		//get testrun
		if(!$testrun) {
			//error
		} else {
			//Get the testrun
			$this->find($testrun);
		}
	}

	//Function to create the testrun in the database
	public function create($fields = array()) {
		if(!$this->_db->insert('testrun', $fields)) {
			throw new Exception('There was a problem creating the testrun.');
		}
	}

	//Update values for a Testrun
	public function update($fields = array(), $id) {
		if(!$this->_db->update('testrun', $id, $fields)) {
			throw new Exception('There was a problem updating');
		}
	}

	//Function to find the testrun by id
	public function find($testrun = null) {
		if($testrun) {
			$data = $this->_db->get('testrun', array('id', '=', $testrun));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Function to check if a testrun object was returned
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	//Function to retrieve object data
	public function data() {
		return $this->_data;
	}

	//Function to find all testruns, and all testruns by testid
	public function findAll($testid = null) {
		if($testid) {
			$data = DB::getInstance()->query("SELECT * FROM testrun WHERE testid='$testid'");
		} else {
			$data = DB::getInstance()->query("SELECT * FROM testrun");
		}
		return $data->results();
	}
}