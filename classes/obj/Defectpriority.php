<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Defect Priority data object
 *
**/

class Defectpriority {
	private $_db;
	private $_data;

	//Constructor for Defect Priority Object
	public function __construct($priority = null) {
		$this->_db = DB::getInstance();

		if(!$priority) {

		} else {
			//Get a specified defect priority details
			$this->find($priority);
		}
	}

	//Function to find a priority by id or name
	public function find($priority = null) {
		if($priority) {
			$field = (is_numeric($priority)) ? 'id' : 'name';
			$data = $this->_db->get('defectpriority', array($field, '=', $priority));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Return all defect priority values
	public function findAll() {
		$data = DB::getInstance()->query("SELECT * FROM defectpriority");
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