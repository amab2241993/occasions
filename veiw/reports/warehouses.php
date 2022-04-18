<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'warehouses';
		$getH3 = 'تقارير المستودعات';
		include '../../init.php';
		$discount = 0;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$date1 = '' ;
			$date2 = '' ;
			if($_POST['date1'] >= $_POST['date2']){
				$date1 = $_POST['date1'];
				$date2 = $_POST['date2'];
			}
			else{
				$date2 = $_POST['date1'];
				$date1 = $_POST['date2'];
			}
			$stmt = $con->prepare(
				"SELECT move.price , move.statment , move.account_id as accountMove ,
				 accounts.name , move_line.account_id as accId , move_line.creditor , 
				 move_line.debtor , move_line.created_at FROM move
				 INNER JOIN move_line ON move.id = move_line.move_id
				 INNER JOIN accounts ON move_line.account_id = accounts.id
				 WHERE move_line.created_at >= ? AND move_line.created_at <= ?"
			);
			$stmt->execute(array($date2,$date1));
			$moves = $stmt->fetchAll();
			
			$stmt = $con->prepare(
				"SELECT bills.price , bills.bill_date ,
				 bill_expense.warehouse , bill_expense.total ,
				 move.price as amount , move.statment , move.account_id as accountMove ,
				 accounts.name ,
				 move_line.account_id as accId
				 FROM bills INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 INNER JOIN move_line ON move.id = move_line.move_id
				 INNER JOIN accounts ON move_line.account_id = accounts.id
				 WHERE bills.bill_date >= ? AND bills.bill_date <= ?
				 ORDER BY move_line.id DESC"
			);
			$stmt->execute(array($date2,$date1));
			$bills = $stmt->fetchAll();

			$stmt = $con->prepare(
				"SELECT sum(bill_refund.refund) as discount FROM bills
				 INNER JOIN bill_refund ON bills.id = bill_refund.bill_id
				 WHERE bills.bill_date >= ? AND bills.bill_date <= ?"
			);
			$stmt->execute(array($date2,$date1));
			if($data = $stmt->fetch(PDO::FETCH_ASSOC)){
				$discount  = $data['discount'];
			}
		}
		else{
			$date = date('Y-m');
			$timestamp = strtotime($date);
			$year = date('Y', $timestamp);
			$month = date('m', $timestamp);
			$stmt = $con->prepare(
				"SELECT move.price , move.statment , move.account_id as accountMove ,
				 accounts.name , move_line.account_id as accId , move_line.creditor , 
				 move_line.debtor , move_line.created_at FROM move
				 INNER JOIN move_line ON move.id = move_line.move_id
				 INNER JOIN accounts ON move_line.account_id = accounts.id
				 WHERE month(move_line.created_at) = ? AND year(move_line.created_at) = ?"
			);
			$stmt->execute(array($month,$year));
			$moves = $stmt->fetchAll();
			
			$stmt = $con->prepare(
				"SELECT bills.price , bills.bill_date ,
				 bill_expense.warehouse , bill_expense.total ,
				 move.price as amount , move.statment , move.account_id as accountMove ,
				 accounts.name ,
				 move_line.account_id as accId
				 FROM bills INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 INNER JOIN move_line ON move.id = move_line.move_id
				 INNER JOIN accounts ON move_line.account_id = accounts.id
				 WHERE month(bills.bill_date) = ? AND year(bills.bill_date) = ?
				 ORDER BY move_line.id DESC"
			);
			$stmt->execute(array($month,$year));
			$bills = $stmt->fetchAll();
			
			$stmt = $con->prepare(
				"SELECT sum(bill_refund.refund) as discount FROM bills
				 INNER JOIN bill_refund ON bills.id = bill_refund.bill_id
				 WHERE month(bills.bill_date) = ? AND year(bills.bill_date) = ?"
			);
			$stmt->execute(array($month,$year));
			if($data = $stmt->fetch(PDO::FETCH_ASSOC)){
				$discount  = $data['discount'];
				if($discount == null || $discount == "") $discount = 0;
			}
		}
	?>
	<form class="row g-3 needs-validation" novalidate action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="col-3">
			<input type = "date" class="form-control date1" name="date1" required>
		</div>
		<div class="col-3">
			<input type="date" class="form-control date2" name="date2" required>
		</div>
		<div class="col-1">
			<button type="submit" class="btn btn-success" style="margin-bottom:10px">بحث</button>
		</div>
	</form>
	<?php
		$warehouse = array_sum(array_map(function($item) {
			return intval($item['warehouse']); 
		}, $bills));
		$price = array_sum(array_map(function($item) {
			return intval($item['price']); 
		}, $bills));
		$total = array_sum(array_map(function($item) {
			return intval($item['total']); 
		}, $bills));
		$amount = array_sum(array_map(function($item) {
			return intval($item['amount']); 
		}, $bills));
		$creditor = array_sum(array_map(function($item) {
			if($item['accId'] == 8 || $item['accountMove'] == 15){
				if($item['accId'] != 6 && $item['accId'] != 7){
					return intval($item['creditor']);
				}
			}
		}, $moves));
		$debtor = array_sum(array_map(function($item) {
			if($item['accId'] == 8 || $item['accountMove'] == 15){
				if($item['accId'] != 6 && $item['accId'] != 7){
					return intval($item['debtor']);
				}
			}
		}, $moves));
		
		$general = array_sum(array_map(function($item) {
			if($item['accountMove'] == 13){
				if($item['accId'] != 6 && $item['accId'] != 7){
					return intval($item['creditor']);
				}
			}
		}, $moves));
		$fixed = array_sum(array_map(function($item) {
			if($item['accountMove'] == 14){
				if($item['accId'] != 6 && $item['accId'] != 7){
					return intval($item['creditor']);
				}
			}
		}, $moves));
		$profitOld = (intval($price)/2) - ((intval($total)/2) + intval($discount));
		$profits   = $profitOld - (intval($general) + intval($fixed));
		$debts     = (intval($discount) + (intval($price)/2)) - (intval($amount)/2);
		$profitNew = $profits - $debts;
		$result4   = intval(($profits*25)/100);
		$result7   = intval(($profitNew*25)/100);
		$all       = intval($warehouse/2) + intval($debtor) + intval($result7) - intval($creditor);
	?>
	<table id="warehouses" class="table table-striped table-bordered">
		<thead class= 'tableStyle'>
			<tr>
				<th scope="col-1">التاريخ</th>
				<th scope="col-2">البند الرئيسي</th>
				<th scope="col-2">البند الفرعي</th>
				<th scope="col-1">البيان</th>
				<th scope="col-1">إيداع</th>
				<th scope="col-1">سحب</th>
				<th scope="col-1">الأرباح</th>
				<th scope="col-1">الرصيد</th>
				<th scope="col-1">الديون</th>
				<th scope="col-1">حذف</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$index = 0;
			foreach($bills as $bill){
				if($index % 2 == 0){
					echo "<tr>";
				}
				if($bill['accId'] != 6 && $bill['accId'] != 7){
					$stmt = $con->prepare("SELECT name FROM accounts WHERE id = ? LIMIT 1");
					$stmt->execute(array($bill['accountMove']));
					$accountMove = $stmt->fetch();
				?>
				<td scope="col-md-1"><?=$bill['bill_date']?></td>
				<td scope="col-md-2"><?=$accountMove['name']?></td>
				<td scope="col-md-2"><?=$bill['name']?></td>
				<td scope="col-md-1"><?=substr($bill['statment'] , 15)?></td>
				<td scope="col-md-1"><?=$bill['warehouse']?></td>
				<td scope="col-md-1">0</td>
				<td scope="col-md-1"></td>
				<td scope="col-md-1"></td>
				<td scope="col-md-1"></td>
				<td scope="col-md-1"></td>
				<?php
				}
				$index++;
				if($index % 2 == 0){
					echo "</tr>";
				}
			}
			foreach($moves as $move){
				if($move['accId'] == 8 || $move['accountMove'] == 15){
					if($move['accId'] != 6 && $move['accId'] != 7){
						$stmt = $con->prepare("SELECT name FROM accounts WHERE id = ? LIMIT 1");
						$stmt->execute(array($move['accountMove']));
						$accountMove = $stmt->fetch();
						$createDate = new DateTime($move['created_at']);
						$strip = $createDate->format('Y-m-d');
					?>
					<tr>
						<td scope="col-md-1"><?=$strip?></td>
						<td scope="col-md-2"><?=$accountMove['name']?></td>
						<td scope="col-md-2"><?=$move['name']?></td>
						<td scope="col-md-1"><?=$move['statment']?></td>
						<td scope="col-md-1"><?=$move['debtor']?></td>
						<td scope="col-md-1"><?=$move['creditor']?></td>
						<td scope="col-md-1"></td>
						<td scope="col-md-1"></td>
						<td scope="col-md-1"></td>
						<td scope="col-md-1"></td>
					</tr>
					<?php
					}
				}
			}
		?>
		</tboby>
		<thead class= 'table-dark'>
			<tr>
				<td scope="col-md-1">الأجمالي</td>
				<td scope="col-md-2"></td>
				<td scope="col-md-2"></td>
				<td scope="col-md-1"></td>
				<td scope="col-md-1"><?=($warehouse/2) + $debtor?></td>
				<td scope="col-md-1"><?=$creditor?></td>
				<td scope="col-md-1"><?=$result7?></td>
				<td scope="col-md-1"><?=$all?></td>
				<td scope="col-md-1"><?=$result4 - $result7?></td>
				<td scope="col-md-1"></td>
			</tr>
		</thead>
	</table>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>reports/warehouses.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	// }
	// else{
		// header('Location:../../index.php');
		// exit();
	// }
	ob_end_flush();
?>