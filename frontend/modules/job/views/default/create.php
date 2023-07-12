<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create Job';
?>
<div class="body-content pt-20 container">
	<div class="col-12">
		<?php $form = ActiveForm::begin([
			'id' => 'create-job-form',
			'method' => 'post',
			'options' => [
				'enctype' => 'multipart/form-data',
			],

		]); ?>

		<div class="col-12 text-center pt-20 create-empolyee-box">
			<div class="row">
				<div class="col-12">
					<span class="font-size28 "><b>Create New Job</b></span>
				</div>
				<div class="col-12 text-left mt-20">
					<span class="font-size18 ">Please fill out the information completely.</span>
				</div>
				<div class="offset-lg-9 offset-md-9 offset-sm-8  col-lg-3 col-md-3 col-sm-4 offset-6 col-6 text-left mt-10">
					<label class="label-input">Start Date</label>
					<?=
					DatePicker::widget([
						'name' => 'trueDate',
						'type' => DatePicker::TYPE_INPUT,
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd'
						]
					]);
					?>
				</div>
				<div class="col-lg-6 text-left">
					<label class="label-input">Job name</label>
					<input type="text" name="jobName" class="form-control" required>
				</div>
				<div class="col-lg-6 text-left">
					<label class="label-input">Branch</label>
					<select class="form-control" name="branch" id="job-branch" required>
						<option value="">Branch</option>
						<?php
						if (isset($branch) && count($branch)) {
							foreach ($branch as $b) : ?>
								<option value="<?= $b["branchId"] ?>"><?= $b["branchName"] ?></option>
						<?php
							endforeach;
						}
						?>
					</select>
				</div>
				<div class="col-lg-6 text-left mt-10">
					<label class="label-input">Client</label>
					<!-- <input type="text" name="clientName" class="form-control" id="client-name" required>
					<input type="hidden" name="clientId" id="clientId">
					<div class="col-12 client-select-box" id="existClientName">

					</div> -->
					<select class="form-control" name="clientId" id="job-client-id" required>
						<option value="">Client</option>
					</select>
				</div>

				<div class="col-lg-6 text-left mt-10">
					<label class="label-input">Field</label>
					<select class="form-control" name="field" id="field-job" required>
						<option value="">Field</option>

					</select>
				</div>
				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Job type</label>
					<select class="form-control" name="jobType" id="jobType" disabled required>
						<option value="">Job Type</option>
					</select>
				</div>
				<div class="col-lg-8 text-left mt-10" id="step-due-date">
					<div class="col-12 text-center font-size16 pt-40"> Choose Job type</div>
				</div>

				<div class="col-lg-4 text-left mt-10">
					<label class="label-input">Category</label>
					<select class="form-control" name="category" id="job-category" required>
						<option value="">Category</option>
						<?php
						if (isset($category) && count($category)) {
							foreach ($category as $c) : ?>
								<option value="<?= $c["categoryId"] ?>"><?= $c["categoryName"] ?></option>
						<?php
							endforeach;
						}
						?>
					</select>
				</div>
				<div class="col-lg-8 text-left mt-10" id="month-set">
					<div class="col-12 text-center font-size16 pt-40"> Choose Job category</div>
				</div>
				<div class="col-12 mt-20 text-left font-size18">
					<input type="checkbox" class="checkbox-sm mr-10" name="report">Need business report
				</div>
				<div class="col-12 mt-20">
					<hr>
				</div>
				<div class="col-lg-6 col-md-6 text-left mt-10">
					<label class="label-input">Dream team</label>
					<select class="form-control" name="dreamteam" disabled id="dream-team-job" required>
						<option value="">Deram Team</option>
					</select>
				</div>
				<div class="col-lg-6 col-md-6 text-left mt-10">
					<label class="label-input">Approver</label>
					<select class="form-control" name="approver" id="approver" required disabled>
						<option value="">Approver</option>

					</select>
				</div>
				<div class="col-lg-6 col-md-6 text-left mt-30">
					<div class="row" id="add-more-pic1">
						<div class="col-9">
							<label class="label-input">PIC 1</label>
							<select class="form-control" name="pIc1[]" id='morePic1-0' required disabled>
								<option value="">PIC 1</option>
							</select>
							<input type="hidden" id="lastPIC1" value="1">
						</div>
						<div class="col-2 text-center">
							<label class="label-input">Percentage</label>
							<input type="text" name="percentagePic1[]" id="percentagePic1-0" class="form-control text-right p-1" onKeyUp="if(isNaN(this.value)){this.value='';}" disabled>
						</div>
						<div class="col-1 border-right">
							<label class="label-input"> </label>
							<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image" id="add-pic1">
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 text-left mt-30">
					<div class="row" id="add-more-pic2">
						<div class="col-9">
							<label class="label-input">PIC 2</label>
							<select class="form-control" name="pIc2[]" id='morePic2-0' disabled>
								<option value="">PIC 2</option>
							</select>
							<input type="hidden" id="lastPIC2" value="1">
						</div>
						<div class="col-2 text-center">
							<label class="label-input">Percentage</label>
							<input type="text" name="percentagePic2[]" id="percentagePic2-0" class="form-control text-right p-1" onKeyUp="if(isNaN(this.value)){this.value='';}" disabled>
						</div>
						<div class="col-1">
							<label class="label-input"> </label>
							<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image" id="add-pic2">
						</div>
					</div>
				</div>
				<div class="col-12 text-left mt-20 mb-10">
					<label class="label-input">Notify to email</label>
					<?=
					Select2::widget([
						'name' => 'email',
						'data' => $email,
						'theme' => 'krajee',
						'options' => [
							'multiple' => true,
							'autocomplete' => 'off',
							'class' => 'form-control',
							'placeholder' => 'Select email(es)..',
						],
						'pluginOptions' => [
							'allowClear' => true,
						],
					]);
					?>
				</div>
				<div class="col-12 text-left mt-10 mb-10">
					<label class="label-input">Teachme Biz url</label>
					<input type="text" name="url" class="form-control">
				</div>
				<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
					<label class="label-input">Fee</label>
					<input type="text" name="fee" class="form-control text-right" onKeyUp="if(isNaN(this.value)){this.value='';}" required>
				</div>
				<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
					<label class="label-input">Currency</label>
					<select class="form-control" name="currency" required>
						<option value=" ">Currency</option>
						<?php
						if (isset($currency) && count($currency) > 0) {
							foreach ($currency as $c) : ?>
								<option value="<?= $c['currencyId'] ?>">
									<?= $c["name"] ?>&nbsp;&nbsp;(<?= $c["code"] ?>&nbsp;&nbsp;<?= $c["symbol"] ?>)
								</option>
						<?php
							endforeach;
						}
						?>
					</select>
				</div>
				<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
					<label class="label-input">Charge Date</label>
					<?=
					DatePicker::widget([
						'name' => 'feeChargeDate',
						'type' => DatePicker::TYPE_INPUT,
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd'
						]
					]);
					?>
				</div>
				<div class="col-lg-3 col-md-6 col-12 text-left mt-10">
					<label class="label-input">Advance receivable</label>
					<input type="text" name="advanceRec" class="form-control text-right" onKeyUp="if(isNaN(this.value)){this.value='';}">
				</div>
				<div class="col-lg-3 col-md-6 col-12 text-left mt-10">
					<label class="label-input">Charge Date</label>
					<?=
					DatePicker::widget([
						'name' => 'advancedChargeDate',
						'type' => DatePicker::TYPE_INPUT,
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd'
						]
					]);
					?>
				</div>
				<div class="col-lg-3 text-left mt-10">
					<label class="label-input">outsourcing Fee</label>
					<input type="text" name="outsourcingFee" class="form-control text-right" onKeyUp="if(isNaN(this.value)){this.value='';}">
				</div>
				<div class="col-lg-3 text-left mt-10">
					<label class="label-input">Estimate total working time (hrs.)</label>
					<input type="text" name="estimate" class="form-control text-right" onKeyUp="if(isNaN(this.value)){this.value='';}">
				</div>
				<div class="col-lg-12 text-left mt-10">
					<label class="label-input">Carefully points</label>
					<textarea class="form-control" name="memo"></textarea>
				</div>
				<div class="col-12 text-right mt-20 mb-20">
					<input type="hidden" id="em" value="0">
					<button class="btn button-blue" type="submit" onclick="javascript:checkCreateJob()"><i class="fa fa-book mr-10" aria-hidden="true"></i>Create Job</button>
				</div>

			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
	<?php
	echo $this->render('document');
	?>
</div>