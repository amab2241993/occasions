<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$serviceId = $_POST['serviceId'];
			$name = $_POST['name'];
			$stmt = $con->prepare("UPDATE services SET name = ? WHERE id = ?");
			$stmt->execute(array($name , $serviceId));
			$count = $stmt->rowCount();
			if ($count > 0) {
				echo true;
			}
			else{
				$con->rollback();
				echo false;
			}
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			echo false;
		}
	}
?>