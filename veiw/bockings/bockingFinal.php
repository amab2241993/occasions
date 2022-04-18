<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'bocking Final';
		$getH3 = 'حجوزات النهائى';
		include '../../init.php';
		$stmt = $con->prepare("SELECT id , name FROM accounts WHERE parent_id = 5");
		$stmt->execute();
		$accounts = $stmt->fetchAll();
		if(isset($_GET['date'])){
			$stmt = $con->prepare(
				"SELECT bills.* ,
				 customers.name AS customer_name ,
				 main.id AS counter ,
				 move.id AS move_id , move.price AS money 
				 FROM bills
				 INNER JOIN customers ON bills.customer_id =customers.id
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 WHERE bills.status = ? AND bills.bill_date = ? ORDER BY bills.code DESC"
			);
			$stmt->execute(array(2,$_GET['date']));
			$rows = $stmt->fetchAll();
		}
		else{
			$stmt = $con->prepare(
				"SELECT bills.* ,
				 customers.name AS customer_name ,
				 main.id AS counter ,
				 move.id AS move_id , move.price AS money
				 FROM bills
				 INNER JOIN customers ON bills.customer_id =customers.id
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 WHERE bills.status = 2 ORDER BY bills.code DESC"
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
					<th scope="col-1">تاريخ الحجز</th>
					<th scope="col-1">اسم العميل</th>
					<th scope="col-1">عدد الأيام</th>
					<th scope="col-2">المبلغ الكلي</th>
					<th scope="col-1">الخصم</th>
					<th scope="col-2">المبلغ النهائى</th>
					<th scope="col-1">المدفوع</th>
					<th scope="col-1">المتبقي</th>
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
								<a href=<?="bockingUpdate.php?bill=".$row['code']."&&status=2"?>><?= $row['code']; ?></a>
							</td>
							<td scope="col-1"><?= $row['bill_date']; ?></td>
							<td scope="col-1"><?= $row['customer_name']; ?></td>
							<td scope="col-1"><?= $row['num_days']; ?></td>
							<td scope="col-2"><?= $row['total_price']; ?></td>
							<td scope="col-1"><?= $row['discount']; ?></td>
							<td scope="col-2"><?= $row['price']; ?></td>
							<td scope="col-1"><?= $row['money']?></td>
							<td scope="col-1"><?= $row['price'] - $row['money']?></td>
							<?php
								if(intval($row['price']) - intval($row['money']) <= 0){
								?>
								<td scope="col-1" id=<?=$row['counter']?> code=<?=$row['code']?> status=<?=$row['status']?>>
									<i class='fa fa-remove deleteBocking'>
								</td>
								<?php
								}
								else{
								?>
								<td scope="col-1" id=<?=$row['counter']?> code=<?=$row['code']?> status=<?=$row['status']?>>
									<button type='button' data-toggle='modal' data-target='<?="#ex".$count?>'>أكمل الدفع</button>
									<i class='fa fa-remove deleteBocking'>
								</td>
								<?php
								}
							?>
						</tr>
						<?php
						}
					}
				?>
			</tbody>
		</table>
		<?php
		$index = 1;
		foreach($rows as $row){
		?>
		<!-- ******************** model **************************** -->
		<div class="modal fade" id='<?="ex".$index?>' tabindex="-1" role="dialog" aria-labelledby=<?="ex".$index."Label"?> aria-hidden="true">
			<div class="modal-dialog"  role="document">
				<div class="modal-content">
					<div class="modal-body row">
						<form class="row g-3 needs-validation" name="tester[]"id=<?="certain".$row["id"].""?> novalidate>
							<input type="hidden" id="<?='move'.$row['id']?>" value=<?= $row['move_id']; ?>>
							<input type="hidden" id="<?='money'.$row['id']?>" value=<?= $row['money']; ?>>
							<div class="col-md-5"><h3>عملية دفع</h3></div>
							<div class="col-md-5"></div>
							<div class="col-md-1">
								<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="col-md-12" style='color:white'>-</div>
							<div class="col-md-9">
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
							<div class="col-md-3">
								<label for="id" class="form-label">المبلغ المدفوع</label>
								<input type="text" disabled class="form-control" value=<?=$row['price'] - $row['money']?>>
							</div>
							<div class="col-md-9">
								<label for="<?='price'.$row['id']?>" class="form-label">المبلغ</label>
								<input type="number" class="form-control" id="<?='price'.$row['id']?>" required min=10 max="<?=$row['price'] - $row['money']?>">
								<div class="invalid-feedback">إدخل المبلغ المطلوب من فضلك</div>
							</div>
							<div class="col-md-3"></div>
							<div class="col-md-12" style='color:white'>-</div>
							<div class="col-md-4">
								<button type="submit" id="click[]" value = "<?=$row['id']?>" class="btn btn-primary">حفظ البيانات</button>
							</div>
							<div class="col-md-3"></div>
							<div class="col-md-4">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">قفل الصفحة</button>
							</div>
							<div class="col-md-12" style='color:white'>-</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
			$index++;
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
	?>
	<script src="<?php echo $controller ?>bockings/bockingFinal.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>