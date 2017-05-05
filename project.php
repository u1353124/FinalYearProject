<?php

/**
 * Mark Greenbank - U1353124
 *
 * Displays details of a project found by id
 * Allows the user to create a version of a project
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(!$id = Input::get('id')) {
	Session::flash('projects','Page does not exist.');
	Redirect::to('projects.php');
}

//Check if the project exists
$project = new Project($id);
if(!$project->exists()) {
	Redirect::to(404);
} else {
	$createdUser = new User($project->data()->createdby);
	$createdUser = $createdUser->data();
	$updatedUser = new User($project->data()->updatedby);
	$updatedUser = $updatedUser->data();
	$versions = Version::findAll($project->data()->id);
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
				),
			'description' => array(
				'required' => true,
				'min' => 2,
				'max' => 65535
				)
			));

		if($validation->passed()) {

			$version = new Version();

			try {
				$version->create(array(
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'projectid' => $project->data()->id,
					'createdby' => $user->data()->id,
					'updatedby' => $user->data()->id,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));

				Session::flash('project','Version Created!');
				Redirect::to('project.php?id=' . $project->data()->id);

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
						if(Session::exists('project')) {
							echo '<p class="flashmessage">' . Session::flash('project') . '</p>';
						}
					?>
					<form class='form-body form-view'>
					<h2><?php echo $project->data()->name; ?></h2>
					<ul class='ulist'>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Description: </span><span><?php echo $project->data()->description; ?></span>
						</li>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Date: </span><span><?php echo date_format(new DateTime($project->data()->startdate), 'Y-m-d'); ?> - <?php echo date_format(new DateTime($project->data()->enddate), 'Y-m-d'); ?></span>
						</li>
						<li class='ulistitem'>
							<span class='ulistitemlabel'>Versions: </span><span>
							<?php
								$i = 1;
								foreach($versions as $version) {
									echo '<a href="../version.php?id=' . $version->id . '">' . $version->name. '</a>';
									if($i < count($versions)) {
										echo ' - ';
									}
									$i++;
								}
							?>
							</span>
						</li>
						<li class='ulistitem'>
							<span >Created by <?php echo $createdUser->firstname . ' ' . $createdUser->lastname; ?> on <?php echo date_format(new DateTime($project->data()->createdat), 'Y-m-d'); ?></span>
						</li>
						<li class='ulistitem'>
							<span >Last updated by <?php echo $createdUser->firstname . ' ' . $createdUser->lastname; ?> on <?php echo date_format(new DateTime($project->data()->updatedat), 'Y-m-d'); ?></span>
						</li>
					</ul>
					</form>
					</br>
					<form class='form-body' action='' method='post'>
						<h2>Create new version</h2>
						<div class='form-section'>
							<label for='name'> Name: </label><input class='form-input' type='text' name='name' id='name' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='description'> Description: </label><input class='form-input' type='text' name='description' id='description' autocomplete='off' required />
						</div>

						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Create Version</button>
						</div>
					</form>
				</div>
			</div>



<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>