<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'Dashboard';
		include 'init.php';
		$kh = 0;
		$bank = 0;
		$kh1 = 0;
		$bank1 = 0;
		$stmt = $con->prepare(
			"SELECT sum(debtor) as debtor ,sum(creditor) as creditor FROM move_line WHERE account_id = ?"
		);
		$stmt->execute(array(2));
		if($data = $stmt->fetch(PDO::FETCH_ASSOC)){
			$kh =  $data['creditor'];
			$kh1 =  $data['debtor'];
		}

		$stmt1 = $con->prepare(
			"SELECT sum(debtor) as debtor ,sum(creditor) as creditor FROM move_line WHERE account_id = ?"
		);
		$stmt1->execute(array(3));

		$stmt2 = $con->prepare(
			"SELECT debtor , creditor FROM move_line WHERE account_id != ? AND account_id != ?"
		);
		$stmt2->execute(array(3 , 2));
		$rows = $stmt2->fetchAll();

		if($data = $stmt1->fetch(PDO::FETCH_ASSOC)){
			$bank =  $data['creditor'];
			$bank1 = $data['debtor'];
		}
		/* Start Dashboard Page */
	?>
	<h3>تقارير الأرباح</h3>
	<table class="table tableStyle1">
		<thead class= 'tableStyle'>
			<tr>
				<th scope="col">#</th>
				<th scope="col">الأيرادات</th>
				<th scope="col">الصروفات</th>
				<th scope="col">صافي الأرباح</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$count = 0;
				foreach($rows as $row){
				?>
				<tr>
					<th><?=++$count?></th>
					<th><?=$row['debtor']?></th>
					<th><?=$row['creditor']?></th>
					<th></th>
				</tr>
				<?php
				}
			?>
		</tbody>
		<thead class= 'table-dark'>
			<tr>
				<th scope="col">الأجمالي</th>
				<th scope="col"><?= ($kh + $bank) ?></th>
				<th scope="col"><?= ($kh1 + $bank1) ?></th>
				<th scope="col"><?= (($kh + $bank) - ($kh1 + $bank1)) ?></th>
			</tr>
		</thead>
	</table>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>masrofat.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location: index.php');
		exit();
	}
	ob_end_flush();
?>