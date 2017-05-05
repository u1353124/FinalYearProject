<?php

/**
 * Mark Greenbank - U1353124
 *
 * Version page, Displays details of the version by id
 * Allows the user to create a testset
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(!$id = Input::get('id')) {
	Session::flash('projects','This page does not exist.');
	Redirect::to('projects.php');
}

//Check if the project exists
$version = new Version($id);
if(!$version->exists()) {
	Redirect::to(404);
} else {
	$project = new Project($version->data()->projectid);
	$createdUser = new User($version->data()->createdby);
	$createdUser = $createdUser->data();
	$updatedUser = new User($version->data()->updatedby);
	$updatedUser = $updatedUser->data();
	$testsets = Testset::findAll($version->data()->id);
}

// Check if the user is logged in
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
				'unique' => 'testset'
				),
			'description' => array(
				'required' => true,
				'min' => 2,
				'max' => 65535
				),
			'assignedto' => array(
				'required' => true
				)
			));

		$assignedUser = new User(Input::get('assignedto'));
		if(!$assignedUser->exists()) {
			Session::flash('version','The user id does not exist!');
			Redirect::to('version.php?id=' . $version->data()->id);
		}

		if($validation->passed()) {

			$testset = new Testset();
			try {
				$testset->create(array(
					'projectid' => $project->data()->id,
					'versionid' => $version->data()->id,
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'assignedto' => $assignedUser->data()->id,
					'createdby' => $user->data()->id,
					'updatedby' => $user->data()->id,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));

				Session::flash('version','Test set Created!');
				Redirect::to('version.php?id=' . $version->data()->id);

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
						if(Session::exists('version')) {
							echo '<p class="flashmessage">' . Session::flash('version') . '</p>';
						}
					?>
					<form class='form-body form-view'>
					<h2><a href='../project.php?id=<?php echo $project->data()->id; ?>'><?php echo $project->data()->name; ?></a> -> <?php echo $version->data()->name; ?></h2>
					<ul class='ulist'>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Description: </span><span><?php echo $version->data()->description; ?></span>
						</li>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Test sets: </span><span>
							<?php
								$i = 1;
								foreach($testsets as $set) {
									echo '<a href="../testset.php?id=' . $set->id . '">' . $set->name. '</a>';
									if($i < count($testsets)) {
										echo ' - ';
									}
									$i++;
								}
							?>
							</span>
						</li>
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
						<h2>Create new test set</h2>
						<div class='form-section'>
							<label for='name'> Name: </label><input class='form-input' type='text' name='name' id='name' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='description'> Description: </label><input class='form-input' type='text' name='description' id='description' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='assignedto'> Assigned to: </label><input class='form-input' type='text' name='assignedto' id='assignedto' autocomplete='off' required />
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Create Test set</button>
						</div>
					</form>
				</div>
			</div>



<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>