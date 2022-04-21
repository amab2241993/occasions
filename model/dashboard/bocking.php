<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$stmt = $con->prepare("SELECT COUNT(*) FROM bills WHERE status = 1");
			$stmt->execute();
			$numRow 	 = $stmt->fetchColumn() + 1;
			$customerId  = $_POST['customerId'];
			$billDate    = $_POST['billDate'];
			$billType    = $_POST['billType'];
			$numDays     = $_POST['numDays'];
			$baggage     = $_POST['baggage'];
			$relay       = $_POST['relay'];
			$empPrice    = $_POST['empPrice'];
			$total_price = $_POST['total_price'];
			$discount    = $_POST['discount'];
			$price       = $_POST['price'];
			$details     = $_POST['details'];
			$detail = json_encode($details);
			
			$stmt = $con->prepare(
				"INSERT INTO main()VALUES()"
			);
			$stmt->execute();
			if($stmt->rowCount() == 1){
				$id = $con->lastInsertId();
				$stmt = $con->prepare(
					"INSERT INTO bills(main_id , customer_id , code , bill_date , num_days , baggage ,
					 relay , employee_price , total_price , discount , price , details , bill_type)
					 VALUES(:zmainId , :zcustomerId , :zcode , :zbillDate , :znumDay , :zbaggage ,
					 :zrelay , :zempPrice , :ztotalPrice , :zdiscount , :zprice , :zdetail , :zbillType)"
				);
				$stmt->execute(array(
					'zmainId'  	  => $id,
					'zcustomerId' => $customerId,
					'zcode'		  => $numRow,
					'zbillDate'	  => $billDate,
					'znumDay'  	  => $numDays,
					'zbaggage'    => $baggage,
					'zrelay'  	  => $relay,
					'zempPrice'   => $empPrice,
					'ztotalPrice' => $total_price,
					'zdiscount'	  => $discount,
					'zprice'	  => $price,
					'zdetail'	  => $detail,
					'zbillType'	  => $billType
				));
				$result = array(
					["message"=>"success" ,
					"billId" => $con->lastInsertId() ,
					"status"=>100]
				);
				echo json_encode($result);
			}
			else {
				$con->rollback();
				$result = array(
					["message"=>"خطأ فى البيانات" ,
					"billId" => $con->lastInsertId() ,
					"status"=>101]
				);
				echo json_encode($result);
			}
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