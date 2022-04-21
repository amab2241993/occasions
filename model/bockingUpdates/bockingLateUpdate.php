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
			$stmt1 = $con->prepare("SELECT * FROM bill_refund WHERE bill_id = $billId LIMIT 1");
			$stmt1->execute();
			$refund = $stmt1->fetch();
			if($stmt1->rowCount() > 0){
				if(intval($money['price']) <= $price){
					$stmt = $con->prepare("DELETE FROM bill_refund WHERE bill_id = $billId");
					$stmt->execute();
				}
				else{
					$stmt = $con->prepare(
						"UPDATE bill_refund SET amount_paid = ? , amount_total = ? , refund = ?
						 WHERE bill_id = ?"
					);
					$stmt->execute(array(
						$money['price'] , $price , (intval($money['price']) - intval($price)) , $billId
					));

				}
			}
			else{
				if(intval($money['price']) > $price){
					$stmt = $con->prepare(
						"INSERT  INTO bill_refund(amount_paid , amount_total , refund , bill_id)
						 VALUES(:zamountPaid , :zamountTotal , :zrefund , :zbillId)"
					);
					$stmt->execute(array(
						'zamountPaid'  => $money['price'] ,
						'zamountTotal' => $price ,
						'zrefund'      => (intval($money['price']) - intval($price)) , 
						'zbillId' 	   => $billId
					));

				}
			}
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