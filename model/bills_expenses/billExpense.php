<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$relayIn  	= $_POST['relayIn'];
			$relayOut 	= $_POST['relayOut'];
			$incentives	= $_POST['incentives'];
			$driver     = $_POST['driver'];
			$total     	= $_POST['total'];
			$counter 	= $_POST['counter'];
			$mainId    	= $_POST['mainId'];
			$stmt = $con->prepare(
				"UPDATE bill_expense SET relay_in = ? , relay_out = ? , incentives = ? , driver = ? ,
				 total = ? WHERE id = ?"
			);
			$stmt->execute(array($relayIn , $relayOut , $incentives , $driver , $total , $counter));
			
			$stmt = $con->prepare(
				"UPDATE move SET price = ? WHERE main_id = ?"
			);
			$stmt->execute(array($total , $mainId));
			
			$stmt = $con->prepare("SELECT id FROM move WHERE main_id = $mainId LIMIT 1");
			$stmt->execute();
			$moveId = $stmt->fetch();
			
			$stmt = $con->prepare(
				"UPDATE move_line SET debtor = ? WHERE move_id = ? AND debtor != 0"
			);
			$stmt->execute(array($total , $moveId['id']));
			
			$stmt = $con->prepare(
				"UPDATE move_line SET creditor = ? WHERE move_id = ? AND creditor != 0"
			);
			$stmt->execute(array($total , $moveId['id']));

			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>