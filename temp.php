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