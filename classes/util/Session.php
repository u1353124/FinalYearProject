<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to manage sessions
 *
**/

class Session {

	//Function to create a session
	public static function put($name, $value) {
		return $_SESSION[$name] = $value;

	}

	//Check if the session exists
	public static function exists($name) {
		return (isset($_SESSION[$name])) ? true : false;
	}

	//Delete a session
	public static function delete($name) {
		if(self::exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	//Retrieve a session value
	public static function get($name) {
		return $_SESSION[$name];
	}

	//Flash messages to the user
	public static function flash($name, $string ='') {
		if(self::exists($name)) {
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			self::put($name, $string);
		}
	}
}

?>