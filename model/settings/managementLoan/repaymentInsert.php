<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$details = $_POST['details'];
			foreach($details as $detail){
				$loanId = $detail['loanId'];
				$amount = $detail['amount'];
				$stmt = $con->prepare("UPDATE loans SET repayment = repayment + $amount WHERE id = ?");
				$stmt->execute(array($loanId));
				$count = $stmt->rowCount();
				if($count > 0) echo true;
				else echo false;
			}
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>