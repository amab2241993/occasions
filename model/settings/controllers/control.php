<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$parent = $_POST['parent'];
			$stmt = $con->prepare("SELECT * FROM accounts WHERE parent_id = $parent");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>