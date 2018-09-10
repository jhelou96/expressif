<?php
//We check if there is any error message to display
if(isset($_SESSION['installation_error'])) {
	echo '
		<div class="alert alert-warning alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<strong>Warning!</strong> ' . $_SESSION['installation_error'] . '
		</div>
	';
	
	unset($_SESSION['installation_error']);
}