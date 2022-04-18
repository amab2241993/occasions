<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'Dashboard';
		include 'init.php';
		$stmt = $con->prepare("SELECT * FROM halls ORDER BY id DESC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<h3>إدارة الصالة</h3>
	<form class="needs-validation halls" novalidate style="padding-right:10px;padding-left:10px;">
		<div class="form-row">
			<div class="col-md-2 mb-3">
				<label for="name" style="padding-right:10px;">اسم الصالة</label>
				<input type="text" class="form-control" placeholder="اسم الصالة" required id="name">
				<div class="invalid-feedback">
					اكتب الاسم من فضلك
				</div>
			</div>
			<div class="col-md-2 mb-3">
				<label for="price"style="padding-right:10px;">سعر الصالة</label>
				<input type="number" class="form-control" placeholder="سعر الصالة" required  id="price">
				<div class="invalid-feedback">
					اكتب السعر من فضلك
				</div>
			</div>
			<div class="col-md-2 mb-3">
				<label for="breakfast" style="padding-right:10px;">وقت الفطور</label>
				<input type="time" class="form-control" placeholder="وقت الفطور" required  id="breakfast">
				<div class="invalid-feedback">
					أدخل وقت الفطور من فضلك
				</div>
			</div>
			<div class="col-md-2 mb-3">
				<label for="lunch"style="padding-right:10px;">وقت الغداء</label>
				<input type="time" class="form-control" placeholder="وقت الغداء" required  id="lunch">
				<div class="invalid-feedback">
					أدخل وقت الغداء من فضلك
				</div>
			</div>
			<div class="col-md-2 mb-3">
				<label for="dinner"style="padding-right:10px;">وقت العشاء</label>
				<input type="time" class="form-control" placeholder="وقت العشاء" required  id="dinner">
				<div class="invalid-feedback">
					أدخل وقت الغداء من العشاء
				</div>
			</div>
			<div class="col-md-1 mb-1">
				<label style="color:white;">-</label>
				<button class="form-control btn btn-primary" type="submit">اضافة</button>
			</div>
		</div>
	</form>
	<table class="table tableStyle1">
		<thead class="tableStyle">
			<tr>
				<th scope="col-md-1">#</th>
				<th scope="col">اسم الصالة</th>
				<th scope="col">السعر</th>
				<th scope="col">وقت الإفطار</th>
				<th scope="col">وقت الغداء</th>
				<th scope="col">وقت العشاء</th>
				<th scope="col">تعديل</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if (! empty($rows)){
					$count= 0;
					foreach($rows as $row){
					?>
					<tr>
						<th scope="row"><?=++$count?></th>
						<td><?= $row['name']; ?></td>
						<td><?= $row['price']; ?></td>
						<td><?= $row['breakfast_time']; ?></td>
						<td><?= $row['lunch_time']; ?></td>
						<td><?= $row['dinner_time']; ?></td>
						<td><button type='button' data-toggle='modal' data-target='<?="#exampleModal".$count?>'>تعديل</button></td>
					</tr>
					<?php
					}
				}
			?>
		</tbody>
	</table>
	<?php
		$index = 1;
		$update[]='';
		foreach($rows as $row){
			$stmt = $con->prepare("SELECT * FROM halls WHERE id=? LIMIT 1");
			$stmt->execute(array($row['id']));
			$update[$index] = $stmt->fetch();
		?>
		<div class="modal fade" id='<?="exampleModal".$index?>' role="dialog" tabindex="-1" aria-labelledby='<?="exampleModal"."$index"."Label";?>' aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="padding-right:10px;padding-left:10px;">
					<form class="row g-3 needs-validation update" novalidate>
						<input type="hidden" id="<?='hall'.$update[$index]['id'];?>" value="<?=$update[$index]['id'];?>">
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-4"><h4>تعديل البيانات</h4></div>
						<div class="col-md-6"></div>
						<div class="col-md-1">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-6">
							<label for="name1">اسم الصالة</label>
							<input type="text" class="form-control" value ="<?=$update[$index]['name'];?>" required id="<?="name".$update[$index]['id'];?>">
							<div class="invalid-feedback">
								اكتب الاسم من فضلك
							</div>
						</div>
						<div class="col-md-6">
							<label for="price1">سعر الصالة</label>
							<input type="number" class="form-control" required value="<?=$update[$index]['price'];?>" id="<?="price".$update[$index]['id'];?>">
							<div class="invalid-feedback">
								اكتب السعر من فضلك
							</div>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-4">
							<label for="breakfast1">وقت الفطور</label>
							<input type="time" class="form-control" required value="<?=$update[$index]['breakfast_time'];?>" id="<?="breakfast".$update[$index]['id'];?>">
							<div class="invalid-feedback">
								أدخل وقت الفطور من فضلك
							</div>
						</div>
						<div class="col-md-4">
							<label for="lunch1">وقت الغداء</label>
							<input type="time" class="form-control" required value="<?=$update[$index]['lunch_time'];?>" id="<?="lunch".$update[$index]['id'];?>">
							<div class="invalid-feedback">
								أدخل وقت الغداء من فضلك
							</div>
						</div>
						<div class="col-md-4">
							<label for="dinner1">وقت العشاء</label>
							<input type="time" class="form-control" required value="<?=$update[$index]['dinner_time'];?>" id="<?="dinner".$update[$index]['id'];?>">
							<div class="invalid-feedback">
								أدخل وقت الغداء من العشاء
							</div>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-4">
							<button class="form-control btn btn-secondary" data-dismiss="modal">قفل الصفحة</button>
						</div>					
						<div class="col-md-6"></div>
						<div class="col-md-1">
							<button type="submit" id="click[]" value = "<?=$update[$index]['id']?>" class="btn btn-primary">حفظ</button>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
					</form>
				</div>
			</div>
		</div>
		<?php
			$index++;
		}
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>halls.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location: index.php');
		exit();
	}
	ob_end_flush();
?>