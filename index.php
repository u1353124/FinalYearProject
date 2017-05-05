<?php

/**
 * Mark Greenbank - U1353124
 *
 * Index page, allows the user to login
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'email' => array(
				'required' => true,
				'min' => 4,
				'max' => 255
				),
			'password' => array(
				'required' => true,
				'min' => 8,
				'max' => 72
				)
			));

		if($validation->passed()) {
			$user = new User();

			$remember = (Input::get('remember') === 'on') ? true : false;

			$login = $user->login(Input::get('email'), Input::get('password'), $remember);

			if($login) {
				Redirect::to('home.php');
			} else {
				formatErrors(array("Details are incorrect."));
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
						if(Session::exists('index')) {
							echo '<p class="flashmessage">' . Session::flash('index') . '</p>';
						}
					?>
					<form class='form-body' method='post' action =''>
						<h2> Login</h2>
						<div class='form-section'>
							<label for='email'>Email: </label><input class='form-input' name='email' id='email' type='text' value='' autocomplete="off" required/>
						</div>

						<div class='form-section'>
							<label for='password'>Password: </label><input class='form-input' name='password' id='password' type='password' required/>
						</div>

						<div class='form-section'>
							<label class='remember' for='remember'> Remember me</label><input type='checkbox' name='remember' id='remember' />
						</div>

						<input type='hidden' name='token' value='<?php echo Token::generate(); ?>'/>
						<div class='form-section'>
							<button type='Submit' class='button button-primary'>Login</button>
						</div>
					</form>
					<br />
					<a href='register.php' style='margin-left: 35%; text-align: center;'>Click here to register a user.</a>
				</div>
			</div>

<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';

?>