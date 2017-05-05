<?php

/**
 * Mark Greenbank - U1353124
 *
 * Cookies use to remember choices users make, such as staying logged in
 *
**/

class Cookie {

	//Function to check if a cookie exists
	public static function exists($name) {
		return (isset($_COOKIE[$name])) ? true : false;
	}

	//Function to retrieve a cookie
	public static function get($name) {
		return $_COOKIE[$name];
	}

	//function to create a cookie. Time is measured in seconds
	public static function put($name, $value, $expiry) {
		if($name=='' || $value=='') {
			return false;
		}

		if(setcookie($name, $value, time() + $expiry, '/')) {
			return true;
		}
		return false;
	}

	//Function to delete a cookie by setting it with a time 1 second before the current time
	public static function delete($name) {
		self::put($name,'', time() -1);
	}
}

?>