<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$price = $_POST['price'];
		$serviceId = $_POST['serviceId'];
		$stmt = $con->prepare(
			"UPDATE services SET name = ? , price = ? WHERE id = ?"
		);
		$stmt->execute(array($name,$price,$serviceId));
	}
?>