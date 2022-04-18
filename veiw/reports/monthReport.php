<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'month report';
		$getH3 = 'التقرير الشهرية';
		include '../../init.php';
		?><script src="<?php echo $controller ?>reports/monthReport.js"></script><?php
		include $tpl . 'navbar.php';
		$rows      = NULL ;
		$price     = 0 ;
		$amount    = 0 ;
		$total     = 0 ;
		$relay     = 0 ;
		$customer  = 0 ;
		$companion = 0 ;
		$all       = 0 ;
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
				"SELECT bills.price , bills.code as coder , move.price  as pri , bill_expense.*
				 FROM bills
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 WHERE bills.bill_date >= ? AND bills.bill_date <= ? ORDER BY bills.code DESC"
			);
			$stmt1 = $con->prepare(
				"SELECT sum(bills.price) as price ,
				 sum(move.price) as amount ,
				 sum(bill_expense.total) as total
				 FROM bills
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 WHERE bills.bill_date >= ? AND bills.bill_date <= ?"
			);
			$stmt->execute(array($date2,$date1));
			$rows = $stmt->fetchAll();
			$stmt1->execute(array($date2,$date1));
			if($data = $stmt1->fetch(PDO::FETCH_ASSOC)){
				$price  = $data['price'];
				$amount = $data['amount'];
				$total  = $data['total'];
			}
		}
		else{
			$date = date('Y-m');
			$timestamp = strtotime($date);
			$year = date('Y', $timestamp);
			$month = date('m', $timestamp);
			$stmt = $con->prepare(
				"SELECT bills.price , bills.code as coder , move.price  as pri , bill_expense.*
				 FROM bills
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 WHERE month(bill_date) = ? AND year(bill_date) = ? ORDER BY bills.code DESC"
			);
			$stmt1 = $con->prepare(
				"SELECT sum(bills.price) as price ,
				 sum(move.price) as amount ,
				 sum(bill_expense.total) as total
				 FROM bills
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id
				 WHERE month(bill_date) = ? AND year(bill_date) = ?"
			);
			$stmt->execute(array($month,$year));
			$rows = $stmt->fetchAll();
			$stmt1->execute(array($month,$year));
			if($data = $stmt1->fetch(PDO::FETCH_ASSOC)){
				$price  = $data['price'];
				$amount = $data['amount'];
				$total  = $data['total'];
			}
		}
		/* Start Dashboard Page */
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
		if($rows){
		?>
		<table id="first" class="table table-striped table-bordered">
			<thead class= 'tableStyle'>
				<tr>
					<th scope="col-1">الفاتورة</th>
					<th scope="col-1">المبلغ</th>
					<th scope="col-1">المدفوع</th>
					<th scope="col-1">المتبقي</th>
					<th scope="col-1">ترحيل</th>
					<th scope="col-1">عمال</th>
					<th scope="col-1">زملاء</th>
					<th scope="col-1">خدمات</th>
					<th scope="col-1">الجملة</th>
					<th scope="col-1">الارباح</th>
					<th scope="col-1">الحزنة</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($rows as $row ){
						$total1    =  ($row['tent'] + $row['decoration']);
						$total1    += ($row['electricity'] + $row['service']);
						$total2    =  ($row['incentives'] + $row['administrative']);
						$total2    += ($row['admin'] + $row['warehouse'] + $row['driver']);
						$relay     += ($row['relay_in'] + $row['relay_out']) ;
						$customer  += $total1 ;
						$companion += $row['companion'] ;
						$all       += $total2 ;
					?>
					<tr>
						<th scope="col-1"><?=$row['coder']?></th>
						<th scope="col-1"><?=$row['price']?></th>
						<th scope="col-1"><?=$row['pri']?></th>
						<th scope="col-1"><?=($row['price'] - $row['pri'])?></th>
						<th scope="col-1"><?=($row['relay_in'] + $row['relay_out'])?></th>
						<th scope="col-1"><?=$total1?></th>
						<th scope="col-1"><?=$row['companion']?></th>
						<th scope="col-1"><?=$total2?></th>
						<th scope="col-1"><?=$row['total']?></th>
						<th scope="col-1"><?=($row['price'] - $row['total'])?></th>
						<th scope="col-1"><?=($row['pri'] - $row['total'])?></th>
					</tr>
					<?php
					}
				?>
			</tboby>
			<thead class= 'table-dark'>
				<tr>
					<th scope="col-1">الأجمالي</th>
					<th scope="col-1"><?=$price?></th>
					<th scope="col-1"><?=$amount?></th>
					<th scope="col-1"><?=($price - $amount)?></th>
					<th scope="col-1"><?=$relay?></th>
					<th scope="col-1"><?=$customer?></th>
					<th scope="col-1"><?=$companion?></th>
					<th scope="col-1"><?=$all?></th>
					<th scope="col-1"><?=$total?></th>
					<th scope="col-1"><?=($price - $total)?></th>
					<th scope="col-1"><?=($amount - $total)?></th>
				</tr>
			</thead>
		</table>
		<?php
		}
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>