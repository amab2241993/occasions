<?php
	session_start();
	include '../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$price = $_POST['price'];
		$breakfast = $_POST['breakfast'];
		$lunch = $_POST['lunch'];
		$dinner = $_POST['dinner'];
		$stmt = $con->prepare(
			"INSERT INTO halls(name , price , breakfast_time , lunch_time , dinner_time)
			 VALUES(:zname , :zprice , :zbreakfast , :zlunch , :zdinner)"
		);
		$stmt->execute(array(
			'zname'		 =>$name,
			'zprice'	 =>$price,
			'zbreakfast' =>$breakfast,
			'zlunch'	 =>$lunch,
			'zdinner'	 =>$dinner
		));
	}
?>