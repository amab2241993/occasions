<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$pass = $_POST['pass'];
		$full = $_POST['full'];
		$pass_hash = sha1($pass);
		$stmt = $con->prepare(
			"INSERT INTO users(user_name , password , full_name)
			 VALUES(:zname , :zpass , :zfull)"
		);
		$stmt->execute(array(
			'zname' =>$name,
			'zpass'	=>$pass_hash,
			'zfull' =>$full
		));
	}
?>