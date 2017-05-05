<?php

/**
 * Mark Greenbank - U1353124
 *
 * Page to allow the user to change their password
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
			'password' => array(
				'required' => true,
				'min' => 8,
				'max' => 72
				),
			'newpassword' => array(
				'required' => true,
				'min' => 8,
				'max' => 72
				),
			'confirmnewpassword' => array(
				'required' => true,
				'matches' => 'newpassword'
				)
			));

		if($validation->passed()) {

			if(password_verify(Input::get('password'), $user->data()->password)) {
				try {
					$user->update(array(
						'password' => Hash::make(Input::get('newpassword')),
						'updatedat' => date('Y-m-d H:i:s')
						));

					Session::flash('account', 'Your password has been updated.');
					Redirect::to('account.php');
				} catch (Exception $e) {
					die($e->getMessage());
				}
			} else {
				echo 'Incorrect password';
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
						<h2> Update password</h2>
						<div class='form-section'>
							<label for='password'>Old Password: </label><input class='form-input' name='password' id='password' type='password' autocomplete='off' required/>
						</div>

						<div class='form-section'>
							<label for='newpassword'>New Password: </label><input class='form-input' name='newpassword' id='newpassword' type='password' autocomplete='off' required />
						</div>
						<div class='form-section'>
							<label for='confirmnewpassword'>New Password: </label><input class='form-input' name='confirmnewpassword' id='confirmnewpassword' type='password' autocomplete='off' required/>
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='submit' class='button button-primary'>Update Password</button>
						</div>
					</form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>