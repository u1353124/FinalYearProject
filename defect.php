<?php

/**
 * Mark Greenbank - U1353124
 *
 * Page to view a single defect
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(!$id = Input::get('id')) {
	Session::flash('defects','This page does not exist.');
	Redirect::to('viewdefects.php');
}

//Check if the project exists
$defect = new Defect($id);
if(!$defect->exists()) {
	Redirect::to(404);
} else {
	$createdUser = new User($defect->data()->createdby);
	$createdUser = $createdUser->data();
	$updatedUser = new User($defect->data()->updatedby);
	$updatedUser = $updatedUser->data();
	$steps = json_decode($defect->data()->stepstoreproduce, true);
	$priority = new Defectpriority($defect->data()->defectpriorityid);
	$status = new Defectstatus($defect->data()->defectstatusid);
	$assignedUser = new User($defect->data()->assignedto);
}

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

?>
			<div class='row' style='margin-top: 100px;'>
				<div class='section'>
					<?php
						if(Session::exists('testset')) {
							echo '<p class="flashmessage">' . Session::flash('testset') . '</p>';
						}
					?>
					<form class='form-body form-view'>
					<h2><?php echo escape($defect->data()->name); ?></h2>
					<ul class='ulist'>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Description: </span><span><?php echo escape($defect->data()->description); ?></span>
						</li>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Assigned to: </span><span><?php echo escape($assignedUser->data()->email); ?></span>
						</li>
						<?php
							$i = 1;
							foreach($steps as $step) {
								echo '<li class="ulistitem">';
								echo '<span class="ulistitemlabel">Step ' . $i  . ':</span><span> '. $step . '</span>';
								echo '</li>';
								$i++;
							}
						?>
						<li class='ulistitem'>
							<span class='ulistitemlabel'> Status: </span><span><?php echo escape($status->data()->name);?></span>
						</li>
						<li class='ulistitem'>
							<span class='ulistitemlabel'> Priority: </span><span><?php echo escape($priority->data()->name);?></span>
						</li>
						<li class='ulistitem'>
							<span >Created by <?php echo escape($createdUser->firstname) . ' ' . escape($createdUser->lastname); ?> on <?php echo date_format(new DateTime($defect->data()->createdat), 'Y-m-d'); ?></span>
						</li>
						<li class='ulistitem'>
							<span >Last updated by <?php echo escape($updatedUser->firstname) . ' ' . escape($updatedUser->lastname); ?> on <?php echo date_format(new DateTime($defect->data()->updatedat), 'Y-m-d'); ?></span>
						</li>
					</ul>
					</form>
				</div>
			</div>



<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>