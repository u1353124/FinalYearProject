<?php

/**
 * Mark Greenbank - U1353124
 *
 * Escape and secure user inputs
 *
**/

function escape($string) {
	// ENT_QUOTES : Escape single and double quotes
	// UTF-8 : Define the character encoding
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

?>