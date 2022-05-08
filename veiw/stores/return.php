<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'return';
		$getH3     = 'الفواتير غير المسترجعة';
		include '../../init.php';
		?><script src="<?php echo $controller ?>stores/return.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare(
			"SELECT bills.code , cashing.id , cashing.parent_id , cashing.service_id , services.name ,
			 cashing.quantity FROM bills
			 INNER JOIN cashing ON cashing.bill_id = bills.id
			 INNER JOIN services ON cashing.service_id = services.id
			 ORDER BY bills.id DESC"
		);
		$stmt->execute();
		$index = 0;
		$code = 0;
		$rows = $stmt->fetchAll();
	?>
	<form class="row g-3 needs-validation" id ="changeId" novalidate>
		<?php
			foreach($rows as $row){
				$id   = "cId"."=".$row['id'];
				$code = "code"."=".$row['code'];
				$qu   = "qu"."=".$row['quantity'];
				$pId  = "pId"."=".$row['parent_id'];
				$sId  = "sId"."=".$row['service_id'];
			?>
			<input type="hidden" id="row[]" <?=$qu?> value="<?=$row['name']?>" <?=$code?> <?=$pId?> <?=$sId?> <?=$id?> />
			<?php
			}
		?>
		<div class="col-3 mb-4 mt-4">
			<select class="form-control js-example-basic-single" id="change" required>
				<option selected value=1>أستراجاع المخازن</option>
				<option value=2>أستراجاع الزملاء</option>
			</select>
			<div class="invalid-feedback">إختار الخدمة من فضلك</div>
		</div>
	</form>
	<table class='table table-bordered  text-center'>
		<thead class="tableStyle">
			<th scope="col-1">رقم الفاتورة</th>
			<th scope="col-1">فحص</th>
			<th></th>
		</thead>
		<?php
		foreach($rows as $row){
			if($row['code'] != $code){
			?>
			<tbody>
				<tr>
					<td scope="col-1"><?=$row['code']?></td>
					<td scope="col-1">
						<button class="btn btn-primary" name="check[]" code="<?=$row['code']?>">
							فحص
						</button>
					</td>
				</tr>
			</tbody>
			<?php
			}
			$code = $row['code'];
		}
		?></table><?php
	?>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='out' tabindex="-1" role="dialog" aria-labelledby="outLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<div class="col-5 mt-2">
						<h3>إسترجاع</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<table class="table">
							<thead>
								<th class='col-2'>الصنف</th>
								<th class='col-3'>الكمية</th>
								<th class='col-2'></th>
								<th class='col-5'></th>
							</thead>
							<tbody id="tbody">
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
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