<?php

/**
 * Mark Greenbank - U1353124
 *
 * Register page, allows the user to register an account
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

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
				),
			'email' => array(
				'required' => true,
				'min' => 4,
				'max' => 255,
				'unique' => 'users'
				),
			'password' => array(
				'required' => true,
				'min' => 8,
				'max' => 72
				),
			'confpassword' => array(
				'required' => true,
				'matches' => 'password'
				)
			));

		if($validation->passed()) {

			$user = new User();

			try {
				$user->create(array(
					'firstname' => Input::get('firstname'),
					'lastname' => Input::get('lastname'),
					'email' => Input::get('email'),
					'password' => Hash::make(Input::get('password')),
					'userlevel' => 1,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));

				Session::flash('index','You have been registered!');
				Redirect::to('index.php');

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
						<h2> Register</h2>
						<div class='form-section'>
							<label for='firstname'>Firstname: </label><input class='form-input' name='firstname' id='firstname' type='text' value='<?php echo escape(Input::get('firstname')); ?>' autocomplete='off' required/>
						</div>

						<div class='form-section'>
							<label for='lastname'>Lastname: </label><input class='form-input' name='lastname' id='lastname' type='text' value='<?php echo escape(Input::get('lastname')); ?>' autocomplete='off' required/>
						</div>

						<div class='form-section'>
							<label for='email'>Email: </label><input class='form-input' name='email' id='email' type='email' value='<?php echo escape(Input::get('email')); ?>' autocomplete='off' required/>
						</div>

						<div class='form-section'>
							<label for='password'>Password: </label><input class='form-input' name='password' id='password' type='password' required/>
						</div>

						<div class='form-section'>
							<label for='confpassword'>Confirm Password: </label><input class='form-input' name='confpassword' id='confpassword' type='password' required/>
						</div>
						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='submit' class='button button-primary'>Register</button>
						</div>
					</form>
				</div>
			</div>


<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>