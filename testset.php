<?php

/**
 * Mark Greenbank - U1353124
 *
 * Test set page, displays testset information
 * Allows the user to create a new test
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(!$id = Input::get('id')) {
	Session::flash('projects','This page does not exist.');
	Redirect::to('projects.php');
}

//Check if the project exists
$testset = new Testset($id);
if(!$testset->exists()) {
	Redirect::to(404);
} else {
	$version = new Version($testset->data()->versionid);
	$project = new Project($testset->data()->projectid);
	$createdUser = new User($testset->data()->createdby);
	$createdUser = $createdUser->data();
	$updatedUser = new User($testset->data()->updatedby);
	$updatedUser = $updatedUser->data();
	$tests = Test::findAll($testset->data()->id);
}

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

//Logic to handle the submitted form
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50,
				'unique' => 'test'
				),
			'description' => array(
				'required' => true,
				'min' => 2,
				'max' => 65535
				),
			'steps' => array(
				'required' => true,
				'max' => 65535
				),
			'expectedoutcome' => array(
				'required' => true,
				'max' => 65535
				)
			));

		if($validation->passed()) {
			//convert steps into an array

			$steps = explode("\n", str_replace("\r", "", Input::get('steps')));
			$json = json_encode($steps, JSON_FORCE_OBJECT);

			$test = new Test();
			try {
				$test->create(array(
					'name' => Input::get('name'),
					'testsetid' => $testset->data()->id,
					'description' => Input::get('description'),
					'steps' => $json,
					'expectedoutcome' => Input::get('expectedoutcome'),
					'createdby' => $user->data()->id,
					'updatedby' => $user->data()->id,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));
				Session::flash('testset','Test Created!');
				Redirect::to('testset.php?id=' . $testset->data()->id);

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
						if(Session::exists('testset')) {
							echo '<p class="flashmessage">' . Session::flash('testset') . '</p>';
						}
					?>
					<form class='form-body form-view'>
					<h2><a href='../project.php?id=<?php echo $project->data()->id; ?>'><?php echo $project->data()->name; ?></a> -> <a href='../version.php?id=<?php echo $version->data()->id; ?>'><?php echo $version->data()->name; ?></a> -> <?php echo $testset->data()->name; ?></h2>
					<ul class='ulist'>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Description: </span><span><?php echo $testset->data()->description; ?></span>
						</li>
						<?php
							if(empty($tests)) {
								echo '<li class="ulistitem">';
								echo '<span>There are current no tests in this testset.</span>';
								echo '</li>';
							}
							foreach($tests as $test) {
								echo '<li class="ulistitem">';
								echo '<span class="ulistitemlabel"><a href="../test.php?id=' . $test->id . '">' . $test->name . ' (#' . $test->id . ')</a></span>';
								echo '</li>';
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
						<h2>Create new test</h2>
						<div class='form-section'>
							<label for='name'> Name: </label><input class='form-input' type='text' name='name' id='name' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='description'> Description: </label><input class='form-input' type='text' name='description' id='description' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='steps'> Steps: </label><textarea class='form-input form-text' rows='4' name='steps' id='steps' autocomplete='off' required /></textarea>
						</div>
						<div class='form-section'>
							<label for='expectedoutcome'> Expected Outcome: </label><input class='form-input' type='text' name='expectedoutcome' id='expectedoutcome' autocomplete='off' required />
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Create Test</button>
						</div>
					</form>
				</div>
			</div>



<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>