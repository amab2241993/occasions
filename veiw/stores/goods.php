<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])){
		$pageTitle = 'goods';
		$getH3     = 'بضاعة أول مدة';
		include '../../init.php';
		?><script src="<?php echo $controller ?>stores/goods.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM stores ORDER BY id ASC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" id = "goods" novalidate>
		<div class="col-3 mb-2 mt-4">
			<label for="stores" class="form-label">المخزن</label>
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
		<div class="col-2 mb-2 mt-4">
			<label for="item" class="form-label">إختار الصنف</label>
			<select class="form-control" disabled id="item" required></select>
			<div class="invalid-feedback">إختار الصنف من فضلك</div>
		</div>
		<div class="col-2 mb-2 mt-4">
			<label for="calculation" class="form-label">نوع العملية</label>
			<select class="form-control" id="calculation">
				<option value=1 selected>إضافة</option>
				<option value=2>نقص</option>
			</select>
		</div>
		<div class="col-2 mb-2 mt-4">
			<label for="quantity" class="form-label">الكمية</label>
			<input type="number" disabled class="form-control" value="" id="quantity" required max=0 min=1>
			<div class="invalid-feedback">إدخل الكمية من فضلك</div>
		</div>
		<div class="col-1 mb-2 mt-4">
			<label style="color:#dcdcdc;"class="form-label">-</label>
			<button type="submit" class="form-control btn btn-primary">+</button>
		</div>
	</form>
	
	<table class='table' style="width:96%;">
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1 mb-2"> الصنف</th>
				<th scope="col-1 mb-2">نوع العملية</th>
				<th scope="col-1 mb-2">الكمية</th>
				<th scope="col-1 mb-2">الكمية الكلية</th>
				<th scope="col-1 mb-2">الكمية الجديد</th>
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
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>