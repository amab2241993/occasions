<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name    = $_POST['name'];
		$phone   = $_POST['phone'];
		$address = $_POST['address'];
		$status  = $_POST['status'];
		$stmt    = $con->prepare(
			"INSERT INTO customers(name , phone , address , status)
			 VALUES(:zname , :zphone , :zaddress , :zstatus)"
		);
		$stmt->execute(array(
			'zname'    => $name,
			'zphone'   => $phone,
			'zaddress' => $zaddress,
			'zstatus'  => $status
		));
	}
?>