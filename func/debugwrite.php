<?php

/**
 * Mark Greenbank - U1353124
 *
 * Function to write debugging data into the debug file
**/

//Requires an Array of variables, a message string and a file path
function writeToLog($variables, $message, $filepath) {
	//open the file for appending
	$logfile = fopen($GLOBALS['logfile'], "a") or die("Unable to open file!");
	fwrite($logfile, "------==========------\n");
	fwrite($logfile, "Variables: \n");
	foreach($variables as $variable) {
		fwrite($logfile, var_export($variable, true) . "\n\n");
	}
	fwrite($logfile, "Error Message: " . $message . "\n");
	fwrite($logfile, "File Path: " . $_SERVER['DOCUMENT_ROOT'] . $filepath ."\n");
	fwrite($logfile, "------==========------\n\n");
	fclose($logfile);
}

?>