<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'profits';
		$getH3 = 'بند الأرباح الشهرية';
		include '../../init.php';
		$price    = 0;
		$amount   = 0;
		$total    = 0;
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
				"SELECT move.account_id , move_line.creditor FROM move
				 INNER JOIN move_line ON move.id = move_line.move_id
				 WHERE move_line.created_at >= ? AND move_line.created_at <= ?
				 AND move.account_id != 3
				 ORDER BY move_line.id DESC"
			);
			$stmt->execute(array($date2,$date1));
			$move = $stmt->fetchAll();
			
			$stmt = $con->prepare(
				"SELECT sum(bills.price) as price , sum(move.price) as amount , 
				 sum(bill_expense.total) as total
				 FROM bills INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 WHERE bills.bill_date >= ? AND bills.bill_date <= ?"
			);
			$stmt->execute(array($date2,$date1));
			if($data = $stmt->fetch(PDO::FETCH_ASSOC)){
				$price  = $data['price'];
				$amount = $data['amount'];
				$total  = $data['total'];
			}
			
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
				"SELECT move.account_id , move_line.creditor FROM move
				 INNER JOIN move_line ON move.id = move_line.move_id
				 WHERE month(move_line.created_at) = ? AND year(move_line.created_at) = ?
				 AND move.account_id != 3 ORDER BY move_line.id DESC"
			);
			$stmt->execute(array($month,$year));
			$move = $stmt->fetchAll();
			
			$stmt = $con->prepare(
				"SELECT sum(bills.price) as price , sum(move.price) as amount , 
				 sum(bill_expense.total) as total
				 FROM bills INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 WHERE month(bills.bill_date) = ? AND year(bills.bill_date) = ?"
			);
			$stmt->execute(array($month,$year));
			if($data = $stmt->fetch(PDO::FETCH_ASSOC)){
				$price  = $data['price'];
				$amount = $data['amount'];
				$total  = $data['total'];
			}
			
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
		if($move){
			$general = array_sum(array_map(function($item) {
				if($item['account_id'] == 13)
				return intval($item['creditor']); 
			}, $move));
			$fixed = array_sum(array_map(function($item) {
				if($item['account_id'] == 14)
				return intval($item['creditor']); 
			}, $move));
			$profitOld = intval($price) - (intval($total) + intval($discount));
			$profits   = $profitOld - (intval($general) + intval($fixed));
			$debts     = (intval($discount) + intval($price)) - intval($amount);
			$profitNew = $profits - $debts;
			$result3   = intval(($profits*35)/100);
			$result4   = intval(($profits*25)/100);
			$result5   = intval(($profits*5)/100);
			$result6   = intval(($profitNew*35)/100);
			$result7   = intval(($profitNew*25)/100);
			$result8   = intval(($profitNew*5)/100);
		?>
		<table id="profits" class="table table-striped table-bordered">
			<thead class= 'tableStyle'>
				<tr>
					<th scope="col-4">المبلغ</th>
					<th scope="col-4">البيان</th>
					<th scope="col-4">الملاحظات</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?=$price?></td>
					<td>جملة ايرادات الفواتير</td>
					<td></td>
				</tr>
				<tr>
					<td><?=($total)?></td>
					<td>مصروفات الفواتير</td>
					<td></td>
				</tr>
				<tr>
					<td><?=($discount)?></td>
					<td>مردودات الفواتير</td>
					<td></td>
				</tr>
				<tr>
					<td><?=$profitOld?></td>
					<td>صافي الارباح</td>
					<td></td>
				</tr>
				<tr>
					<td><?=$general?></td>
					<td>المصروفات الثابتة</td>
					<td></td>
				</tr>
				<tr>
					<td><?=$fixed?></td>
					<td>المصروفات العامة</td>
					<td></td>
				</tr>
				<tr>
					<td><?=intval($general) + intval($fixed)?></td>
					<td>جملة المصروفات</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<?=intval($profits)?>
					</td>
					<td>جملة الارباح</td>
					<td>100%</td>
				</tr>
				<tr>
					<td><?=$debts?></td>
					<td>جملة المديونيات</td>
					<td></td>
				</tr>
				<tr>
					<td><?=$profitNew?></td>
					<td>جملة الارباح بعد المديونيات	</td>
					<td></td>
				</tr>
				<tr>
					<td><?=$result6?></td>
					<td>35%</td>
					<td>نور الدائم</td>
				</tr>
				<tr>
					<td><?=$result6?></td>
					<td>35%</td>
					<td>سيد احمد</td>
				</tr>
				<tr>
					<td><?=$result7?></td>
					<td>25%</td>
					<td>المستودع</td>
				</tr>
				<tr>
					<td><?=$result8?></td>
					<td>5%</td>
					<td>المسؤلية المجتمعية</td>
				</tr>
			</tboby>
			<thead class= 'table-dark'>
				<tr>
				</tr>
			</thead>
		</table>
		<?php
		}
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