<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'fixed expenses';
		$getH3 = 'تقرير المصروفات ثابتة';
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
				"SELECT move.statment , move.account_id as accountMove ,
				 accounts.name , move_line.account_id as accId , move_line.creditor ,
				 move_line.created_at FROM move
				 INNER JOIN move_line ON move.id = move_line.move_id
				 INNER JOIN accounts ON move_line.account_id = accounts.id
				 WHERE move_line.created_at >= ? AND move_line.created_at <= ? AND move.account_id = ?"
			);
			$stmt->execute(array($date2 , $date1 , 14));
			$moves = $stmt->fetchAll();
		}
		else{
			$date = date('Y-m');
			$timestamp = strtotime($date);
			$year = date('Y', $timestamp);
			$month = date('m', $timestamp);
			$stmt = $con->prepare(
				"SELECT move.statment , move.account_id as accountMove ,
				 accounts.name , move_line.account_id as accId , move_line.creditor ,
				 move_line.created_at FROM move
				 INNER JOIN move_line ON move.id = move_line.move_id
				 INNER JOIN accounts ON move_line.account_id = accounts.id
				 WHERE month(move_line.created_at) = ? AND year(move_line.created_at) = ?
				 AND move.account_id = ?"
			);
			$stmt->execute(array($month , $year , 14));
			$moves = $stmt->fetchAll();
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
		$creditor = array_sum(array_map(function($item) {
			if($item['accId'] != 6 && $item['accId'] != 7){
				return intval($item['creditor']);
			}
		}, $moves));
	?>
	<table id="fixed" class="table table-striped table-bordered">
		<thead class= 'tableStyle'>
			<tr>
				<th scope="col-1">التاريخ</th>
				<th scope="col-2">البند الرئيسي</th>
				<th scope="col-2">البند الفرعي</th>
				<th scope="col-1">البيان</th>
				<th scope="col-1">سحب</th>
				<th scope="col-1">حذف</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach($moves as $move){
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
					<td scope="col-md-1"><?=$move['creditor']?></td>
					<td scope="col-md-1"></td>
				</tr>
				<?php
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
				<td scope="col-md-1"><?=$creditor?></td>
				<td scope="col-md-1"></td>
			</tr>
		</thead>
	</table>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>reports/fixed.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	// }
	// else{
		// header('Location:../../index.php');
		// exit();
	// }
	ob_end_flush();
?>