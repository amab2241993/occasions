<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'sales';
		$getH3     = 'المبيعات';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/sales.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM stores ORDER BY id ASC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$stmt = $con->prepare("SELECT id , name FROM accounts WHERE parent_id = 5");
		$stmt->execute();
		$accounts = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" id = "sales" novalidate>
		<div class="col-2 mt-2">
			<label for="count" class="form-label">إختار المخزن</label>
			<select class="form-control" id="stores" required>
				<option disabled selected value="">إختار المخزن</option>
				<?php
					foreach($rows as $row){
					?>
					<option value=<?=$row['id']?>><?=$row['name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">إختار المخزن من فضلك</div>
		</div>
		<div class="col-2 mt-2">
			<label for="item" class="form-label">إختار الصنف</label>
			<select class="form-control" disabled id="item" required></select>
			<div class="invalid-feedback">إختار النوع من فضلك</div>
		</div>
		<div class="col-2 mt-2">
			<label for="type" class="form-label">نوع الدفع</label>
			<select class="form-control"  id="type" required>
				<option selected disabled value="">نوع الدفع</option>
				<?php
					foreach($accounts as $account){
					?>
					<option value=<?=$account['id']?>><?=$account['name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">إختار طريقة الدفع من فضلك</div>
		</div>
		<div class="col-2 mt-2">
			<label for="quantity" class="form-label">الكمية</label>
			<input type="number" disabled class="form-control" value="" id="quantity" required min=1>
			<div class="invalid-feedback" id = 'invalids'></div>
		</div>
		<div class="col-2 mt-2">
			<label for="price" class="form-label">السعر</label>
			<input type="number" disabled class="form-control" id="price" required>
			<div class="invalid-feedback">إدخل السعر من فضلك</div>
		</div>
		<div class="col-2 mt-2">
			<label for="statment" class="form-label">البيان</label>
			<input type="text" class="form-control" id="statment">
		</div>
		<div class="col-1 mb-2 mt-2">
			<label style="color:#dcdcdc;"class="form-label">-</label>
			<button type="submit" class="form-control btn btn-primary">إضافة</button>
		</div>
	</form>
	
	<table class='table' style="width:96%;">
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1 mb-2"> الصنف</th>
				<th scope="col-1 mb-2">الكمية</th>
				<th scope="col-1 mb-2">السعر</th>
				<th scope="col-1 mb-2">خذف</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<form class="row" id="dataTable">
		<div id="save" class="col-1 mt-2">
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