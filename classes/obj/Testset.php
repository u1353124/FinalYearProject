<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Testset data object
 *
**/

class Testset {
	private $_db;
	private $_data;

	//Constructor function
	public function __construct($testset = null) {
		$this->_db = DB::getInstance();

		//get testset
		if(!$testset) {
			//error
		} else {
			//Get the testset
			$this->find($testset);
		}
	}

	//Function to create the testset in the database
	public function create($fields = array()) {
		if(!$this->_db->insert('testset', $fields)) {
			throw new Exception('There was a problem creating the test set.');
		}
	}

	//Function to find the testset in the database by id
	public function find($testset = null) {
		if($testset) {
			$data = $this->_db->get('testset', array('id', '=', $testset));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Function to check if a testset object has been returned
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	//Function to retrieve object data
	public function data() {
		return $this->_data;
	}

	//Function to return all testsets linked to a provided versionid
	public function findAll($versionid) {
		$data = DB::getInstance()->query("SELECT * FROM testset WHERE versionid='$versionid'");
		return $data->results();
	}
}