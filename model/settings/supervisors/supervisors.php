<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$details = $_POST['details'];
			foreach($details as $detail){
				$id   = $detail['id'];
				$cost = $detail['cost'];
				if(intval($id) == 3){
					$cost = substr($cost , 1);
					echo $cost;
				}
				$stmt = $con->prepare("UPDATE management SET cost = $cost WHERE id = ?");
				$stmt->execute(array($id));
				echo true;
			}
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>