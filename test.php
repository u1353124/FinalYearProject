<?php

/**
 * Mark Greenbank - U1353124
 *
 * Test page, displays information of a test specified by id
 * Allows the user to create a new test run
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(!$id = Input::get('id')) {
	Session::flash('projects','This page does not exist.');
	Redirect::to('projects.php');
}

//Check if the project exists
$test = new Test($id);
if(!$test->exists()) {
	Redirect::to(404);
} else {
	$testset = new Testset($test->data()->testsetid);
	$version = new Version($testset->data()->versionid);
	$project = new Project($testset->data()->projectid);
	$createdUser = new User($test->data()->createdby);
	$createdUser = $createdUser->data();
	$updatedUser = new User($test->data()->updatedby);
	$updatedUser = $updatedUser->data();
	$steps = json_decode($test->data()->steps, true);
	$testruns = Testrun::findAll($test->data()->id);
}

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

//Logic to handle the submitted form
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {

		$assignedUser = new User(Input::get('assignedto'));

		$testrun = new Testrun();
		$newTestPriority = new Testpriority();
		try {
			$testrun->create(array(
				'testid' => $test->data()->id,
				'actualoutcome' => '',
				'teststatusid' => 3,
				'testpriorityid' => $newTestPriority->find(Input::get('testpriority')),
				'assignedto' => $assignedUser->data()->id,
				'createdby' => $user->data()->id,
				'updatedby' => $user->data()->id,
				'createdat' => date('Y-m-d H:i:s'),
				'updatedat' => date('Y-m-d H:i:s')
				));
			Session::flash('test','Test run created!');
			Redirect::to('test.php?id=' . $test->data()->id);

		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
}

?>
			<div class='row' style='margin-top: 100px;'>
				<div class='section'>
					<?php
						if(Session::exists('test')) {
							echo '<p class="flashmessage">' . Session::flash('test') . '</p>';
						}
					?>
					<form class='form-body form-view'>
					<h2><a href='../project.php?id=<?php echo $project->data()->id; ?>'><?php echo $project->data()->name; ?></a> -> <a href='../version.php?id=<?php echo $version->data()->id; ?>'><?php echo $version->data()->name; ?></a> -> <a href='../testset.php?id=<?php echo $version->data()->id; ?>'><?php echo $testset->data()->name; ?></a> -> <?php echo $test->data()->name; ?></h2>
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
						<h2>Run test</h2>
						<div class='form-section'>
							<label for='testpriority'> Test Priority: </label><select class='form-input form-select' name='testpriority' id='testpriority'>
								<?php
									$priorities = Testpriority::findAll();
									foreach($priorities as $priority) {
										echo '<option>' . $priority->name . '</option>';
									}
								?>
							</select>
						</div>
						<div class='form-section'>
							<label for='assignedto'> Assigned To: </label><select class='form-input form-select' name='assignedto' id='assignedto'>
								<?php
									$userlist = User::findAll();
									foreach($userlist as $useroption) {
										echo '<option>' . $useroption->email . '</option>';
									}
								?>
							</select>
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Run Test</button>
						</div>
					</form>

					</br>
					<form class='form-body form-view'>
						<h2>Test runs</h2>
						<ul class='ulist'>
						<?php
							if(empty($testruns)) {
								echo '<li class="ulistitem">';
								echo '<span>There are current no tests in this testset.</span>';
								echo '</li>';
							}
							foreach($testruns as $run) {
								$priority = new Testpriority($run->testpriorityid);
								$status = new Teststatus($run->teststatusid);
								echo '<li class="ulistitem">';
								echo '<span class="ulistitemlabel"><a href="../testrun.php?id=' . $run->id . '">' . $test->data()->name . ' (#' . $run->id . ')</a> -- Priority: ' . $priority->data()->name . ' -- Status: '. $status->data()->name . ' </span>';
								echo '</li>';
							}
						?>
						</ul>
					</form>

				</div>
			</div>



<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>