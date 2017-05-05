<?php

/**
 * Mark Greenbank - U1353124
 *
 * Displays a userprofile page based on a user id
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(Session::exists('profile')) {
	echo '<p class="flashmessage test-success">' . Session::flash('profile') . '</p>';
}

if(!$id = Input::get('id')) {
	Session::flash('index','Page does not exist.');
	Redirect::to('index.php');
} else {
	$user = new User($id);
	if(!$user->exists()) {
		Redirect::to(404);
	} else {
		$data = $user->data();
	}
	?>

	<h3><?php echo escape($data->firstname); ?></h3>
	<h3><?php echo escape($data->lastname); ?></h3>

	<?php
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>