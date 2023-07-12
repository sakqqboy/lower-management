<?php

use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create Employee';
?>
<div class="body-content pt-20 container">
	<div class="col-12">
		<?php $form = ActiveForm::begin([
			'id' => 'employee-form',
			'action' => Yii::$app->homeUrl . 'setting/employee/create-employee',
			'method' => 'post',
			'options' => [
				'enctype' => 'multipart/form-data',
			],
		]); ?>

		<div class="col-12 text-center pt-20 create-empolyee-box">
			<div class="row">
				<div class="col-12">
					<span class="font-size26 ">Create Employee</span>
				</div>
				<div class="col-12 text-left mt-20 mb-2">
					<span class="font-size18 ">Personal Infomation</span>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Name</label>
					<input type="text" name="firstName" class="form-control" required>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Lastname</label>
					<input type="text" name="lastName" class="form-control">
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Nickname</label>
					<input type="text" name="nickName" class="form-control">
				</div>

				<div class="col-lg-4 text-left mt-10" style="z-index:10;">
					<label class="label-input">BirthDate</label>
					<?=
					DatePicker::widget([
						'name' => 'birthDate',
						'type' => DatePicker::TYPE_INPUT,
						//'value' => '23-Feb-1982',
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd'
						]
					]);
					?>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Email</label><label class="label-input text-danger invalid-input" id="invalid-input">* * Duplicate email, please use new email.</label>
					<input type="email" name="email" class="form-control" id="employeeEmail" onkeyup="javascript:checkEmail()" required>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Telephone Number</label>
					<input type="text" name="telNember" class="form-control">
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Gender</label><br>
					<input type="radio" name="gender" class="form-check-inline" value="1">Male
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="gender" class="form-check-inline" value="2">Female
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Picture</label><br>
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" readonly>
							<div class="input-group-btn">
								<span class="fileUpload btn button-blue">
									<span class="upl news-button" id="upload"><i class="fa fa-file" aria-hidden="true"></i> <?= Yii::t('app', 'Picture') ?></span>
									<input type="file" name="picture" class="upload up" id="up" onchange="readURL(this);" />
								</span><!-- btn-orange -->
							</div><!-- btn -->
						</div><!-- group -->
					</div><!-- form-group -->
				</div>
				<div class="col-12 text-left mt-20 mb-2">
					<hr>
					<span class="font-size18 ">Work Infomation</span>

				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Branch</label>
					<select class="form-control" name="branch" required="required" id="branch">
						<option value="">Branch</option>
						<?php
						if (isset($branchs) && count($branchs) > 0) {
							foreach ($branchs as $branch) : ?>
								<option value="<?= $branch["branchId"] ?>"><?= $branch["branchName"] ?></option>
						<?php
							endforeach;
						}
						?>
					</select>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Section</label>
					<select class="form-control" name="section" id="section" disabled required="required">
						<option>Section</option>
						<?php
						if (isset($sections) && count($sections) > 0) {
							foreach ($sections as $section) : ?>
								<option value="<?= $section["sectionId"] ?>"><?= $section["sectionName"] ?></option>
						<?php
							endforeach;
						}
						?>

					</select>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Position</label>
					<select class="form-control" name="position" id="position" disabled required="required">
						<option>Position</option>
					</select>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Dream Team</label>
					<select class="form-control" name="team" id="team-create">
						<option>Dream Team</option>

					</select>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Dream Team Position</label>
					<select class="form-control" name="teamPosition">
						<option>Dream Team Position</option>
						<?php
						if (isset($teamPosition) && count($teamPosition) > 0) {
							foreach ($teamPosition as $teamPo) : ?>
								<option value="<?= $teamPo["id"] ?>"><?= $teamPo["name"] ?></option>
						<?php
							endforeach;
						}
						?>
					</select>
				</div>
				<div class="col-12 text-left mt-40 mb-2">
					<hr>
					<span class="font-size18 ">User Type</span>

				</div>
				<div class="col-12 row mt-10">
					<?php
					if (isset($userType) && count($userType) > 0) {
						foreach ($userType as $type) : ?>
							<div class="col-lg-3 col-md-4 col-6 mb-20 text-left">
								<input type="checkbox" class="form-check-inline" name="userType[]" value="<?= $type["typeId"] ?>"><?= $type["typeName"] ?>
							</div>
					<?php
						endforeach;
					}
					?>
				</div>
				<div class="col-12 text-left mt-20">
					<hr>
				</div>
				<div class="col-12 text-right mt-20 mb-20">
					<input type="hidden" id="em" value="0">
					<button class="btn button-blue"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;Create</button>
				</div>

			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>

</div>