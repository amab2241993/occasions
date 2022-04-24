<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])){
		$pageTitle = 'bocking';
		$getH3 = 'تعديل  الفاتورة';
		include '../../init.php';
		?><script src="<?php echo $controller ?>bockings/bockingUpdate.js"></script><?php
		include $tpl . 'navbar.php';
		/* Start bocking Page */
		if(isset($_GET['bill']) && isset($_GET['status'])){
			$stmt = $con->prepare("SELECT * FROM services WHERE parent_id <=> NULL ORDER BY id DESC");
			$stmt->execute();
			$services = $stmt->fetchAll();
			$stmt = $con->prepare(
				"SELECT bills.* , customers.name , customers.id AS counter FROM bills INNER JOIN customers
				 ON bills.customer_id =customers.id WHERE bills.code = ? AND bills.status = ? LIMIT 1"
			);
			$stmt->execute(array($_GET['bill'] , $_GET['status']));
			$bill = $stmt->fetch();
			$jsons = json_decode($bill["details"]);
			$percentage = intval($bill['discount'])*100 / (intval($bill['total_price']));
		?>
		<form class="row g-3 needs-validation" id="bocking" novalidate>
			<input type="hidden" value="<?php echo count($services) ?>" id="remember">
			<input type="hidden" value="<?php echo count($jsons) ?>" id="jsons">
			<input type="hidden" value="<?=$bill['id']?>" id="billId">
			<input type="hidden" value="<?=$bill['status']?>" id="status">
			<div class="col-3 mb-2 mt-4">
				<select class="form-control" id="type" required>
					<option selected value=<?=$bill['bill_type']?>>
						<?php
							if($bill['bill_type'] == 1) echo'عميل';
							if($bill['bill_type'] == 2) echo 'زميل';
							if($bill['bill_type'] == 3) echo 'حساب العفش فقط';
						?>
					</option>
				</select>
			</div>
			<div class="col-3 mb-2 mt-4">
				<select class="form-control" id="customer" required>
					<option selected value=<?=$bill['counter']?>><?=$bill['name']?></option>
				</select>
			</div>
			<div class="col-6 mb-2 mt-4"></div>
			<div class="col-3 mb-2">
				<select class="form-control" id="services" required>
					<option disabled selected value="">إختار الخدمة</option>
					<?php
						foreach($services as $service){
							echo "<option value='".$service['id']."'>".$service['name']."</option>";
						}
					?>
				</select>
				<div class="invalid-feedback">إختار الخدمة من فضلك</div>
			</div>
			<div class="col-3 mb-2">
			<input type="number" class="form-control" value="" id="quantity" required min=1>
				<div class="invalid-feedback">إدخل الكمية من فضلك</div>
			</div>
			<div class="col-1 mb-2">
				<button type="submit" class="form-control btn btn-primary">إضافة</button>
			</div>
			<div class="col-5 mb-2"></div>
		</form>
		<div class="col-12 mb-2">
			<table class='table table-bordered text-center'>
				<thead class='tableStyle'>
					<tr>
						<th class="col-1 mb-2">رقم</th>
						<th class="col-1 mb-2">الصنف</th>
						<th class="col-1 mb-2">الكمية</th>
						<th class="col-2 mb-2">سعر الوحدة</th>
						<th class="col-2 mb-2">سعر العامل</th>
						<th class="col-2 mb-2">سعر العمال</th>
						<th class="col-2 mb-2">سعر العفش</th>
						<th class="col-1 mb-2">خذف</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 0;
						foreach($jsons as $json){
						?>
						<tr>
							<td class="col-1 mb-2" name="id_numbers[]" id=<?="id_numbers" . $i?>><?=$json->serviceId?></td>
							<td class="col-1 mb-2" name="item[]" id=<?="item" . $i ?>><?=$json->serviceName?></td>
							<td class="col-1 mb-2" style="width:15%">
								<input class='form-control' type='number' name='quantities[]' id=<?='quantities' . $i?> value=<?=$json->quantity?>>
							</td>
							<td scope="col-2 mb-2" name="price_unit[]"    id=<?='price_unit'    . $i?>><?=$json->priceUnit?></td>
							<?php
								if($bill['bill_type'] == 3){
								?>
								<td class="col-2 mb-2" name="price_worker[]"  id=<?='price_worker'  . $i?>>0</td>
								<td class="col-2 mb-2" name="price_workers[]" id=<?='price_workers' . $i?>>0</td>
								<?php
								}
								else{
								?>
								<td class="col-2 mb-2" name="price_worker[]"  id=<?='price_worker'  . $i?>><?=$json->priceWorker?></td>
								<td class="col-2 mb-2" name="price_workers[]" id=<?='price_workers' . $i?>><?=$json->priceWorkers?></td>
								<?php
								}
							?>
							<td class="col-2 mb-2" name="total_units[]"   id=<?='total_units'   . $i?>><?=$json->totalUnits?></td>
							<td class="col-1 mb-1" name="delete[]"        id=<?='delete' .$i?>><i class='fa fa-remove delete'></td>
						</tr>
						<?php
							$i++;
						}
					?>
				</tbody>
			</table>
		</div>
		<form class="row g-3 needs-validation" id="bockingUpdate" style="width:96%;margin: 0 auto;" novalidate>
			<input type="hidden"class="form-control" id="baggageHide" value=<?=(intval($bill['baggage']) / intval($bill['num_days']))?>>
			<?php
				$i = 0;
				foreach($jsons as $json){
				?>
				<input type='hidden' id=<?="quantityHide".$i?> value=<?=$json->quantity?>>
				<input type='hidden' name ="worker[]" id=<?="worker".$i?> value=<?=$json->workerId?>>
				<input type='hidden' name ="store[]" id=<?="store".$i?> value=<?=$json->storeId?>>
				<?php
					$i++;
				}
			?>
			<div class="col-1 mb-2">
				<label for="baggage" style="padding-right:10px" class="form-label">العفش</label>
				<span class="form-control" style="background-color:#dcdcdc;color:red;" id="baggage"><?=$bill['baggage']?></span>
			</div>
			<div class="col-2 mb-2">
				<label for="num_days" class="form-label">عدد الايام</label>
				<input class="form-control" id="num_days" value=<?=$bill['num_days']?> required>
				<div class="invalid-feedback">إدخل عدد الايام من فضلك</div>
			</div>
			<div class="col-2 mb-2">
				<label for="percentage_discount" class="form-label">الخصم %</label>
				<input type="number"class="form-control" id="percentage_discount"  value=<?=round($percentage)?> required>
			</div>
			<div class="col-2 mb-2">
				<label for="discount" class="form-label">الخصم عادي</label>
				<input type="number"class="form-control" id="discount" value=<?=$bill['discount']?>>
			</div>
			<div class="col-2 mb-2">
				<label for="price" class="form-label">المتبقي</label>
				<input type="number"class="form-control" id="remaining" disabled value=<?=$bill['price']?> required>
			</div>
			<div class="col-2 mb-2">
				<label for="relay" class="form-label">الترحيل</label>
				<input type="number" class="form-control" id="relay" value=<?=$bill['relay']?>>
			</div>
			<div class="col-1 mb-2">
				<label for="workers" class="form-label">العمال</label>
				<span class="form-control" style="background-color:#dcdcdc;color:red;" id="workers"><?=$bill['employee_price']?></span>
			</div>
			<div class="col-2 mb-2">
				<label for="total" class="form-label">الأجمالي</label>
				<span class="form-control" style="background-color:#dcdcdc;color:red;" id="total"><?=$bill['total_price']?></span>
			</div>
			<div class="col-1 mb-2">
				<label  class="form-label" style="color:#dcdcdc">-</label>
				<button type="submit" class="form-control btn btn-primary">تعديل</button>
			</div>
		</form>
		<?php
			/* End bocking Page */
			include $tpl . 'footer.php';
		}
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>