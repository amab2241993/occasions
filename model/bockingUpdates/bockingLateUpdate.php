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
			$stmt = $con->prepare(
				"SELECT move_fake.price FROM bills
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move_fake ON main.id = move_fake.main_id
				 WHERE bills.id = $billId LIMIT 1"
			);
			$stmt->execute();
			$money = $stmt->fetch();
			$stmt = $con->prepare(
				"SELECT refund , pay FROM bill_refund WHERE bill_id = $billId LIMIT 1"
			);
			$stmt->execute();
			$refund = $stmt->fetch();
			$all = 0;
			if((intval($money['price'])  - intval($refund['pay'])) > $price){
				$all = intval($money['price']) -(intval($refund['pay']) + $price);
			}
			$stmt = $con->prepare(
				"UPDATE bill_refund SET amount_paid = ? , amount_total = ? , refund = ?
				 WHERE bill_id = ?"
			);
			$stmt->execute(array(
				(intval($money['price']) - intval($refund['pay'])) , $price , $all , $billId
			));
			$result = array(
				["message"=>"success" ,
				"billId" => $billId,
				"status"=>100]
			);
			echo json_encode($result);
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			$result = array(
				["message"=>"خطأ فى البيانات" ,
				"billId" => $con->lastInsertId() ,
				"status"=>101]
			);
			echo json_encode($result);
		}
	}
?>