<?php

/**
 * Mark Greenbank - U1353124
 *
 * Page to view account settings and change user details
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

?>
			<div class='row' style='margin-top: 100px;'>
				<div class='section'>
					<?php
						if(Session::exists('account')) {
							echo '<p class="flashmessage">' . Session::flash('account') . '</p>';
						}
					?>

					<ul class='ulist'>

					<?php
						if($user->hasPermission('admin')) {
					?>
						<li class='ulistitem'>
							<span>You have administrator privilleges.</span>
						</li>
					<?php
						}
						if($user->hasPermission('moderator')) {
					?>
						<li class='ulistitem'>
							<span>You have moderator privilleges.</span>
						</li>
					<?php
						}
					?>
						<li class='ulistitem'>
							<span><a href='update.php'>Click here to update details.</a></span>
						</li>
						<li class='ulistitem'>
							<span><a href='changepassword.php'>Click here to update password.</a></span>
						</li>
					</ul>

				</div>
			</div>



<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>