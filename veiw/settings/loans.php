<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'loans';
		$getH3     = 'سلفيات';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/loans.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM loans ORDER BY id ASC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" id = "loans" novalidate>
		<div class="col-4 mb-2 mt-4">
			<label for="loan" class="form-label">اسم المستلف</label>
			<select class="form-control" id="loan" required>
				<option disabled selected value="">إختار المستلف</option>
				<?php
					foreach($rows as $row){
					?>
					<option value=<?=$row['id']?>><?=$row['name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">إختار المستلف من فضلك</div>
		</div>
		<div class="col-3 mb-2 mt-4">
			<label for="amount" class="form-label">المبلغ</label>
			<input type="number" class="form-control" value="" id="amount" required min=1>
			<div class="invalid-feedback">إدخل المبلغ من فضلك</div>
		</div>
		<div class="col-1 mb-2 mt-4">
			<label style="color:#dcdcdc;"class="form-label">-</label>
			<button type="submit" class="form-control btn btn-primary">+</button>
		</div>
	</form>
	
	<table class='table' style="width:96%;">
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1 mb-2">الأسم</th>
				<th scope="col-1 mb-2">السلفة</th>
				<th scope="col-1 mb-2">خذف</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<form class="row" id="dataTable">
		<div id="save" class="col-1 mb-2 mt-4">
			<label style="color:#dcdcdc;"class="form-label">-</label>
			<button type="submit" class="form-control btn btn-primary">حفظ</button>
		</div>
	</form>
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