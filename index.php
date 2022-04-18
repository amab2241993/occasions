<?php
	session_start();
	if (isset($_SESSION['user_name'])) {
		header('Location:veiw/dashboard/dashboard.php');
	}
	else{
		header('Location:veiw/login/login.php');
	}