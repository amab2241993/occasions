<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'Dashboard';
		$getH3     = 'دفترة اليوميات';
		include '../../init.php';
		?><script src="<?php echo $controller ?>ledgers/ledger.js"></script><?php
		include $tpl . 'navbar.php';
		$rows 	   = NULL;
		$creditor  = 0;
		$debtor    = 0;
		$creditor1 = 0;
		$debtor1   = 0;
		$names     = 0;
		
		$stmt = $con->prepare(
			"SELECT
			 move.statment , move.account_id as accountMove ,
			 move_line.* ,
			 accounts.name
			 FROM move
			 INNER JOIN move_line ON move.id = move_line.move_id
			 INNER JOIN accounts ON move_line.account_id = accounts.id ORDER BY move_line.id DESC"
		);
		$stmt1 = $con->prepare(
			"SELECT sum(debtor) as debtor ,  sum(creditor) as creditor FROM move INNER JOIN
			 move_line ON move.id = move_line.move_id WHERE move_line.account_id = ?"
		);
		$stmt2 = $con->prepare(
			"SELECT sum(debtor) as debtor ,  sum(creditor) as creditor FROM move INNER JOIN
			 move_line ON move.id = move_line.move_id WHERE move_line.account_id = ?"
		);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$stmt1->execute(array(6));
		if($data = $stmt1->fetch(PDO::FETCH_ASSOC)){
			$debtor   = $data['debtor'];
			$creditor = $data['creditor'];
		}
		$stmt2->execute(array(7));
		if($data = $stmt2->fetch(PDO::FETCH_ASSOC)){
			$debtor1   = $data['debtor'];
			$creditor1 = $data['creditor'];
		}
		/* Start Dashboard Page */
	?>
	<form class="row g-3 needs-validation" novalidate action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="col-3">
			<input type="date" class="form-control date1" name="date1" required>
		</div>
		<div class="col-3">
			<input type="date" class="form-control date2" name="date2" required>
		</div>
		<div class="col-1">
			<button type="submit" class="btn btn-success" style="margin-bottom:10px">بحث</button>
		</div>
	</form>
	<table id="first" class="table table-striped table-bordered">
		<thead class= 'tableStyle'>
			<tr>
				<th scope="col-md-1">التاريخ</th>
				<th scope="col-md-2">البند الرئيسى</th>
				<th scope="col-md-2"> البند الفرعي</th>
				<th scope="col-md-2">البيان</th>
				<th scope="col-md-1">إيداع خزنة</th>
				<th scope="col-md-1">إيداع بنك</th>
				<th scope="col-md-1">سحب خزنة</th>
				<th scope="col-md-1">سحب بنك</th>
				<th scope="col-md-1">الرصيد</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$index = 0;
				foreach($rows as $row){
					if($index % 2 == 0){
						echo "<tr>";
					}
					if($row['account_id'] != 6 && $row['account_id'] != 7){
						$stmt = $con->prepare("SELECT name FROM accounts WHERE id = ? LIMIT 1");
						$stmt->execute(array($row['accountMove']));
						$accountMove = $stmt->fetch();
						$createDate = new DateTime($row['created_at']);
						$strip = $createDate->format('Y-m-d');
					?>
					<td scope="col-md-1"><?=$strip?></td>
					<td scope="col-md-2"><?=$accountMove['name']?></td>
					<td scope="col-md-2"><?=$row['name']?></td>
					<td scope="col-md-2"><?=$row['statment']?></td>
					<?php
						$names=0;
						if($row['account_id'] == 12 || $row['account_id'] == 8){
							$names = 1;
						}
						else{
							$names = 2;
						}
					}
					else{
						if($row['account_id'] == 6){
							if($names == 1){
							?>
							<td scope="col-md-1"><?=$row['creditor']?></td>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"></td>
							<?php
							}
							else{
							?>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"><?=$row['debtor']?></td>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"></td>
							<?php
							}
						}
						else{
							if($names == 1){
							?>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"><?=$row['creditor']?></td>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"></td>
							<td scope="col-md-1"></td>
							<?php
							}
							else{
							?>
							<td scope="col-1"></td>
							<td scope="col-1"></td>
							<td scope="col-1"></td>
							<td scope="col-1"><?=$row['debtor']?></td>
							<td scope="col-1"></td>
							<?php
							}
						}
					}
					$index++;
					if($index % 2 == 0){
						echo "</tr>";
					}
				}
			?>	
		</tboby>
		<thead class= 'table-dark'>
			<tr>
				<th scope="col-1">الأجمالي</th>
				<th scope="col-2"></th>
				<th scope="col-2"></th>
				<th scope="col-2"></th>
				<th scope="col-1"><?=$creditor?></th>
				<th scope="col-1"><?=$creditor1?></th>
				<th scope="col-1"><?=$debtor?></th>
				<th scope="col-1"><?=$debtor1?></th>
				<th scope="col-1"><?=($creditor + $creditor1) - ($debtor + $debtor1)?></th>
			</tr>
		</thead>
	</table>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>