<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$employeeId = $_POST['employeeId'];
		$employeeA  = $_POST['employeeA'];
		$employeeP  = $_POST['employeeP'];
		$employeeS  = $_POST['employeeS'];
		$employeeN  = $_POST['employeeN'];
		$stmt = $con->prepare(
			"UPDATE employees SET name = ? , phone = ?  , address = ? , statement = ? WHERE id = ?"
		);
		$stmt->execute(array($employeeN , $employeeP , $employeeA , $employeeS , $employeeId));
		$count = $stmt->rowCount();
		if($count > 0){
			echo true;
		}
		else{
			echo false;
		}
	}
?>