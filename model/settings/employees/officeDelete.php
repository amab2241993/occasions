<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$customerId = $_POST['customerId'];
			$stmt = $con->prepare("DELETE FROM customers WHERE id = ?");
			$stmt->execute(array($customerId));
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