<?php
	session_start();
	include '../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$price = $_POST['price'];
		if(isset($_POST['parent'])&& $_POST['parent'] != NULL){
			$parent = $_POST['parent'];
			$stmt = $con->prepare(
				"INSERT INTO services(parent_id , name , price)VALUES(:zparent,:zname,:zprice)"
			);
			$stmt->execute(array('zparent'=>$parent,'zname'=>$name,'zprice'=>$price));
		}
		else{
			$stmt = $con->prepare(
				"INSERT INTO services(name , price)VALUES(:zname,:zprice)"
			);
			$stmt->execute(array('zname'=>$name,'zprice'=>$price));
		}
	}
?>