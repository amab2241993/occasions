<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name     = $_POST['name'];
		$phone    = $_POST['phone'];
		$address  = $_POST['address'];
		$jobTitle = $_POST['jobTitle'];
		$stmt  = $con->prepare(
			"INSERT INTO employees(name , phone , address , statement)
			 VALUES(:zname , :zphone , :zaddress , :zstatment)"
		);
		$stmt->execute(array(
			'zname'     => $name,
			'zphone'    => $phone,
			'zaddress'  => $address,
			'zstatment' => $jobTitle
		));
	}
?>