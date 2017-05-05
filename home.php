<?php

/**
 * Mark Greenbank - U1353124
 *
 * Home page, displays basic user information
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
} else {

?>

			<div class='row' style='margin-top: 100px;'>
				<div class='section'>

					<?php
						if(Session::exists('home')) {
							echo '<p class="flashmessage">' . Session::flash('home') . '</p>';
						}
					?>

					<a href='profile.php?id=<?php echo escape($user->data()->id); ?>'><?php echo escape($user->data()->firstname); echo ' ' . escape($user->data()->lastname); ?></a>

<?php

	if($user->hasPermission('admin')) {
		echo '<p> You are an admin</p>';
	}	

	if($user->hasPermission('moderator')) {
		echo '<p> You are a moderator</p>';
	}

?>
					</div>
			</div>
<?php

}

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>