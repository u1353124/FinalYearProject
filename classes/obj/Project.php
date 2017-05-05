<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold Project data object
 *
**/

class Project {
	private $_db;
	private $_data;

	// Constructor function
	public function __construct($project = null) {
		$this->_db = DB::getInstance();

		//get project
		if(!$project) {
			//error
		} else {
			//Get the project
			$this->find($project);
		}
	}

	// Function to create a project
	public function create($fields = array()) {
		if(!$this->_db->insert('project', $fields)) {
			throw new Exception('There was a problem creating the project.');
		}
	}

	// Function to find a project by the project id
	public function find($project = null) {
		if($project) {
			$data = $this->_db->get('project', array('id', '=', $project));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Function to check if a project object was successfully found
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	//Function to return the queried data
	public function data() {
		return $this->_data;
	}

	//Function to find all projects in the database
	public function findAll() {
		$data = DB::getInstance()->query("SELECT * FROM project");
		return $data->results();
	}
}