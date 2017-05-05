<?php

/**
 * Mark Greenbank - U1353124
 *
 * Format errors with user friendly html
 *
**/

function formatErrors($errors) {
	echo '<div class="row" style="margin-top: 10px;"">';
	echo '<div class="section">';
	echo '<ul class="ulist">';
	foreach($errors as $error) {
		echo '<li class="ulistitem test-failure">';
			echo '<span class="ulistitemlabel">'. $error .' </span>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
	echo '</div>';
}

?>