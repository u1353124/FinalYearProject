<?php

/**
 *
 * Test set for week1 testing
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

/*
 * Testing array
 * To add a new test use the format:
 * array("Assertion", functiontotest, TestDescription);
**/

$tests = array(
	array(
		"127.0.0.1", 
		Config::get('mysql/host'),
		"Testing 'get' function in 'Config' class with correct value"
		),
	array(
		"hash",
	 	Config::get('remember/cookie_name'),
	 	"Testing 'get' function in 'Config' class with correct value"
	 	),
	array(
		"root",
		Config::get('mysql/username'),
		"Testing 'get' function in 'Config' class with correct value"
		),
	array(
		null,
		Config::get(),
		"Testing 'get' function in 'Config' class with no value"
		),
	array(
		604800,
		Config::get('remember/cookie_expiry'),
		"Testing 'get' function in 'Config' class with correct value and returning integers"
		),
	array(
		null,
		Config::get('remember'),
		"Testing 'get' function in 'Config' class with incorrect value"
		),
	array(
		true,
		Cookie::put('testcookie', 'testvalue', 60),
		"Testing 'put' function in 'Cookie' class with correct value"
		),
	array(
		true,
		Cookie::exists('testcookie'),
		"Testing 'exists' function in 'Cookie' class with correct value"
		),
	array(
		false,
		Cookie::put('', 'testvalue', 60),
		"Testing 'put' function in 'Cookie' class with empty name"
		),
	array(
		false,
		Cookie::put('testcookie2', '', 60),
		"Testing 'put' function in 'Cookie' class with empty value"
		),
	array(
		false,
		Cookie::put('', '', 60),
		"Testing 'put' function in 'Cookie' class with empty value and name"
		)
	);

$testuser = DB::getInstance()->get('users', array('firstname', '=', 'Mark'));

array_push($tests, array("Mark", $testuser->first()->firstname, "Testing 'get' and 'first' functions of 'DB' class"));
$tu='';
$testuser = DB::getInstance()->query("SELECT * FROM users WHERE email='mgreenbank2@gmail.com'");
foreach($testuser->results() as $testu) {
	$tu = $testu->email;
}

array_push($tests, array("mgreenbank2@gmail.com", $tu, "Testing 'query' and 'first' and 'results' functions of 'DB' class"));

$testuser = DB::getInstance()->query("SELECT * FROM users WHERE email='dfgdfg");

array_push($tests, array(true, $testuser->error(), "Testing 'query' and 'error' functions of 'DB' class with incorrect details"));

$testuser = DB::getInstance()->query("SELECT * FROM users WHERE test=''");

array_push($tests, array(true, $testuser->error(), "Testing 'query' and 'error' functions of 'DB' class with incorrect column name"));

$testuser = DB::getInstance()->insert('users', array(
					'firstname' => 'Inserttest',
					'lastname' => 'Inserttest',
					'email' => 'Inserttest',
					'password' => 'test1234',
					'userlevel' => 1,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));

array_push($tests, array(true, $testuser, "Testing 'insert' functions of 'DB' class with correct details"));

$testuser = DB::getInstance()->insert('users', array(
					'firstname' => 'Inserttest',
					'las234tname' => 'Inserttest',
					'email' => 'Inserttest',
					'pass234word' => 'test1234',
					'userlevel' => 1,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));

array_push($tests, array(false, $testuser, "Testing 'insert' functions of 'DB' class with incorrect column names"));

$testuser = DB::getInstance()->insert('users', array(
					'firstname' => 'Inserttest',
					'las234tname' => 'Inserttest',
					'email' => 'Inserttest',
					'pass234word' => 'test1234',
					'userlevel' => 1,
					'createdat' => 123,
					'updatedat' => date('Y-m-d H:i:s')
					));

array_push($tests, array(false, $testuser, "Testing 'insert' functions of 'DB' class with incorrect column data types"));

