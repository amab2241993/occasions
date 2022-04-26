<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'permissions';
		$getH3     = "صلاحيات المستخدم";
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/permissions.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM users ORDER BY id DESC");
		$stmt->execute();
		$users = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="row needs-validation" id="permissions" novalidate>
		<div class="col-3 mb-3">
			<select class="form-control" required id="user">
				<option selected disabled value="">إختار مستخدم</option>
				<?php
					foreach($users as $user){
					?>
					<option value="<?=$user['id']?>"><?=$user['user_name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">
				اكتب الاسم من فضلك
			</div>
		</div>
		<div class="col-3 mb-3">
			<select class="form-control" disabled required id="permission">
			</select>
			<div class="invalid-feedback">
				إختار الصلاحية من فضلك
			</div>
		</div>
		<div class="col-1 mb-1">
			<button class="form-control btn btn-primary" type="submit">اضافة</button>
		</div>
		<div class="col-2 mb-1">
			<button class="form-control btn btn-primary" type="submit">اضافة الكل</button>
		</div>
	</form>
	<table class="table">
		<thead class="tableStyle">
			<tr>
				<th scope="col-2">اسم الصلاحية</th>
				<th scope="col-2">حذف</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='passwordInter' tabindex="-1" role="dialog" aria-labelledby="passwordInterLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='passowrdForm' novalidate>
						<input type="hidden" id="customer_id">
						<div class="col-5 mt-2"><h3>إدخل كلمة السر</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-10 mt-2">
							<label for='password' class="form-label">كلمة المرور</label>
							<input type="password" class="form-control" id="password" required>
							<div class="invalid-feedback">كلمة المرور غير صحيحة</div>
						</div>
						<div class="col-2 mt-2"></div>
						<div class="col-4 mt-2 mb-2">
							<button type="submit" class="btn btn-primary">إرسال</button>
						</div>
						<div class="col-3 mt-2 mb-2"></div>
						<div class="col-4 mt-2 mb-2">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">قفل الصفحة</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	// }
	// else{
	// 	header('Location:../../index.php');
	// 	exit();
	// }
	ob_end_flush();
?>