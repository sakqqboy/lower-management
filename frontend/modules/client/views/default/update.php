<?php

use frontend\models\lower_management\Branch;
use yii\bootstrap4\ActiveForm;

$this->title = 'Update Client';
?>
<div class="body-content pt-20">
	<div class="row">
		<div class="col-12 mt-10 text-left page-title">
			Update : <?= $client["clientName"] ?>
		</div>
	</div>
	<?php $form = ActiveForm::begin([
		'id' => 'client-form',
		'action' => Yii::$app->homeUrl . 'client/default/save-update-client',
		'method' => 'post',
		'options' => [
			'enctype' => 'multipart/form-data',
		],
	]); ?>
	<div class="col-12 text-center pt-20 mt-20 create-empolyee-box">
		<div class="row">
			<div class="col-lg-4 text-left mt-10">
				<label class="label-input">Client Name</label>
				<input type="text" name="clientName" class="form-control" value="<?= $client["clientName"] ?>" required>
			</div>
			<input type="hidden" name="clientId" value="<?= $client["clientId"] ?>">
			<div class="col-lg-4 text-left mt-10">
				<label class="label-input">Branch</label>
				<select class="form-control" name="branch" required="required" id="branch">
					<option value="<?= $client["branchId"] ?>"><?= Branch::branchName($client["branchId"]) ?></option>
					<?php
					if (isset($branches) && count($branches) > 0) {
						foreach ($branches as $branch) : ?>
							<option value="<?= $branch["branchId"] ?>"><?= $branch["branchName"] ?></option>
					<?php
						endforeach;
					}
					?>
				</select>
			</div>
			<div class="col-lg-4 text-left mt-10">
				<label class="label-input">Email</label>
				<input type="text" name="email" class="form-control" value="<?= $client["email"] ?>">
			</div>
			<div class="col-lg-4 text-left mt-10">
				<label class="label-input">Tel 1</label>
				<input type="text" name="tel1" class="form-control" value="<?= $client["clientTel1"] ?>">
			</div>
			<div class="col-lg-4 text-left mt-10">
				<label class="label-input">Tel 2</label>
				<input type="text" name="tel2" class="form-control" value="<?= $client["clientTel2"] ?>">
			</div>
			<div class="col-lg-4 text-left mt-10">
				<label class="label-input">Tax Id</label>
				<input type="text" name="taxId" class="form-control">
			</div>
			<div class="col-lg-12 text-left mt-10">
				<label class="label-input">Address</label><br>
				<textarea class="form-control" name="address"></textarea>
			</div>
			<div class="col-lg-12 text-left mt-10">
				<label class="label-input">Remark</label><br>
				<textarea class="form-control" name="remark"><?= $client["remark"] ?></textarea>
			</div>

			<div class="col-12 text-right mt-20 mb-20">
				<button class="btn button-yellow" type="submin"><i class="fa fa-edit mr-10" aria-hidden="true"></i>update</button>
			</div>

		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>