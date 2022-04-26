<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name  = $_POST['name'];
		$phone = $_POST['phone'];
		$status = $_POST['status'];
		$stmt  = $con->prepare(
			"INSERT INTO customers(name , phone , status)
			 VALUES(:zname , :zphone , :zstatus)"
		);
		$stmt->execute(array(
			'zname'   => $name,
			'zphone'  => $phone,
			'zstatus' => $status
		));
	}
?>