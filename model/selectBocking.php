<?php
	session_start();
	include '../connect.php';
	header('Content-type: application/json; charset=utf-8');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$id = $_POST['id'];
		// Check If The User Exist In Database
		$stmt = $con->prepare("SELECT price FROM halls WHERE id= ? LIMIT 1");
		$stmt->execute(array($id));
		echo json_encode($stmt->fetch()['price']);
	}
?>