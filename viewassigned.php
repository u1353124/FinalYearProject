<?php

/**
 * Mark Greenbank - U1353124
 *
 * Allows the user to view all assigned testruns and defects
 *
**/

require_once $_SERVER['DOCUMENT_ROOT'] . '/inc/templates/header.php';

$user = new User();
if(!$user->isLoggedIn()) {
	Session::flash('index','You need to be logged in to view that page.');
	Redirect::to('index.php');
}

$userid = $user->data()->id;

$query = DB::getInstance()->query("SELECT * FROM defect WHERE assignedto='$userid'");
$defects = $query->results();
$query = DB::getInstance()->query("SELECT * FROM testrun WHERE assignedto='$userid'");
$testruns = $query->results();


?>
			<div class='row' style='margin-top: 100px;'>
				<div class='section'>
					<?php
						if(Session::exists('testset')) {
							echo '<p class="flashmessage">' . Session::flash('testset') . '</p>';
						}
					?>
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
					<br />
					<form class='form-body form-view'>
					<h2>Test runs</h2>
					<ul class='ulist'>
						<?php
							if(empty($testruns)) {
								echo '<li class="ulistitem">';
								echo '<span>There are current no test runs assigned to you.</span>';
								echo '</li>';
							}
							foreach($testruns as $testrun) {
								$test = new Test($testrun->testid);
								$priority = new Testpriority($testrun->testpriorityid);
								$status = new Teststatus($testrun->teststatusid);
								echo '<li class="ulistitem">';
								echo '<span class="ulistitemlabel"><a href="../testrun.php?id=' . $testrun->id . '">' . $test->data()->name . ' (#' . $testrun->id . ')</a> -- Priority: ' . $priority->data()->name . ' -- Status: '. $status->data()->name . ' </span>';
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