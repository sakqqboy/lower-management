<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create Employee';
?>
<div class="body-content pt-20 container">
	<div class="col-12">
		<?php $form = ActiveForm::begin([
			'id' => 'employee-form',
			'action' => Yii::$app->homeUrl . 'setting/employee/update-employee',
			'method' => 'post',
			'options' => [
				'enctype' => 'multipart/form-data',
			],
		]); ?>

		<div class="col-12 text-center pt-20 create-empolyee-box">
			<div class="row">
				<div class="col-12">
					<span class="font-size26 ">Update Employee</span>
				</div>
				<div class="col-12 text-left mt-20 mb-2">
					<span class="font-size18 ">Personal Infomation</span>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Firstname</label>
					<input type="text" name="firstName" class="form-control" value="<?= $employee["employeeFirstName"] ?>" required>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Lastname</label>
					<input type="text" name="lastName" class="form-control" value="<?= $employee["employeeLastName"] ?>" required>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Nickname</label>
					<input type="text" name="nickName" value="<?= $employee["employeeNickName"] ?>" class="form-control">
				</div>

				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">BirthDate</label>
					<?=
					DatePicker::widget([
						'name' => 'birthDate',
						'type' => DatePicker::TYPE_INPUT,
						'value' => $employee["birthDate"],
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd'
						]
					]);
					?>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Email</label><label class="label-input text-danger invalid-input" id="invalid-input">* * Duplicate email, please use new email.</label>
					<input type="email" name="email" class="form-control" value="<?= $employee["email"] ?>" id="employeeEmail" onkeyup="javascript:checkEmail()" required>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Telephone Number</label>
					<input type="text" name="telNember" value="<?= $employee["telephoneNumber"] ?>" class="form-control">
				</div>

				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Gender</label><br>
					<input type="radio" name="gender" class="form-check-inline" value="1" <?= $employee["gender"] == 1 ? 'checked' : '' ?>>Male
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="gender" class="form-check-inline" value="2" <?= $employee["gender"] == 2 ? 'checked' : '' ?>>Female
				</div>
				<?php
				if ($employee['picture'] && $employee['picture'] != null) {
				?>
					<div class="col-lg-4 text-left mt-10">
						<img src="<?= Yii::$app->homeUrl ?>images/<?= $employee['picture'] ?>" class="img-fluid">
					</div>
				<?php
				}
				?>
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
						<option value="<?= $employee["branchId"] ?>"><?= Branch::branchName($employee["branchId"]) ?></option>
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
					<select class="form-control" name="section" id="section" required="required">
						<option value="<?= $employee["sectionId"] ?>"><?= Section::sectionName($employee["sectionId"]) ?></option>
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
					<select class="form-control" name="position" id="position" required="required">
						<option value="<?= $employee["positionId"] ?>"><?= Position::positionName($employee["positionId"]) ?></option>
					</select>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Dream Team</label>
					<select class="form-control" name="team">
						<option value="<?= $employee["teamId"] ?>"><?= Team::teamName($employee["teamId"]) ?></option>
						<?php
						if (isset($teams) && count($teams) > 0) {
							foreach ($teams as $team) : ?>
								<option value="<?= $team["teamId"] ?>"><?= $team["teamName"] ?></option>
						<?php
							endforeach;
						}
						?>
					</select>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Dream Team Position</label>
					<select class="form-control" name="teamPosition">
						<option value="<?= $employee["teamPositionId"] ?>"><?= TeamPosition::positionName($employee["teamPositionId"]) ?></option>
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
						foreach ($userType as $type) :
							if (isset($currentType[$type["typeId"]])) {
								$check = "checked";
							} else {
								$check = "";
							}
					?>
							<div class="col-lg-3 col-md-4 col-6 mb-20 text-left">
								<input type="checkbox" class="form-check-inline" name="userType[]" value="<?= $type["typeId"] ?>" <?= $check ?>><?= $type["typeName"] ?>
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
					<input type="hidden" name="em" id="em" value="<?= $employee["employeeId"] ?>">
					<button class="btn button-yellow"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;Update</button>
				</div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>

</div>