<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Defect data object
 *
**/

class Defect {
	private $_db;
	private $_data;

	//Contructor function
	public function __construct($defect = null) {
		$this->_db = DB::getInstance();

		//get defect
		if(!$defect) {
			//error
		} else {
			//Get the defect
			$this->find($defect);
		}
	}

	// Function to insert defect object into database
	public function create($fields = array()) {
		if(!$this->_db->insert('defect', $fields)) {
			throw new Exception('There was a problem creating the defect.');
		}
	}

	// Function to find defect by either id or name
	// Defect name must be unique
	public function find($defect = null) {
		if($defect) {
			$field = (is_numeric($defect)) ? 'id' : 'name';
			$data = $this->_db->get('defect', array($field, '=', $defect));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	// Function to check if a defect has been found
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	// Function to return the data of a found defect
	public function data() {
		return $this->_data;
	}

	// Function to return all defects
	public function findAll() {
		$data = DB::getInstance()->query("SELECT * FROM defect");
		return $data->results();
	}
}