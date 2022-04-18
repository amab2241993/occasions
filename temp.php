
	
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