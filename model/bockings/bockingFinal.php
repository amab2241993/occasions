<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$billId    = $_POST['billId'];
			$main      = $_POST['main'];
			$type 	   = $_POST['type'];
			$typeM	   = $_POST['typeM'];
			$price	   = $_POST['price'];
			$idA       = 3; // رقم الإيرادات
			$idE       = 4; // رقم المصروفات
			$idAA      = 12; // رقم الحجوزات
			$idEE      = 20; // رقم مصاريف فواتير
			$stmt = $con->prepare("SELECT COUNT(*) FROM bills WHERE status = 2");
			$stmt->execute();
			$numRow = $stmt->fetchColumn() + 1;
			$statment1 = "ايرادات فاتورة رقم ".$numRow;
			$statment2 = "مصاريف فاتورة رقم ".$numRow;
			$stmt      = $con->prepare(
				"INSERT INTO move (main_id , account_id , price , statment)
				 VALUES(:zmain , :zaccount , :zprice , :zstatment)"
			);
			$stmt->execute(array(
				'zmain'     => $main,
				'zaccount'  => $idA,
				'zprice'    => $price,
				'zstatment' => $statment1
			));
			$lastId = $con->lastInsertId();
			$stmt = $con->prepare(
				"INSERT INTO move_line(creditor , move_id , account_id)
				 VALUES(:zcreditor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zcreditor'   => $price,
				'zmove'       => $lastId,
				'zaccount_id' => $type,
			));
			$stmt = $con->prepare(
				"INSERT INTO move_line(debtor , move_id , account_id)
				 VALUES(:zdebtor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zdebtor'     => $price,
				'zmove'       => $lastId,
				'zaccount_id' => $idAA,
			));
			$tent 		 	= 0;
			$decoration	 	= 0;
			$electricity 	= 0;
			$service	 	= 0;
			$administrative = 0;
			$admin			= 0;
			$warehouse		= 0;
			$total          = 0;
			$stmt =$con->prepare("SELECT id , details , code , price FROM bills WHERE id = $billId LIMIT 1");
			$stmt->execute();
			$bill = $stmt->fetch();
			$billCode = $bill['code'];
			$jsons = json_decode($bill["details"]);
			foreach($jsons as $json){
				if(intval($json->workerId) == 1){
					$tent += intval($json->priceWorkers);
				}
				if(intval($json->workerId) == 2){
					$decoration += intval($json->priceWorkers);
				}
				if(intval($json->workerId) == 3){
					$service += intval($json->priceWorkers);
				}
				if(intval($json->workerId) == 4){
					$electricity += intval($json->priceWorkers);
				}
				$total += intval($json->priceWorkers);
			}
			$stmt = $con->prepare("SELECT * FROM management");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			foreach($rows as $row){
				if($row['id'] == 1){
					if($row['percent'] == true){
						$administrative = (intval($bill['price']) * intval($row['cost']))/100;
					}
					else{
						$administrative = $row['cost'];
					}
					$total += intval($administrative);
				}
				if($row['id'] == 2){
					if($row['percent'] == true){
						$admin = (intval($bill['price']) * intval($row['cost']))/100;
					}
					else{
						$admin = $row['cost'];
					}
					$total += intval($admin);
				}
				if($row['id'] == 3){
					if($row['percent'] == true){
						$warehouse = (intval($bill['price']) * intval($row['cost']))/100;
					}
					else{
						$warehouse = $row['cost'];
					}
					$total += intval($warehouse);
				}
			}
			
			$stmt = $con->prepare(
				"INSERT INTO main()VALUES()"
			);
			$stmt->execute();
			if($stmt->rowCount() == 1){
				$idM = $con->lastInsertId();
				$stmt = $con->prepare(
					"INSERT INTO bill_expense(bill_id , main_id , tent , decoration , electricity ,
					 service , administrative , admin , warehouse , total)
					 VALUES(:zbill , :zmain , :ztent , :zdecoration , :zelectricity , :zservice ,
					 :zadministrative , :zadmin , :zwarehouse , :ztotal)"
				);
				$stmt->execute(array(
					'zbill'  		  => $bill['id'],
					'zmain'			  => $idM,
					'ztent'		  	  => $tent,
					'zdecoration'	  => $decoration,
					'zelectricity'    => $electricity,
					'zservice'    	  => $service,
					'zadministrative' => $administrative,
					'zadmin' 		  => $admin,
					'zwarehouse'	  => $warehouse,
					'ztotal'	      => $total,
				));
				$stmt  = $con->prepare(
					"INSERT INTO move (main_id , account_id , price , statment)
					 VALUES(:zmain , :zaccount , :zprice , :zstatment)"
				);
				$stmt->execute(array(
					'zmain'     => $idM,
					'zaccount'  => $idE,
					'zprice'    => $total,
					'zstatment' => $statment2
				));
				$lastIdM = $con->lastInsertId();
				$stmt = $con->prepare(
					"INSERT INTO move_line(debtor , move_id , account_id)
					 VALUES(:zdebtor , :zmove , :zaccount_id)"
				);
				$stmt->execute(array(
					'zdebtor'     => $total,
					'zmove'       => $lastIdM,
					'zaccount_id' => $typeM,
				));
				$stmt = $con->prepare(
					"INSERT INTO move_line(creditor , move_id , account_id)
					 VALUES(:zcreditor , :zmove , :zaccount_id)"
				);
				$stmt->execute(array(
					'zcreditor'   => $total,
					'zmove'       => $lastIdM,
					'zaccount_id' => $idEE,
				));
			}
			$stmt = $con->prepare("UPDATE bills SET status = 2 , code = $numRow WHERE id = $billId");
			$stmt->execute();
			$stmt = $con->prepare("SELECT id , code FROM bills WHERE code > $billCode AND status = 1 ORDER BY code ASC");
			$stmt->execute();
			$codes = $stmt->fetchAll();
			if($stmt->rowCount() > 0){
				foreach($codes as $x){
					$coder = ($x['code'] - 1);
					$counter = $x['id'];
					$stmt = $con->prepare("UPDATE bills SET code = $coder WHERE id = $counter");
					$stmt->execute();
				}
			}
			$result = array(
				["message"=>"success" ,
				"billId" => $billId,
				"status"=>100]
			);
			echo json_encode($result);
			$con->commit();
		}catch(PDOExecption $e) {
			$con->rollback();
			$result = array(
				["message"=>"خطأ فى البيانات" ,
				"billId" => $billId,
				"status"=>101]
			);
			echo json_encode($result);
		}
	}
?>