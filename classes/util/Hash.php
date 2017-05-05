<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to generate a secure hash using php's BCRYPT algorithm
 *
**/

class Hash {

	//create a hash using the bcrypt function
	public static function make($string) {
		$options = [
			'cost' => Config::get('hash/strength')
		];
		return password_hash($string, PASSWORD_BCRYPT, $options);
	}

	//generate a unique hash
	public static function unique() {
		return self::make(uniqid());
	}
}

?>