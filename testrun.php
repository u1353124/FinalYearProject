<?php

/**
 * Mark Greenbank - U1353124
 *
 * Testrun page, displays details of a testrun
 * Allows the user to edit the test run
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(!$id = Input::get('id')) {
	Session::flash('projects','This page does not exist.');
	Redirect::to('projects.php');
}

//Check if the project exists
$testrun = new Testrun($id);
if(!$testset->exists()) {
	Redirect::to(404);
} else {
	$test = new Test($testrun->data()->testid);
	$testset = new Testset($test->data()->testsetid);
	$version = new Version($testset->data()->versionid);
	$project = new Project($testset->data()->projectid);
	$createdUser = new User($test->data()->createdby);
	$createdUser = $createdUser->data();
	$updatedUser = new User($test->data()->updatedby);
	$updatedUser = $updatedUser->data();
	$steps = json_decode($test->data()->steps, true);
	$testruns = Testrun::findAll($test->data()->id);
	$linkeddefects = Defectlink::findAll($testrun->data()->id);
}

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

//Logic to handle the submitted form
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
			//convert steps into an array
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'actualoutcome' => array(
				'max' => 65535
				)
			));

		if($validation->passed()) {

			$newAssignedUser = new User(Input::get('assignedto'));

			$statuses = Teststatus::findAll();
			foreach($statuses as $status) {
				if($status->name == Input::get('teststatus')){
					$newTestStatus = $status->id;
				}
			}

			$priorities = Testpriority::findAll();
			foreach($priorities as $priority) {
				if($priority->name == Input::get('testpriority')){
					$newTestPriority = $priority->id;
				}
			}

			try {
				
				$testrun->update(array(
					'actualoutcome' => Input::get('actualoutcome'),
					'teststatusid' => $newTestStatus,
					'testpriorityid' => $newTestPriority,
					'assignedto' => $newAssignedUser->data()->id,
					'updatedby' => $user->data()->id,
					'updatedat' => date('Y-m-d H:i:s')
					), $testrun->data()->id);

				Session::flash('test','Test run saved!');
				Redirect::to('test.php?id=' . $test->data()->id);

			} catch (Exception $e) {
				die($e->getMessage());
			}
		} else {
			formatErrors($validation->errors());
		}
	}
}

?>
			<div class='row' style='margin-top: 100px;'>
				<div class='section'>
					<?php
						if(Session::exists('testrun')) {
							echo '<p class="flashmessage">' . Session::flash('testrun') . '</p>';
						}
					?>
					<form class='form-body form-view'>
					<h2><a href='../project.php?id=<?php echo $project->data()->id; ?>'><?php echo $project->data()->name; ?></a> -> <a href='../version.php?id=<?php echo $version->data()->id; ?>'><?php echo $version->data()->name; ?></a> -> <a href='../testset.php?id=<?php echo $version->data()->id; ?>'><?php echo $testset->data()->name; ?></a> -> <a href='../test.php?id=<?php echo $test->data()->id; ?>'><?php echo $test->data()->name; ?></a></h2>
					<ul class='ulist'>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Description: </span><span><?php echo $test->data()->description; ?></span>
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
							<span >Created by <?php echo $createdUser->firstname . ' ' . $createdUser->lastname; ?> on <?php echo date_format(new DateTime($version->data()->createdat), 'Y-m-d'); ?></span>
						</li>
						<li class='ulistitem'>
							<span >Last updated by <?php echo $updatedUser->firstname . ' ' . $updatedUser->lastname; ?> on <?php echo date_format(new DateTime($version->data()->updatedat), 'Y-m-d'); ?></span>
						</li>
					</ul>
					</form>
					</br>
					<form class='form-body' action='' method='post'>
						<h2>Test run information</h2>
						<div class='form-section'>
							<label for='actualoutcome'> Actual outcome: </label><textarea class='form-input form-text' rows='4' name='actualoutcome' id='actualoutcome' autocomplete='off' required /><?php echo $testrun->data()->actualoutcome;?></textarea>
						</div>
						<div class='form-section'>
							<label for='teststatus'> Test Status: </label><select class='form-input form-select' name='teststatus' id='teststatus'>
								<?php
									$statuses = Teststatus::findAll();
									foreach($statuses as $status) {
										if($status->id == $testrun->data()->teststatusid){
											echo '<option selected>' . $status->name . '</option>';
										} else {
											echo '<option>' . $status->name . '</option>';
										}
									}
								?>
							</select>
						</div>
						<div class='form-section'>
							<label for='testpriority'> Test Priority: </label><select class='form-input form-select' name='testpriority' id='testpriority'>
								<?php
									$priorities = Testpriority::findAll();
									foreach($priorities as $priority) {
										if($priority->id == $testrun->data()->testpriorityid){
											echo '<option selected>' . $priority->name . '</option>';
										} else {
											echo '<option>' . $priority->name . '</option>';
										}
									}
								?>
							</select>
						</div>
						<div class='form-section'>
							<label for='assignedto'> Assigned To: </label><select class='form-input form-select' name='assignedto' id='assignedto'>
								<?php
									$userlist = User::findAll();
									foreach($userlist as $useroption) {
										if($useroption->id == $testrun->data()->assignedto){
											echo '<option selected>' . $useroption->email . '</option>';
										} else {
											echo '<option>' . $useroption->email . '</option>';
										}
									}
								?>
							</select>
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Save Test Run</button>
						</div>
					</form>

					<br />

					<?php
					if(!empty($linkeddefects)) {
						
					?>
					<form class='form-body'>
						<h2>Linked Defects</h2>
						<ul class='ulist'>
						<?php
							$i = 1;
							foreach($linkeddefects as $defect) {
								$newDefect = new Defect($defect->defectid);
								echo '<li class="ulistitem">';
								echo '<span class="ulistitemlabel"><a href="defect.php?id=' . $newDefect->data()->id . '">' . $newDefect->data()->name . ':</a></span><span> '. $newDefect->data()->description . '</span>';
								echo '</li>';
								$i++;
							}
						?>
						</ul>
					</form>

					<?php } ?>

				</div>
			</div>
<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>