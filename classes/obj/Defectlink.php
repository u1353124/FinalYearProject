<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Defect link data object
 * This links defects to a test run
 *
**/

class Defectlink {
	private $_db;
	private $_data;

	//Constructor for Defect link Object
	public function __construct($value = null) {
		$this->_db = DB::getInstance();

		if(!$value) {

		} else {
			//Get a specified defect link details
			$this->find($value);
		}
	}

	// Function to create a defect link
	public function create($fields = array()) {
		if(!$this->_db->insert('defects', $fields)) {
			throw new Exception('There was a problem creating the defect link.');
		}
	}

	//Function to find a status by defectid, id or testrunid
	public function find($value = null, $field) {
		if($value) {
			$data = $this->_db->get('defects', array($field, '=', $value));
			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Return all defects links values
	public function findAll($testrunid = null) {
		if($testrunid) {
			$data = DB::getInstance()->query("SELECT * FROM defects WHERE testrunid='$testrunid'");
		} else {
			$data = DB::getInstance()->query("SELECT * FROM defects");
		}
		return $data->results();
	}

	//Function to check if there is data stored
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	//Function to return data
	public function data() {
		return $this->_data;
	}
}

?>