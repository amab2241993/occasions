<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$parent = $_POST['parent'];
			$stmt = $con->prepare("SELECT id,name FROM accounts WHERE parent_id = ?");
			$stmt->execute(array($parent));
			$rows = $stmt->fetchAll();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>