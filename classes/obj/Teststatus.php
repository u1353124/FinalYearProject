<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Test status data object
 *
**/

class Teststatus {
	private $_db;
	private $_data;

	//Constructor for Test status Object
	public function __construct($status = null) {
		$this->_db = DB::getInstance();

		if(!$status) {

		} else {
			//Get a specified status details
			$this->find($status);
		}
	}

	//Function to find a status by id or name
	public function find($status = null) {
		if($status) {
			$field = (is_numeric($status)) ? 'id' : 'name';
			$data = $this->_db->get('teststatus', array($field, '=', $status));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Return all test status values
	public function findAll() {
		$data = DB::getInstance()->query("SELECT * FROM teststatus");
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