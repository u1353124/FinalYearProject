<?php

/**
 * Mark Greenbank - U1353124
 *
 * Displays all projects
 * Allows the user to create a new project
 *
**/
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

$projects = Project::findAll();

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50,
				'unique' => 'project'
				),
			'description' => array(
				'required' => true,
				'min' => 2,
				'max' => 65535
				)
			));

		if($validation->passed()) {

			$project = new Project();

			try {
				$project->create(array(
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'startdate' => date('Y-m-d'),
					'enddate' => date('Y-m-d'),
					'createdby' => $user->data()->id,
					'updatedby' => $user->data()->id,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));

				Session::flash('projects','Project Created!');
				Redirect::to('projects.php');

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
						if(Session::exists('projects')) {
							echo '<p class="flashmessage">' . Session::flash('projects') . '</p>';
						}
					?>
					<form class='form-body form-view'>
					<h2> Projects</h2>

					<?php

						if(empty($projects)) {
							echo "There are no projects. Create one using the form below.";
						} else {
							echo '<ul class="ulist">';
							foreach($projects as $project) {
								echo '<li class="ulistitem">';
								echo '<span class="ulistitemlabel"><a href="project.php?id=' . $project->id . '"> ' . $project->name . ' </a></span>';
								echo '</li>';
							}
							echo '</ul>';
						}
					?>
					</form>
					</br>
					<form class='form-body' action ='' method='post'>
						<h2>Create new project</h2>
						<div class='form-section'>
							<label for='name'> Name: </label><input class='form-input' type='text' name='name' id='name' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='description'> Description: </label><input class='form-input' type='text' name='description' id='description' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='startdate'> Start date: </label><input class='form-input' type='text' name='startdate' id='startdate' />
						</div>
						<div class='form-section'>
							<label for='enddate'> End date: </label><input class='form-input' type='text' name='enddate' id='enddate' />
						</div>

						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Create Project</button>
						</div>
					</form>
				</div>
			</div>



<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>