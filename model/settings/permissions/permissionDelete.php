<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$userPermissionId = $_POST['userPermissionId'];
			$stmt = $con->prepare("DELETE FROM user_permission WHERE id = ?");
			$stmt->execute(array($userPermissionId));
			$count = $stmt->rowCount();
			if ($count > 0) {
				$result = true;
			}
			else{
				$result = false;
			}
			echo $result;
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>