<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Version data object
 *
**/

class Version {
	private $_db;
	private $_data;

	//Constructor function
	public function __construct($version = null) {
		$this->_db = DB::getInstance();

		//get version
		if(!$version) {
			//error
		} else {
			//Get the version
			$this->find($version);
		}
	}

	// Function to create a version object in the database
	public function create($fields = array()) {
		if(!$this->_db->insert('version', $fields)) {
			throw new Exception('There was a problem creating the version.');
		}
	}

	// Function to find a version in the database by id
	public function find($version = null) {
		if($version) {
			$data = $this->_db->get('version', array('id', '=', $version));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	// Function to check if a version object has been returned
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	//Function to retrieve the version data from the object
	public function data() {
		return $this->_data;
	}

	//Function to find all versions associated with a projectid
	public function findAll($projectid) {
		$data = DB::getInstance()->query("SELECT * FROM version WHERE projectid='$projectid'");
		return $data->results();
	}
}