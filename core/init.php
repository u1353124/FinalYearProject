<?php

/**
 * Mark Greenbank - U1353124
 *
 * Initialisation called on every page 
 *
**/

/**
 * Start sessions for user logins
**/
session_start();

/**
 * Create global configurations
**/

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '',
		'username' => '',
		'password' => '',
		'db' => ''
		),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800 // 7 Days in seconds
		),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
		),
	'version' => array(
		'version_number' => '0.1'
		),
	'hash' => array(
		'strength' => 12
		),
	'company' => array(
		'name' => 'Project - U1353124'
		)
	);

//Debugging config
$GLOBALS['debug'] = false;
$GLOBALS['logfile'] = $_SERVER['DOCUMENT_ROOT'] . "/project.log";

if(!file_exists($GLOBALS['logfile'])) {
	fopen($GLOBALS['logfile'], "w");
	fclose($GLOBALS['logfile']);
}

/**
 * Auto load classes
**/

spl_autoload_register(function($class) {

	if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/classes/util/' . $class . '.php')) { // Check util directory for class
		require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/util/' . $class . '.php';

	} elseif(file_exists($_SERVER['DOCUMENT_ROOT'] . '/classes/obj/' . $class . '.php')) { // Check obj directory for class
		require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/obj/' . $class . '.php';
	}
});

require_once $_SERVER['DOCUMENT_ROOT'] . '/func/sanitise.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/func/debugwrite.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/func/formaterrors.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));

	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->userid);
		$user->login();
	}

}

?>