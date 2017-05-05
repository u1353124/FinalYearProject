<?php

/**
 * Mark Greenbank - U1353124
 * 
 * Function to update user information
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'firstname' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
				),
			'lastname' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
				)
			));

		if($validation->passed()) {

			try {
				$user->update(array(
					'firstname' => Input::get('firstname'),
					'lastname' => Input::get('lastname'),
					'updatedat' => date('Y-m-d H:i:s')
					));

				Session::flash('account', 'Your details have been updated.');
				Redirect::to('account.php');
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
					<form class='form-body' method='post' action =''>
						<h2> Update details</h2>
						<div class='form-section'>
							<label for='firstname'>First name: </label><input class='form-input' name='firstname' id='firstname' type='text' value='<?php echo $user->data()->firstname; ?>' autocomplete='off' />
						</div>

						<div class='form-section'>
							<label for='lastname'>Last name: </label><input class='form-input' name='lastname' id='lastname' type='text' value='<?php echo $user->data()->lastname; ?>' autocomplete='off' />
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='submit' class='button button-primary'>Update Details</button>
						</div>
					</form>


<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>