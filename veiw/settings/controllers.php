<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'controllers';
		$getH3     = 'البنود';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/controllers.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM accounts WHERE parent_id = ?");
		$stmt->execute(array(4));
		$rows = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="needs-validation" id="controllers" novalidate method="post">
		<div class="form-row">
			<div class="col-md-4 mb-4">
				<label for="parent">البند</label>
				<select class="form-control" id="parent" required>
					<option selected disabled value="">إختار نوع البند</option>
					<?php
						foreach($rows as $row){
							if($row['id'] != 19){
							?>
							<option value="<?=$row['id']?>"><?=$row['name']?></option>
							<?php
							}
						}
					?>
				</select>
				<div class="invalid-feedback">
					إختار بند من فضلك
				</div>
			</div>
			<div class="col-md-4 mb-4">
				<label for="name">اكتب البند</label>
				<input type="text" class="form-control" placeholder="اكتب البند بالكامل" required  id="name">
				<div class="invalid-feedback">
					أدخل اسم البند من فضلك
				</div>
			</div>
			<div class="col-md-1 mb-4">
				<label style="color:#dcdcdc;">-</label>
				<button class="form-control btn btn-primary" type="submit">اضافة</button>
			</div>
		</div>
	</form>
	<table class="table">
		<thead class="tableStyle">
			<tr>
				<th scope="col-1">الرقم المتسلسل</th>
				<th scope="col-1">اسم المستخدم</th>
				<th scope="col-1">التحكم</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='update' tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='updateForm' novalidate>
						<input type="hidden" id="accountId">
						<input type="hidden" id="parentId">
						<div class="col-5 mt-2"><h3>تعديل البيانات</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-1 mt-2"></div>
						<div class="col-10 mt-2">
							<label for="employeeN">إدخل البند</label>
							<input type="text" class="form-control" required id="accountName">
							<div class="invalid-feedback">
								اكتب البند من فضلك
							</div>
						</div>
						<div class="col-2 mt-2"></div>
						<div class="col-4 mt-2 mb-2">
							<button type="submit" class="btn btn-primary">تعديل</button>
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
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='passwordInter' tabindex="-1" role="dialog" aria-labelledby="passwordInterLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='passowrdForm' novalidate>
						<input type="hidden" id="accId">
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