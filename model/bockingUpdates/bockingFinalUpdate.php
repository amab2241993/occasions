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
			$jsons       = $_POST['details'];
			$detail      = json_encode($jsons);
			$stmt = $con->prepare(
				"UPDATE  bills SET num_days = ? , baggage = ? , relay = ? , employee_price = ? ,
				 total_price = ? , discount = ? , price = ? , details = ? WHERE id = ?"
			);
			$stmt->execute(array(
				$numDays , $baggage , $relay , $empPrice , $total_price , $discount , $price , $detail ,
				$billId
			));
			$stmt = $con->prepare(
				"SELECT bill_expense.* , move.id as counter , main.id as mainId FROM bill_expense
				 INNER JOIN main ON bill_expense.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 WHERE bill_id = $billId LIMIT 1"
			);
			$stmt->execute();
			$expense = $stmt->fetch();
			$tent 		    = 0;
			$decoration	    = 0;
			$electricity	= 0;
			$service	 	= 0;
			$administrative = 0;
			$admin		    = 0;
			$warehouse	    = 0;
			$total          = 0;
			foreach($jsons as $json){
				if(intval($json['workerId']) == 1){
					$tent += intval($json['priceWorkers']);
				}
				if(intval($json['workerId']) == 2){
					$decoration += intval($json['priceWorkers']);
				}
				if(intval($json['workerId']) == 3){
					$service += intval($json['priceWorkers']);
				}
				if(intval($json['workerId']) == 4){
					$electricity += intval($json['priceWorkers']);
				}
				$total += intval($json['priceWorkers']);
			}
			$stmt = $con->prepare("SELECT * FROM management");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			foreach($rows as $row){
				if($row['id'] == 1){
					if($row['percent'] == true){
						$administrative = (intval($price) * intval($row['cost']))/100;
					}
					else{
						$administrative = $row['cost'];
					}
					$total += intval($administrative);
				}
				if($row['id'] == 2){
					if($row['percent'] == true){
						$admin = (intval($price) * intval($row['cost']))/100;
					}
					else{
						$admin = $row['cost'];
					}
					$total += intval($admin);
				}
				if($row['id'] == 3){
					if($row['percent'] == true){
						$warehouse = (intval($price) * intval($row['cost']))/100;
					}
					else{
						$warehouse = $row['cost'];
					}
					$total += intval($warehouse);
				}
			}
			$total += intval($expense['incentives']);
			$total += intval($expense['relay_in']);
			$total += intval($expense['relay_out']);
			$total += intval($expense['driver']);
			$total += intval($expense['companion']);
			$stmt = $con->prepare(
				"UPDATE  bill_expense SET tent = ? , decoration = ? , electricity = ? , service = ? ,
					administrative = ? , admin = ? , warehouse = ? , total = ? WHERE bill_id = ?"
			);
			$stmt->execute(array(
				$tent , $decoration , $electricity , $service , $administrative , $admin ,
				$warehouse , $total , $billId
			));
			$stmt = $con->prepare(
				"UPDATE  move SET price = ? WHERE id = ?"
			);
			$stmt->execute(array($total , $expense['counter']));
			$stmt = $con->prepare(
				"UPDATE  move_line SET debtor = ? WHERE move_id = ? AND debtor != ?"
			);
			$stmt->execute(array($total , $expense['counter'] , 0));
			$stmt = $con->prepare(
				"UPDATE  move_line SET creditor = ? WHERE move_id = ? AND creditor != ?"
			);
			$stmt->execute(array($total , $expense['counter'] , 0));
			$stmt = $con->prepare(
				"SELECT move.price FROM bills
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
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