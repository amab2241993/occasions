<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'bill return';
		$getH3     = 'مردودات الفواتير';
		include '../../init.php';
		?><script src="<?php echo $controller ?>bills_returns/billReturn.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM accounts WHERE parent_id = 5");
		$stmt->execute();
		$accounts = $stmt->fetchAll();
		$stmt = $con->prepare(
			"SELECT bills.id AS billId , bills.code , bills.status ,
			 customers.name AS customer_name ,
			 bill_refund.main_id AS counter , bill_refund.refund FROM bill_refund
			 INNER JOIN bills ON bills.id = bill_refund.bill_id
			 INNER JOIN customers ON bills.customer_id =customers.id
			 WHERE bill_refund.refund != 0"
		);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<table id="first" class="table table-striped table-bordered  text-center">
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1">رقم الفاتورة</th>
				<th scope="col-1">اسم العميل</th>
				<th scope="col-1">نوع الفاتورة</th>
				<th scope="col-1">مطلوب</th>
				<th scope="col-1">التحكم</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if (! empty($rows)){
					foreach($rows as $row){
					?>
					<tr>
						<td scope="col-1"><?= $row['code']; ?></td>
						<td scope="col-1"><?= $row['customer_name']; ?></td>
						<?php
							if($row['status'] == 2){
							?>
							<td scope="col-1">فاتورة نهائية</td>
							<?php
							}
							else{
							?>
							<td scope="col-1">فاتورة مؤجلة</td>
							<?php
							}
						?>
						<td scope="col-1"><?= $row['refund']?></td>
						<td scope="col-1" id=<?=$row['counter']?>>
							<button type='button' class="execute">إدفع</button>
						</td>
					</tr>
					<?php
					}
				}
			?>
		</tbody>
	</table>
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
	// }
	// else{
	// 	header('Location:../../index.php');
	// 	exit();
	// }
	ob_end_flush();
?>