<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'employees salaries';
		$getH3     = "تسجيل مرتبات الموظفين";
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/employeeSalary.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare(
			"SELECT name , id FROM employees WHERE salary = 0 ORDER BY id DESC"
		);
		$stmt->execute();
		$employees = $stmt->fetchAll();
		$stmt = $con->prepare(
			"SELECT salary , name , id FROM employees WHERE salary != 0 ORDER BY id DESC"
		);
		$stmt->execute();
		$salaries = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="row needs-validation" id="employees" novalidate>
		<div class="col-2 mb-3">
			<label for="employee">اسم الموظف</label>
			<select class="form-control" required id="employee">
				<?php
					foreach ($employees as $key => $value) {
					?>
					<option value=<?=$value['id']?>><?=$value['name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">
				إختار الإسم الموظف من فضلك
			</div>
		</div>
		<div class="col-2 mb-3">
			<label for="salary">قيمة المرتب</label>
			<input type="text" class="form-control" placeholder="أدخل قيمة الراتب" required  id="salary">
			<div class="invalid-feedback">
				أدخل قيمة الراتب من فضلك
			</div>
		</div>
		<div class="col-1 mb-1">
			<label style="color:#dcdcdc;">-</label>
			<button class="form-control btn btn-primary" type="submit">اضافة</button>
		</div>
	</form>
	<table class="table">
		<thead class="tableStyle">
			<tr>
				<th scope="col-2">الإسم</th>
				<th scope="col-2">المرتب</th>
				<th scope="col-2">تحكم</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if (! empty($salaries)){
					$count= 0;
					foreach($salaries as $salary){
					?>
					<tr salary="<?=$salary['salary']?>" name="<?=$salary['name']?>">
						<td><?= $salary['name']; ?></td>
						<td><?= $salary['salary']; ?></td>
						<td class="col-2" style="font-size:20px">
							<i class='fa fa-edit edit pl-2'id="<?=$salary['id']?>"></i>
							<i class='fa fa-remove remove pl-2' id="<?=$salary['id']?>"></i>
						</td>
					</tr>
					<?php
					}
				}
			?>
		</tbody>
	</table>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='update' tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='updateForm' novalidate>
						<input type="hidden" id="employeeId">
						<div class="col-5 mt-2"><h3>تعديل البيانات</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-1 mt-2"></div>
						<div class="col-9 mt-2">
							<label for="employeeN">اسم الموظف</label>
							<input type="text" class="form-control" id="employeeN" disabled>
							<div class="invalid-feedback">
								اكتب الاسم من فضلك
							</div>
						</div>
						<div class="col-3 mt-2"></div>
						<div class="col-9 mt-2">
							<label for="employeeP">إدخل المرتب الجديد</label>
							<input type="text" class="form-control" required  id="salaryNew">
							<div class="invalid-feedback">
								أدخل المرتب من فضلك
							</div>
						</div>
						<div class="col-3 mt-2"></div>
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
						<input type="hidden" id="employee_id">
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