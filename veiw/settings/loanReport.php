<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'loan report';
		$getH3     = 'تقرير السلفيات';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/loanReport.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM loans WHERE Lending != 0 ORDER BY id DESC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
		
		$Lending = array_sum(array_map(function($item) {
			return intval($item['Lending']);
		}, $rows));
		
		$repayment = array_sum(array_map(function($item) {
			return intval($item['repayment']);
		}, $rows));
		$total = intval($Lending) - intval($repayment)
	?>
	<table class='table table-bordered'>
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1 mb-2">الأسم</th>
				<th scope="col-1 mb-2">السلفة</th>
				<th scope="col-1 mb-2">المسدد</th>
				<th scope="col-1 mb-2">المتبقي</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($rows as $row){
				?>
				<tr>
					<td scope="col-1 mb-2"><?=$row['name']?></td>
					<td scope="col-1 mb-2"><?=$row['Lending']?></td>
					<td scope="col-1 mb-2"><?=$row['repayment']?></td>
					<td scope="col-1 mb-2"><?=intval($row['Lending']) -intval($row['repayment'])?></td>
				</tr>
				<?php
				}
			?>
		</tbody>
		<thead class= 'table-dark'>
			<tr>
				<td scope="col-1">الأجمالي</td>
				<td scope="col-1"><?=$Lending?></td>
				<td scope="col-1"><?=$repayment?></td>
				<td scope="col-1"><?=$total?></td>
			</tr>
		</thead>
	</table>
	<button type="submit" class="mt-4 btn btn-primary">طباعة</button>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	// }
	// else{
	// 	header('Location:../../index.php');
	// 	exit();
	// }
	ob_end_flush();
?>