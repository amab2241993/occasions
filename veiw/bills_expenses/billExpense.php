<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])){
		$pageTitle = 'bills expenses';
		$getH3     = 'مصروفات فواتير';
		include '../../init.php';
		$stmt = $con->prepare(
			"SELECT bills.* , customers.name AS customer_name FROM bills
			 INNER JOIN customers ON bills.customer_id = customers.id
			 WHERE bills.status = 2 ORDER BY id DESC"
		);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" action="<?=$_SERVER['PHP_SELF']?>" method = 'post'novalidate>
		<div class="col-3 mb-4 mt-4">
			<select class="form-control js-example-basic-single" id="change"name="state" required>
				<option disabled selected value="">إختار الخدمة</option>
				<?php
					foreach($rows as $row){
						echo "<option value='".$row['id']."'>".$row['code'].' : '.$row['customer_name']."</option>";
					}
				?>
			</select>
			<div class="invalid-feedback">إختار الخدمة من فضلك</div>
		</div>
		<div class="col-1 mb-4 mt-4">
			<button type="submit" class="form-control btn btn-primary">إختيار</button>
		</div>
		<div class="col-5 mb-4 mt-4"></div>
	</form>
	<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $_POST['state'];
			$stmt = $con->prepare(
				"SELECT bills.* ,
				 customers.name AS customer_name ,
				 bill_expense.* , bill_expense.id as counter , bill_expense.main_id as mainId,
				 move.id AS move_id , move.price AS money
				 FROM bills
				 INNER JOIN bill_expense ON bills.id = bill_expense.bill_id 
				 INNER JOIN customers ON bills.customer_id =customers.id
				 INNER JOIN main ON bills.main_id = main.id
				 INNER JOIN move ON main.id = move.main_id
				 WHERE bills.id = ? LIMIT 1"
			);
			$stmt->execute(array($id));
			$bill = $stmt->fetch();
			$jsons = json_decode($bill["details"]);
		?>
		<?php
			if($stmt->rowCount() > 0){
				$total = $bill['total']
			?>
			<table class='table'>
				<thead class='tableStyle'>
					<tr>
						<th class="col-1 mb-2">الصنف</th>
						<th class="col-1 mb-2">الكمية</th>
						<th class="col-1 mb-2">سعر العمال</th>
						<th class="col-1 mb-2">سعر الوحدات</th>
						<th class="col-1 mb-2">الترحيل</th>
						<th class="col-1 mb-2">اجمالي</th>
						<th class="col-1 mb-2">الخصم</th>
						<th class="col-1 mb-2">المبلغ</th>
						<th class="col-1 mb-2">المدفوع</th>
						<th class="col-1 mb-2">المديونية</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($jsons as $json){
						?>
						<tr>
							<td class="col-1 mb-2" id="item[]"><?=$json->serviceName?></td>
							<td class="col-1 mb-2"id="quantities[]"><?=$json->quantity?></td>
							<?php
								if($bill['bill_type'] != 3){
								?>
								<td class="col-1 mb-2" id="price_workers[]"><?=$json->priceWorkers?></td>
								<?php
								}
								else{
								?>
								<td class="col-1 mb-2" id="price_workers[]">0</td>
								<?php
								}
							?>
							<td class="col-1 mb-2"id="total_units[]"><?=$json->totalUnits?></td>
							<td class="col-1 mb-2"></td>
							<td class="col-1 mb-2"></td>
							<td class="col-1 mb-2"></td>
							<td class="col-1 mb-2"></td>
							<td class="col-1 mb-2"></td>
							<td class="col-1 mb-2"></td>
						</tr>
						<?php
						}
					?>
				</tbody>
				<thead class= 'table-dark'>
					<tr>
						<th scope="col-1">الأجمالي</th>
						<th scope="col-1"></th>
						<th scope="col-1"><?=$bill['employee_price']?></th>
						<th scope="col-1"><?=$bill['baggage']?></th>
						<th scope="col-1"><?=$bill['relay']?></th>
						<th scope="col-1"><?=$bill['total_price']?></th>
						<th scope="col-1"><?=$bill['discount']?></th>
						<th scope="col-1"><?=$bill['price']?></th>
						<th scope="col-1"><?=$bill['money']?></th>
						<th scope="col-1"><?=($bill['price'] - $bill['money'])?></th>
					</tr>
				</thead>
			</table>
			<div class="row col-12">
				<div class="col-1 mt-2 mb-2">الخيامة</div>
				<div class="col-2 mt-2 mb-2"id="tent"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['tent']?>
				</div>
				<div class="col-1 mt-2 mb-2">الكهربجي</div>
				<div class="col-2 mt-2 mb-2"id="electricity"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['electricity']?>
				</div>
				<div class="col-1 mt-2 mb-2">الديكورة</div>
				<div class="col-2 mt-2 mb-2"id="decoration"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['decoration']?>
				</div>
				<div class="col-1 mt-2 mb-2">السيرفيس</div>
				<div class="col-2 mt-2 mb-2"id="service"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['service']?>
				</div>
				<div class="col-1 mt-2 mb-2">نثريات</div>
				<div class="col-2 mt-2 mb-2">
					<input type="number" value=<?=$bill['incentives']?> id="incentives">
				</div>
				<div class="col-1 mt-2 mb-2">ادارية</div>
				<div class="col-2 mt-2 mb-2"id="admin"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['admin']?>
				</div>
				<div class="col-1 mt-2 mb-2">المشرف</div>
				<div class="col-2 mt-2 mb-2"id="administrative"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['administrative']?>
				</div>
				<div class="col-1 mt-2 mb-2">المستودع</div>
				<div class="col-2 mt-2 mb-2"id="warehouse"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['warehouse']?>
				</div>
				<div class="col-1 mt-2 mb-2">ترحيل الوسامة</div>
				<div class="col-2 mt-2 mb-2">
					<input type="number" value=<?=$bill['relay_in']?> id="relayIn">
				</div>
				<div class="col-1 mt-2 mb-2">ترحيل خارجي</div>
				<div class="col-2 mt-2 mb-2">
					<input type="number" value=<?=$bill['relay_out']?> id="relayOut">
				</div>
				<div class="col-1 mt-2 mb-2">السواق</div>
				<div class="col-2 mt-2 mb-2">
					<input type="number" value=<?=$bill['driver']?> id="driver">
				</div>
				<div class="col-1 mt-2 mb-2">الزملاء</div>
				<div class="col-2 mt-2 mb-2"id="companion"
				style="font-weight: bolder;font-size: 20px;background-color:#dcdcdc;">
					<?=$bill['companion']?>
				</div>
				<div class="col-2 mt-4 mb-2">اجمالي المنصرف</div>
				<div class="col-2 mt-4 mb-2"id="expense"
				style="font-weight: bolder;font-size: 20px;color:red;">
					<?=$total?>
				</div>
				<div class="col-2 mt-4 mb-2">الارباح</div>
				<div class="col-2 mt-4 mb-2"id="profits"
				style="font-weight: bolder;font-size: 20px;color:red;">
					<?=$bill['price'] - $total?>
				</div>
				<div class="col-2 mt-4 mb-2">الخزنة او البنك</div>
				<div class="col-2 mt-4 mb-2"id="money"
				style="font-weight: bolder;font-size: 20px;color:red;">
					<?=$bill['money'] - $total?>
				</div>
				<div class="col-2 mb-2">
					<button id = "save" class="form-control btn btn-primary">حفظ</button>
				</div>
			</div>
			<form>
				<input type="hidden" id="moneys" value=<?=$bill['money']?>>
				<input type="hidden" id="profit" value=<?=$bill['price']?>>
				<input type="hidden" id="counter" value=<?=$bill['counter']?>>
				<input type="hidden" id="mainId" value=<?=$bill['mainId']?>>
			</form>
			<?php
			}
		}
	?>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>bills_expenses/billExpense.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>