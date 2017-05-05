<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to hold User data object
 *
**/

class User {
	private $_db;
	private $_data;
	private $_sessionName;
	private $_cookieName;
	private $_isLoggedIn;

	//Constructor for User Object
	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		$this->_sesionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		//get user for the logged in user
		if(!$user) {
			if(Session::exists($this->_sessionName)) {
				$user = Session::get($this->_sessionName);

				//get user data
				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					//logout
				}
			}
		} else {
			//Get a specified users details
			$this->find($user);
		}

	}

	// Function to create a user account
	public function create($fields = array()) {
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating account.');
		}
	}

	// Function to log a user in
	public function login($email = null, $password = null, $remember = false) {

		$user = $this->find($email);

		//Check to see if email and password is not passed (in the case of cookie login)
		if(!$email && !$password && $this->exists()) {
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			//Otherwise normal login
			if($user) {
				if(password_verify($password, $this->data()->password)) {
					Session::put($this->_sessionName, $this->data()->id);
					//If the user requests to be remembered
					if($remember) {
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('users_session', array('userid', '=', $this->data()->id));

						//If no has has been found for the user
						if(!$hashCheck->count()) {
							$this->_db->insert('users_session', array(
								'userid' => $this->data()->id,
								'hash' => $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}

						//Create the cookie
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					return true;
				}
			}
		}
		return false;
	}

	//Function to get user permissions from the userlevels table
	public function hasPermission($key) {
		$group = $this->_db->get('userlevels', array('id', '=', $this->data()->userlevel));

		if($group->count()) {
			//Decode json into an array
			$permissions = json_decode($group->first()->permissions, true);

			if($permissions[$key] == true) {
				return true;
			}
		}
		return false;
	}

	//Update values for a user
	public function update($fields = array(), $id = null) {

		//If an ID is passed, then it will get that particular user, otherwise use the loggedin user
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->id;
		}

		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating');
		}
	}

	//Function to find a user by id or email
	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'email';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	//Return all users
	public function findAll() {
		$data = DB::getInstance()->query("SELECT * FROM users");
		return $data->results();
	}

	//Function to check if there is data stored
	public function exists() {
		return (!empty($this->_data)) ? true : false;
	}

	//Function to log the user out by deleting the session, local cookie and cookie has from the database
	public function logout() {
		$this->_db->delete('users_session', array('userid', '=', $this->data()->id));
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	//Function to return data
	public function data() {
		return $this->_data;
	}

	//Function to return isLoggedIn
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
}

?>