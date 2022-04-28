<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$employeeId = $_POST['employeeId'];
		$salary     = $_POST['salary'];
		$stmt = $con->prepare(
			"UPDATE employees SET salary = ? WHERE id = ?"
		);
		$stmt->execute(array($salary , $employeeId));
		$count = $stmt->rowCount();
		if($count > 0){
			echo true;
		}
		else{
			echo false;
		}
	}
?>