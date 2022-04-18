<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])){
		$pageTitle = 'bocking';
		$getH3 = 'حجز فاتورة';
		include '../../init.php';
		?><script src="<?php echo $controller ?>bockings/bocking.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM services WHERE parent_id <=> NULL ORDER BY id ASC");
		$stmt->execute();
		$services = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<!-- ?> -->
<h2>احجز موعدك</h2>
<form class="row g-3 needs-validation" id="bocking" novalidate>
<input type="hidden" value="<?php echo $_GET["date"] ?>" id="date">
<input type="hidden" value="<?php echo count($services) ?>" id="remember">
<div class="col-3 mb-2 mt-4">
<select class="form-control" id="type" required>
<option selected value=1>عميل</option>
<option value=2>زميل</option>
<option value=3>حساب العفش فقط</option>
</select>
<div class="invalid-feedback">إختار النوع من فضلك</div>
</div>
<div class="col-3 mb-2 mt-4">
<select class="form-control" id="customer" required>
<option disabled selected value="">إختار العميل</option>
</select>
<div class="invalid-feedback">إختار النوع من فضلك</div>
</div>
<div class="col-1 mb-2 mt-4">
<i class="form-control btn btn-primary" id = "ex1" data-toggle="modal" data-target="#ex">+</i>
</div>
<div class="col-5 mb-2 mt-4"></div>
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
<input type="text" class="form-control" value="" id="quantity" required>
<div class="invalid-feedback">إدخل الكمية من فضلك</div>
</div>
<div class="col-1 mb-2">
<button type="submit" class="form-control btn btn-primary">إضافة</button>
</div>
<div class="col-5 mb-2"></div>
</form>
<table class='table' style="width:96%;margin: 0 auto;">
<thead class='tableStyle'>
<tr>
<th scope="col-1 mb-2">رقم الصنف</th>
<th scope="col-1 mb-2">الصنف</th>
<th scope="col-1 mb-2">الكمية</th>
<th scope="col-2 mb-2">سعر الوحدة</th>
<th scope="col-2 mb-2">سعر العامل</th>
<th scope="col-2 mb-2">سعر العمال</th>
<th class="col-2 mb-2">سعر العفش</th>
<th scope="col-1 mb-2">خذف</th>
</tr>
</thead>
<tbody>
<?php
for ($i=0; $i < count($services) ; $i++) {
?>
<tr>
<td scope="col-1 mb-2" name="id_numbers[]" id=<?="id_numbers" . $i?>>0</td>
<td scope="col-1 mb-2" name="item[]" id=<?="item" . $i ?>>0</td>
<td scope="col-1 mb-2" style="width:15%">
<input class='form-control' type='number' name='quantities[]' id=<?='quantities' . $i?> value=0>
</td>
<td scope="col-2 mb-2" name="price_unit[]"    id=<?='price_unit'    . $i?>>0</td>
<td scope="col-2 mb-2" name="price_worker[]"  id=<?='price_worker'  . $i?>>0</td>
<td scope="col-2 mb-2" name="price_workers[]" id=<?='price_workers' . $i?>>0</td>
<td scope="col-2 mb-2" name="total_units[]"   id=<?='total_units'   . $i?>>0</td>
<td scope="col-1 mb-2" name="delete[]"        id=<?='delete' .$i?>><i class='fa fa-remove delete'></td>
</tr>
<?php
}
?>
</tbody>
</table>
<form class="row g-3 needs-validation" id="bockings" style="width:96%;margin: 0 auto;" novalidate>
<input type="hidden"class="form-control" id="baggageHide" value=0>
<?php
for ($i=0; $i < count($services) ; $i++) {
?>
<input type='hidden' id=<?="quantityHide".$i?> value=0>
<input type='hidden' name ="worker[]" id=<?="worker".$i?> value=0>
<input type='hidden' name ="store[]" id=<?="store".$i?> value=0>
<?php
}
?>
<div class="col-1 mb-2">
<label for="baggage" style="padding-right:10px" class="form-label">العفش</label>
<span class="form-control" style="background-color:#dcdcdc;color:red;" id="baggage">0</span>
</div>
<div class="col-2 mb-2">
<label for="num_days" class="form-label">عدد الايام</label>
<input class="form-control" id="num_days" value=1 required>
<div class="invalid-feedback">إدخل عدد الايام من فضلك</div>
</div>
<div class="col-2 mb-2">
<label for="percentage_discount" class="form-label">الخصم %</label>
<input type="number"class="form-control" id="percentage_discount"  value=0 max=100>
</div>
<div class="col-2 mb-2">
<label for="discount" class="form-label">الخصم عادي</label>
<input type="number"class="form-control" id="discount" value=0>
</div>
<div class="col-2 mb-2">
<label for="price" class="form-label">المتبقي</label>
<input type="number"class="form-control" id="remaining" disabled value=0 required>
</div>
<div class="col-2 mb-2">
<label for="relay" class="form-label">الترحيل</label>
<input type="number" class="form-control" id="relay" value=0>
</div>
<div class="col-1 mb-2">
<label for="workers" class="form-label">العمال</label>
<span class="form-control" style="background-color:#dcdcdc;color:red;" id="workers">0</span>
</div>
<div class="col-2 mb-2">
<label for="total" class="form-label">الأجمالي</label>
<span class="form-control" style="background-color:#dcdcdc;color:red;" id="total">0</span>
</div>
<div class="col-1 mb-2">
<label  class="form-label" style="color:#dcdcdc">-</label>
<button type="submit" class="form-control btn btn-primary">حفظ</button>
</div>
</form>
<!-- **********************modal*************** -->
<div class='modal fade' id='ex' role='dialog' tabindex='-1' aria-labelledby="exLabel" aria-hidden='true'>
<div class="modal-dialog" role="document">
<div class="modal-content" style="padding-right:10px;padding-left:10px;">
<form class="row g-3 needs-validation" id="customers"novalidate>
<div class="col-10 mt-2"><h4>إضافة عميل</h4></div>
<div class="col-1" style="padding-left:10px;">
<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="col-12">
<label for="customerName">اسم العميل</label>
<input type="text" class="form-control" required id="customerName">
<div class="invalid-feedback">اكتب الاسم من فضلك</div>
</div>
<div class="col-12">
<label for="phone">أدخل رقم الهاتف</label>
<input type="text" class="form-control" required id="phone">
<div class="invalid-feedback">اكتب رقم الهاتف من فضلك</div>
</div>
<div class="col-12">
<label for="address">أدخل العنوان</label>
<input type="text" class="form-control" required id="address">
<div class="invalid-feedback">اكتب عنوان المنزل من فضلك</div>
</div>
<div class="col-12" style='color:white'>-</div>
<div class="col-4 mb-3">
<button class="form-control btn btn-secondary" data-dismiss="modal">قفل الصفحة</button>
</div>
<div class="col-5 mb-3"></div>
<div class="col-3 mb-3">
<button type="submit" class="btn btn-primary">حفظ</button>
</div>
</form>
</div>
</div>
</div>
<?php
/* End bocking Page */
include $tpl . 'footer.php';
}
else{
header('Location:../../index.php');
exit();
}
ob_end_flush();
?>