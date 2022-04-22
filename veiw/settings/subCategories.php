<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'Dashboard';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/subCategories.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM services WHERE parent_id <=> NULL");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<h3>الاصناف الفرعية</h3>
	<div class="sitting">
		<form class="needs-validation" id="subCategory" novalidate>
			<div class="form-row">
				<div class="col-md-12" style="color:#dcdcdc">-</div>
				<div class="col-md-2 mb-4">
					<label for="parent">نوع الصنف</label>
					<select class="form-control" id="parent" required>
						<option selected disabled value="">نوع الصنف</option>
						<?php
							foreach($rows as $row){
							?>
							<option value="<?=$row['id']?>"><?=$row['name']?></option>
							<?php
							}
						?>
					</select>
					<div class="invalid-feedback">إختار الصنف من فضلك</div>
				</div>
				<div class="col-md-2 mb-4">
					<label for="name">اسم الصنف</label>
					<input type="text" class="form-control" placeholder="اكتب اسم الصنف" required  id="name">
					<div class="invalid-feedback">أدخل اسم الصنف من فضلك</div>
				</div>
				<div class="col-md-3 mb-1">
					<label for="quantity">الكمية</label>
					<input type="number" class="form-control" value=""placeholder="إختياري" id="quantity">
					<div class="invalid-feedback">الكمية اكبر من الموجود في المخازن</div>
				</div>
				<div class="col-md-1 mb-4">
					<label style="color:#dcdcdc;">-</label>
					<button class="form-control btn btn-primary" type="submit">اضافة</button>
				</div>
			</div>
		</form>
		<table class="table tableStyle1">
			<thead class="tableStyle">
				<tr>
					<th scope="col-1">#</th>
					<th scope="col-2">اسم الصنف</th>
					<th scope="col-2">سعر العميل</th>
					<th scope="col-2">سعر الزميل</th>
					<th scope="col-2">سعر العامل</th>
					<th scope="col-2">الكمية</th>
					<th scope="col-1">التحكم</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<div class="col-md-12" style="color:#dcdcdc">-</div>
	</div>
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