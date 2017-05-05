<?php

/**
 * Mark Greenbank - U1353124
 *
 * Class to managing page redirects
 *
**/

class Redirect {
	public static function to($location = null) {
		if($location) {
			//If a numeric location is passed, then switch through available errors
			if(is_numeric($location)) {
				switch($location) {
					case 404:
						header('HTTP/1.0 404 Not Found');
						include $_SERVER['DOCUMENT_ROOT'] . '/inc/errors/404.php';
						exit();
					break;
				}
			}

			header('Location: ' . $location);
			exit();
		}
	}
}

?>