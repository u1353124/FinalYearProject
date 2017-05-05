<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/init.php';

//Display the navigation bar if the user is logged in

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../inc/style/style.css">
		<title> Mark Greenbank - U1353124 - Final Year Project</title>
	</head>

	<body>
		<div class='header'></div>

		<?php

			$user = new User();
			if($user->isLoggedIn()) {
			?>	
				<div class='navigation'>
					<div class='navcontainer'>
						<div class='section'>
							<a class='navlink' href='../home.php'>Home</a><!--
							--><a class='navlink' href='../projects.php'>View Projects</a><!--
							--><a class='navlink' href='../viewassigned.php'>View Assigned Work</a><!--
							--><a class='navlink' href='../viewdefects.php'>View Defects</a><!--
							--><a class='navlink' href='../account.php'>Account Settings</a><!--
							--><a class='navlink' href='../logout.php'>Logout</a>
						</div>
					</div>
				</div>
			<div class='container'>
				<div class='title'><?php echo Config::get('company/name'); ?></div>
		<?php

			} else {
		?>
		<div class='container'>
			<div class='title2'><?php echo Config::get('company/name'); ?></div>
		<?php
		}
		?>
