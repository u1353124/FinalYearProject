<?php

/**
 * Mark Greenbank - U1353124
 *
 * Allows the user to logout
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

$user = new User();
$user->logout();

Session::flash('index','You have successfully logged out.');
Redirect::to('index.php');

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>