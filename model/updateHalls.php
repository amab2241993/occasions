<?php
	session_start();
	include '../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$price = $_POST['price'];
		$breakfast = $_POST['breakfast'];
		$lunch = $_POST['lunch'];
		$dinner = $_POST['dinner'];
		$hallId = $_POST['hallId'];
		$stmt = $con->prepare(
			"UPDATE halls SET name = ? , price = ? , breakfast_time = ? , lunch_time = ?, dinner_time = ? 
			WHERE id = ?"
		);
		$stmt->execute(array($name , $price , $breakfast , $lunch , $dinner , $hallId));
		echo 1;
	}
?>