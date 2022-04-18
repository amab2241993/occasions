<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$billId      = $_POST['billId'];
			$numDays     = $_POST['numDays'];
			$baggage     = $_POST['baggage'];
			$relay       = $_POST['relay'];
			$empPrice    = $_POST['empPrice'];
			$total_price = $_POST['total_price'];
			$discount    = $_POST['discount'];
			$price       = $_POST['price'];
			$details     = $_POST['details'];
			$detail      = json_encode($details);
			
			$stmt = $con->prepare(
				"UPDATE  bills SET num_days = ? , baggage = ? , relay = ? , employee_price = ? ,
				 total_price = ? , discount = ? , price = ? , details = ? WHERE id = ?"
			);
			$stmt->execute(array(
				$numDays , $baggage , $relay , $empPrice , $total_price , $discount , $price , $detail ,
				$billId
			));
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>