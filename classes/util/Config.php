<?php

/**
 * Mark Greenbank - U1353124
 *
 * Config utility to parse config paths (taken from /core/init.php) and return the value
**/

class Config {
	public static function get($path = null) {
		if($path) {
			$config = $GLOBALS['config'];
			$path = explode('/', $path);

			if(count($path) == 1) {
				if($GLOBALS['debug']) {
					$variables = array($config, $path);
					writeToLog($variables, "Config path has an incorrect path.", $_SERVER['PHP_SELF']);
				}
				return null;
			}

			//Loop through path parts of the global config found in /core/init.php
			foreach($path as $pathpart) {
				//Check if the values exists in the config
				if(isset($config[$pathpart])) {
					$config = $config[$pathpart];
				} else {
					if($GLOBALS['debug']) {
						$variables = array($config, $path);
						writeToLog($variables, "Parts of the path given do not exist.", $_SERVER['PHP_SELF']);
					}
					return null;
				}
			}
			return $config;
		}
		return null;
	}
}

?>