<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to parse data from inputs
 *
**/

class Input {
	//Check to see if input has been provided
	public static function exists($type = 'post') {
		switch($type) {
			case 'post':
				return (!empty($_POST)) ? true : false;
			break;
			case 'get':
				return (!empty($_GET)) ? true : false;
			break;
			default:
				return false;
			break;
		}
	}

	//Return the values that were input
	//If nothing was input return an empty string
	public static function get($item) {
		if(isset($_POST[$item])) {
			return $_POST[$item];
		} else if (isset($_GET[$item])) {
			return $_GET[$item];
		}
		return '';
	}

}

?>