$testuser = DB::getInstance()->insert('tests', array(
					'firstname' => 'Inserttest',
					'lastname' => 'Inserttest',
					'email' => 'Inserttest',
					'password' => 'test1234',
					'userlevel' => 1,
					'createdat' => date('Y-m-d H:i:s'),
					'updatedat' => date('Y-m-d H:i:s')
					));

array_push($tests, array(false, $testuser, "Testing 'insert' functions of 'DB' class with incorrect table name"));

$testuser = DB::getInstance()->update('users', '11', array(
					'firstname' => 'Updatedtest',
					'lastname' => 'Updatedtest',
					'updatedat' => date('Y-m-d H:i:s')
					));

array_push($tests, array(true, $testuser, "Testing 'update' functions of 'DB' class with correct details"));

$testuser = DB::getInstance()->update('users', '11', array(
					'firstname' => 'Updatedtest',
					'last123name' => 'Updatedtest',
					'updatedat' => date('Y-m-d H:i:s')
					));

array_push($tests, array(false, $testuser, "Testing 'update' functions of 'DB' class with incorrect column names"));

$testuser = DB::getInstance()->update('users', '11', array(
					'firstname' => 'Updatedtest',
					'lastname' => 'Updatedtest',
					'updatedat' => 123
					));

array_push($tests, array(false, $testuser, "Testing 'update' functions of 'DB' class with incorrect column data types"));

$testuser = DB::getInstance()->update('ustesers', '11', array(
					'firstname' => 'Updatedtest',
					'lastname' => 'Updatedtest',
					'updatedat' => date('Y-m-d H:i:s')
					));

array_push($tests, array(false, $testuser, "Testing 'update' functions of 'DB' class with incorrect table name"));

$hashtest = Hash::make('Test');

array_push($tests, array(true, password_verify('Test', $hashtest), "Testing 'make' function of 'Hash' class with correct variable"));

$hashtest = Hash::make('Test');

array_push($tests, array(false, password_verify('Test1', $hashtest), "Testing 'make' function of 'Hash' class with incorrect variable"));



$failcount = 0;
$totaltests = count($tests);
$passcount = 0;
?>


			<div class='row' style='margin-top: 10px;'>
				<div class='section'>
				<br /><br />
					<a href="#stats"><h3> Jump to Result Statistics</h3></a>
					<?php
						for ($row = 0; $row < count($tests); $row++) { ?>
							<h3><?php echo 'Test ' . ($row+1); ?></h1>
							<p><?php echo $tests[$row][2]; ?></p>
							<ul class='ulist'>
								<?php 
								if(strcmp($tests[$row][0], $tests[$row][1]) !== 0) { $failcount++; ?>
									<li class='ulistitem test-failure'>
										<span class='ulistitemlabel'>Expected Value: </span><?php echo $tests[$row][0]; ?>
									</li>
									<li class='ulistitem test-failure'>
										<span class='ulistitemlabel'>Test Value: </span><?php echo $tests[$row][1]; ?>
									</li>
								<?php } else { $passcount++;?>
									<li class='ulistitem test-success'>
										<span class='ulistitemlabel'>Expected Value: </span><?php echo $tests[$row][0]; ?>
									</li>
									<li class='ulistitem test-success'>
										<span class='ulistitemlabel'>Test Value: </span><?php echo $tests[$row][1]; ?>
									</li>
								<?php }	?>
							</ul>
					<?php }	?>

					<a name="stats"></a>
					<h2>Statistics </h2>
					<ul class='ulist'>
						<li class='ulistitem'>Total Tests: <?php echo $totaltests;?></li>
						<li class='ulistitem'>Passed Tests: <?php echo $passcount; echo ' (' . number_format((float)(($passcount / $totaltests) * 100), 2, '.', '') . '%)';?></li>
						<li class='ulistitem'>Failed Tests: <?php echo $failcount; echo ' (' . number_format((float)(($failcount / $totaltests) * 100), 2, '.', '') . '%)';?></li>
					</ul>

				</div>
			</div>

<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/footer.php';
?>