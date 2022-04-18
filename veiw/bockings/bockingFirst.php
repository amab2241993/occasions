<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'bockings first';
		$getH3 = 'حجوزات مبدائية';
		include '../../init.php';
		?><script src="<?php echo $controller ?>bockings/bockingFirst.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM accounts WHERE parent_id = 5");
		$stmt->execute();
		$accounts = $stmt->fetchAll();
		if(isset($_GET['date'])){
			$stmt = $con->prepare(
				"SELECT bills.* , customers.name AS customer_name , main.id AS counter FROM bills
				 INNER JOIN customers ON bills.customer_id =customers.id INNER JOIN main ON bills.main_id = main.id
				 WHERE bills.status = ? AND bills.bill_date = ? ORDER BY bills.id DESC"
			);
			$stmt->execute(array(1,$_GET['date']));
			$rows = $stmt->fetchAll();
		}
		else{
			$stmt = $con->prepare(
				"SELECT bills.* , customers.name AS customer_name , main.id AS counter FROM bills
				 INNER JOIN customers ON bills.customer_id =customers.id INNER JOIN main ON bills.main_id = main.id
				 WHERE bills.status = 1 ORDER BY bills.id DESC"
			);
			$stmt->execute();
			$rows = $stmt->fetchAll();
		}
		/* Start Dashboard Page */
	?>
	<table id="first" class="table table-striped table-bordered">
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1">رقم الحجز</th>
				<th scope="col-2">تاريخ الحجز</th>
				<th scope="col-2">اسم العميل</th>
				<th scope="col-1">عدد الأيام</th>
				<th scope="col-2">المبلغ الكلي</th>
				<th scope="col-1">الخصم</th>
				<th scope="col-2">المبلغ النهائى</th>
				<th scope="col-1">التحكم</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if (! empty($rows)){
					$count = 0;
					foreach($rows as $row){
						++$count
					?>
					<tr>
						<td scope="col-1">
							<a href=<?="bockingUpdate.php?bill=".$row['code']."&&status=1"?>><?= $row['code']; ?></a>
						</td>
						<td scope="col-2"><?= $row['bill_date']; ?></td>
						<td scope="col-2"><?= $row['customer_name']; ?></td>
						<td scope="col-1"><?= $row['num_days']; ?></td>
						<td scope="col-2"><?= $row['total_price']; ?></td>
						<td scope="col-1"><?= $row['discount']; ?></td>
						<td scope="col-2"><?= $row['price']; ?></td>
						<td scope="col-1" id=<?=$row['counter']?> code=<?=$row['code']?> status=<?=$row['status']?>>
							<button type='button' data-toggle='modal' data-target='<?="#ex".$count?>'>تأكيد</button>
							<i class='fa fa-remove deleteBocking'>
						</td>
					</tr>
					<?php
					}
				}
			?>
		</tbody>
	</table>
	<?php
		$index = 1;
		if (! empty($rows)){
			foreach($rows as $row){
			?>
			<!-- ******************** model **************************** -->
			<div class="modal fade" id='<?="ex".$index?>' tabindex="-1" role="dialog" aria-labelledby=<?="ex".$index."Label"?> aria-hidden="true">
				<div class="modal-dialog"  role="document">
					<div class="modal-content">
						<div class="modal-body row">
							<form class="row g-3 needs-validation" name="tester[]"id=<?="uncertain".$row["id"].""?> novalidate>
								<input type="hidden" id=<?="res".$row["id"].""?> value=<?=$row["id"];?>>
								<input type="hidden" id=<?="date".$row["id"].""?> value=<?=$row["bill_date"];?>>
								<input type="hidden" id=<?="main".$row["id"].""?> value=<?= $row["counter"];?>>
								<div class="col-5 mt-2"><h3>عملية دفع</h3></div>
								<div class="col-5 mt-2"></div>
								<div class="col-1 mt-2">
									<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="col-9 mt-2">
									<label for="<?='type'.$row['id']?>" class="form-label">نوع الدفع</label>
									<select class="form-control"  id="<?='type'.$row['id']?>" required>
										<option selected disabled value=""></option>
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
								<div class="col-3 mt-2">
									<label for="id" class="form-label">المبلغ المدفوع</label>
									<span><?=$row['price']?></span>
								</div>
								<div class="col-9 mt-2">
									<label for="<?='price'.$row['id']?>" class="form-label">المبلغ</label>
									<input type="number" class="form-control" id="<?='price'.$row['id']?>" required min=1 max="<?=$row['price']?>">
									<div class="invalid-feedback">إدخل المبلغ المطلوب من فضلك</div>
								</div>
								<div class="col-3 mt-2"></div>
								<div class="col-9 mt-2">
									<label for="<?='typeM'.$row['id']?>" class="form-label">نوع الدفع المصروفات</label>
									<select class="form-control"  id="<?='typeM'.$row['id']?>" required>
										<option selected disabled value=""></option>
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
								<div class="col-4 mt-2 mb-2">
									<button type="submit" id="click[]" value = "<?=$row['id']?>" class="btn btn-primary">حفظ البيانات</button>
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
				$index++;
			}
		}
	?>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='passwordInter' tabindex="-1" role="dialog" aria-labelledby="passwordInterLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id= 'passowrdForm' novalidate>
						<input type="hidden" id="mainId" value="">
						<input type="hidden" id="code" value="">
						<input type="hidden" id="status" value="">
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
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>