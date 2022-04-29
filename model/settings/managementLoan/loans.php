<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name     = $_POST['name'];
		$phone    = $_POST['phone'];
		$address  = $_POST['address'];
		$stmt  = $con->prepare(
			"INSERT INTO loans(name , phone , address)
			 VALUES(:zname , :zphone , :zaddress)"
		);
		$stmt->execute(array(
			'zname'     => $name,
			'zphone'    => $phone,
			'zaddress'  => $address
		));
	}
?>