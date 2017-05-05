<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to generate tokens to prevent Cross site request forgery
 *
**/

class Token {

	//Function to generate a token
	public static function generate() {
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}

	//Function to check the token with the session
	public static function check($token) {
		$tokenName = Config::get('session/token_name');

		//Check whether the session exists and the token passed by the input form matches the session token
		if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
			Session::delete($tokenName);
			return true;
		}
		return false;
	}
}

?>