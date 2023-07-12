<div class="col-12">
	<hr>
</div>
<div class="col-12 mt-20 mb-20">
	<div class="row font-size16 head-step">
		<div class="col-6">
			Team change
		</div>
		<div class="col-6 mb-20">
			Approver change
		</div>
	</div>
	<div class=" row">
		<div class="col-6">
			<!-- <select class="form-control" name="team" id="change-team"> -->
			<select class="form-control" name="team" id="dream-team-job">
				<?php

				use kartik\select2\Select2;

				if (isset($job["teamId"])) { ?>
					<option value="<?= $job['teamId'] ?>"><?= $job['teamName'] ?></option>
					<?php
				}
				if (isset($team) && count($team) > 0) {
					foreach ($team as $t) : ?>
						<option value="<?= $t['teamId'] ?>"><?= $t['teamName'] ?></option>
				<?php
					endforeach;
				}
				?>


			</select>
		</div>
		<div class="col-lg-6 col-md-6 text-left">
			<select class="form-control" name="approver" id="approver" required>
				<?php
				if (isset($jobApprover)) { ?>
					<option value="<?= $jobApprover['employeeId'] ?>"><?= $jobApprover['firstName'] ?>&nbsp;&nbsp;&nbsp;<?= $jobApprover['lastName'] ?> (<?= $jobApprover["nickName"] ?>)</option>
				<?php
				}
				?>
				<option value="">Approver</option>
				<?php
				if (isset($approver) && count($approver)) {
					foreach ($approver as $a) : ?>
						<option value="<?= $a["employeeId"] ?>"><?= $a["firstName"] ?>&nbsp;&nbsp;&nbsp;<?= $a["lastName"] ?> (<?= $a["nickName"] ?>)</option>
				<?php
					endforeach;
				}
				?>
			</select>
		</div>
		<div class="col-12 mt-20  font-size16 head-step">
			PIC change
		</div>
		<div class="col-12 mb-20 mt-20">
			<div class="row mb-10">
				<?php
				if (isset($response) && count($response) > 0) { ?>
					<div class="col-6">
						<?php
						$showAdd = 1;
						$countPic1 = 0;
						foreach ($response as $res) : ?>

							<?php
							if ($res["resName"] == "PIC 1") {
								$countPic1++;
							?>
								<div class="row  mb-10" id="add-more-pic1">
									<div class="col-9">
										<select class="form-control" id='morePic1-0' name="pIc1[]">
											<option value="<?= $res['employeeId'] ?>"><?= $res['nickName'] ?></option>
											<?php
											if (isset($pic) && count($pic) > 0) {
												foreach ($pic as $p1) : ?>
													<option value="<?= $p1['employeeId'] ?>"><?= $p1['nickName'] ?></option>
											<?php
												endforeach;
											}
											?>
										</select>
									</div>
									<input type="hidden" id="lastPIC1" value="1">
									<div class="col-2 text-center">
										<input type="text" name="percentagePic1[]" id="percentagePic1-0" class="form-control text-right p-1" value="<?= $res['percent'] ?>" onKeyUp="if(isNaN(this.value)){this.value='';}">
									</div>
									<div class="col-1 border-right">
										<?php
										if ($showAdd == 1) {
										?>
											<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image add-pic-job" id="add-pic1">
										<?php
											$showAdd = 0;
										}
										?>
									</div>
								</div>
							<?php
							}
						endforeach;
						if ($countPic1 == 0) {
							?>
							<div class="row" id="add-more-pic1">
								<div class="col-9">
									<select class="form-control" id='morePic1-0' name="pIc1[]">
										<option value="">PIC 1</option>
										<?php
										if (isset($pic) && count($pic) > 0) {
											foreach ($pic as $p1) : ?>
												<option value="<?= $p1['employeeId'] ?>"><?= $p1['nickName'] ?></option>
										<?php
											endforeach;
										}
										?>
									</select>
								</div>
								<input type="hidden" id="lastPIC1" value="1">
								<div class="col-2 text-center">
									<input type="text" name="percentagePic1[]" id="percentagePic1-0" class="form-control text-right p-1" value="" onKeyUp="if(isNaN(this.value)){this.value='';}" placeholder="%">
								</div>
								<div class="col-1 border-right">
									<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image add-pic-job" id="add-pic1">
								</div>
							</div>
						<?php
						}
						?>
					</div>
					<div class="col-6">
						<?php
						$showAdd = 1;
						$countPic2 = 0;
						foreach ($response as $res) :
							if ($res["resName"] == "PIC 2") {
								$countPic2++;
						?>
								<div class="row mb-10" id="add-more-pic2">
									<div class="col-9">
										<select class="form-control" id='morePic2-0' name="pIc2[]">
											<option value="<?= $res['employeeId'] ?>"><?= $res['nickName'] ?></option>
											<?php
											if (isset($pic) && count($pic) > 0) {
												foreach ($pic as $p2) : ?>
													<option value="<?= $p2['employeeId'] ?>"><?= $p2['nickName'] ?></option>
											<?php
												endforeach;
											}
											?>
										</select>
									</div>
									<input type="hidden" id="lastPIC2" value="1">
									<div class="col-2 text-center">
										<input type="text" name="percentagePic2[]" id="percentagePic2-0" class="form-control text-right p-1 " value="<?= $res['percent'] ?>" onKeyUp="if(isNaN(this.value)){this.value='';}">
									</div>
									<div class="col-1 border-right">
										<?php
										if ($showAdd == 1) {
										?>
											<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image add-pic-job" id="add-pic2">
										<?php
											$showAdd = 0;
										}
										?>
									</div>
								</div>
							<?php
							}
						endforeach;
						if ($countPic2 == 0) {
							?>
							<div class="row" id="add-more-pic2">
								<div class="col-9">
									<select class="form-control" id='morePic2-0' name="pIc2[]">
										<option value="">PIC 2</option>
										<?php
										if (isset($pic) && count($pic) > 0) {
											foreach ($pic as $p2) : ?>
												<option value="<?= $p2['employeeId'] ?>"><?= $p2['nickName'] ?></option>
										<?php
											endforeach;
										}
										?>
									</select>
								</div>
								<input type="hidden" id="lastPIC2" value="1">
								<div class="col-2 text-center">
									<input type="text" name="percentagePic2[]" id="percentagePic2-0" class="form-control text-right p-2" value="" onKeyUp="if(isNaN(this.value)){this.value='';}" placeholder="%">
								</div>
								<div class="col-1 border-right">
									<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image add-pic-job" id="add-pic2">
								</div>
							</div>
						<?php
						}
						?>
					</div>
				<?php
				} else { ?>
					<div class="col-lg-6 col-md-6 text-left">
						<div class="row" id="add-more-pic1">
							<div class="col-9">
								<label class="label-input">PIC 1</label>
								<select class="form-control" name="pIc1[]" id='morePic1-0' required>
									<option value="">PIC 1</option>
									<?php
									if (isset($pic) && count($pic) > 0) {
										foreach ($pic as $p1) : ?>
											<option value="<?= $p1['employeeId'] ?>"><?= $p1['nickName'] ?></option>
									<?php
										endforeach;
									}
									?>
								</select>
								<input type="hidden" id="lastPIC1" value="1">
							</div>
							<div class="col-2 text-center">
								<label class="label-input">Percentage</label>
								<input type="text" name="percentagePic1[]" id="percentagePic1-0" class="form-control text-right p-1" onKeyUp="if(isNaN(this.value)){this.value='';}">
							</div>
							<div class="col-1 border-right">
								<label class="label-input"> </label>
								<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image" id="add-pic1">
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 text-left">
						<div class="row" id="add-more-pic2">
							<div class="col-9">
								<label class="label-input">PIC 2</label>
								<select class="form-control" name="pIc2[]" id='morePic2-0'>
									<option value="">PIC 2</option>
									<?php
									if (isset($pic) && count($pic) > 0) {
										foreach ($pic as $p1) : ?>
											<option value="<?= $p1['employeeId'] ?>"><?= $p1['nickName'] ?></option>
									<?php
										endforeach;
									}
									?>
								</select>
								<input type="hidden" id="lastPIC2" value="1">
							</div>
							<div class="col-2 text-center">
								<label class="label-input">Percentage</label>
								<input type="text" name="percentagePic2[]" id="percentagePic2-0" class="form-control text-right p-1" onKeyUp="if(isNaN(this.value)){this.value='';}">
							</div>
							<div class="col-1">
								<label class="label-input"> </label>
								<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image" id="add-pic2">
							</div>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>


		<div class="col-lg-6 col-md-6 col-12 mt-10">
			<div class="col-12 font-size16 head-step mb-10">
				Email change request
			</div>
			<?php
			//throw new Exception(print_r($currentEmail, true));
			echo Select2::widget([
				'name' => 'email',
				'data' => $email,
				'theme' => 'krajee',
				'value' => $currentEmail,
				'options' => [
					'multiple' => true,
					'autocomplete' => 'off',
					'class' => 'form-control',
					'placeholder' => 'Select email(s)..',
				],
				'pluginOptions' => [
					'allowClear' => true,

				],
			]);
			?>

		</div>
		<div class="col-lg-6 col-md-6 col-12 mt-10">
			<div class="col-12 font-size16 head-step mb-10">
				Total time
			</div>
			<div class="col-12">
				PIC 1 :&nbsp;&nbsp;<input type="text" name="p1Time" class="total-time" onKeyUp="if(isNaN(this.value)){this.value='';}" value="<?= $job['p1Time'] ?>"> hr.
			</div>
			<div class="col-12 mt-10">
				PIC 2 : <input type="text" name="p2Time" class="total-time" onKeyUp="if(isNaN(this.value)){this.value='';}" value="<?= $job['p2Time'] ?>"> hr.
			</div>

		</div>
		<div class="col-12 text-left mt-20 font-size14">
			<b>Teachme Biz url<b>
					<?php
					if ($job['url'] != '') { ?>
						==> <a href=" <?= $job['url'] ?>"> <?= $job['url'] ?></a>
					<?php
					}
					?>

					<input type="text" name="url" class="form-control mt-10" value="<?= $job['url'] ?>">
		</div>
	</div>
</div>