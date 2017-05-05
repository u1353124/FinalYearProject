<?php

/**
 * Mark Greenbank - U1353124
 *
 * Allows the user to view all defects
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

$userid = $user->data()->id;

$query = DB::getInstance()->query("SELECT * FROM defect");
$defects = $query->results();

//Logic to handle the submitted form
if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
			//convert steps into an array
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'name' => array(
				'max' => 50,
				'required' => true,
				'unique' => 'defect'
				),
			'description' => array(
				'max' => 65535,
				'min' => 2,
				'required' => true
				),
			'stepstoreproduce' => array(
				'max' => 65535,
				'required' => true
				)
			));

		if($validation->passed()) {

			$assignedUser = new User(Input::get('assignedto'));

			$statuses = Defectstatus::findAll();
			foreach($statuses as $status) {
				if($status->name == Input::get('defectstatus')){
					$newDefectStatus = $status->id;
				}
			}

			$priorities = Testpriority::findAll();
			foreach($priorities as $priority) {
				if($priority->name == Input::get('defectpriority')){
					$newDefectPriority = $priority->id;
				}
			}

			$steps = explode("\n", str_replace("\r", "", Input::get('stepstoreproduce')));
			$json = json_encode($steps, JSON_FORCE_OBJECT);

			$defect = new Defect();
			$defectlink = new Defectlink();
			
			try {
				$defect->create(array(
					'name' => Input::get('name'),
					'description' => Input::get('description'),
					'stepstoreproduce' => $json,
					'defectstatusid' => $newDefectStatus,
					'defectpriorityid' => $newDefectPriority,
					'assignedto' => $assignedUser->data()->id,
					'createdby' => $user->data()->id,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedby' => $user->data()->id,
					'updatedat' => date('Y-m-d H:i:s')
					));

				$newDefect = new Defect(Input::get('name'));

				if(strcmp(Input::get('testrunid'), 'None') !== 0) {
					$defectlink->create(array(
						'defectid' => $newDefect->data()->id,
						'testrunid' => Input::get('testrunid')
					));
				}

				Session::flash('defects','Defect Created');
				Redirect::to('viewdefects.php');

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
						if(Session::exists('defects')) {
							echo '<p class="flashmessage">' . Session::flash('defects') . '</p>';
						}
					?>

					<form class='form-body' action='' method='post'>
						<h2>Create Defect</h2>
						<div class='form-section'>
							<label for='name'> Name: </label><input class='form-input' type='text' name='name' id='name' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='description'> Description: </label><input class='form-input' type='text' name='description' id='description' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='stepstoreproduce'> Steps to reproduce: </label><textarea class='form-input form-text' rows='4' name='stepstoreproduce' id='stepstoreproduce' autocomplete='off' required /></textarea>
						</div>
						<div class='form-section'>
							<label for='defectstatus'> Defect Status: </label><select class='form-input form-select' name='defectstatus' id='defectstatus'>
								<?php
									$statuses = Defectstatus::findAll();
									foreach($statuses as $status) {
										echo '<option>' . $status->name . '</option>';
									}
								?>
							</select>
						</div>
						<div class='form-section'>
							<label for='defectpriority'> Defect Priority: </label><select class='form-input form-select' name='defectpriority' id='defectpriority'>
								<?php
									$priorities = Defectpriority::findAll();
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
										if($useroption->id == $user->data()->id){
											echo '<option selected>' . $useroption->email . '</option>';
										} else {
											echo '<option>' . $useroption->email . '</option>';
										}
									}
								?>
							</select>
						</div>
						<div class='form-section'>
							<label for='testrunid'> Test run: </label><select class='form-input form-select' name='testrunid' id='testrunid'>
								<option>None</option>
								<?php
									$testrunlist = Testrun::findAll();
									foreach($testrunlist as $testrunoption) {
										echo '<option>' . $testrunoption->id . '</option>';
									}
								?>
							</select>
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Create Defect</button>
						</div>
					</form>
					<br />
					<form class='form-body form-view'>
					<h2>Defects</h2>
					<ul class='ulist'>
						<?php
							if(empty($defects)) {
								echo '<li class="ulistitem">';
								echo '<span>There are currently no defects assigned to you.</span>';
								echo '</li>';
							}
							foreach($defects as $defect) {
								$defectpriority = new Defectpriority($defect->defectpriorityid);
								$defectstatus = new Defectstatus($defect->defectstatusid);
								echo '<li class="ulistitem">';
								echo '<span class="ulistitemlabel"><a href="../defect.php?id=' . $defect->id . '">' . $defect->name . ' (#' . $defect->id . ')</a> -- Priority: ' . $defectpriority->data()->name . ' -- Status: '. $defectstatus->data()->name . ' </span>';
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