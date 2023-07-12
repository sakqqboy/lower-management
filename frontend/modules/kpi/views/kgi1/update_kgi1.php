<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi1;
use yii\bootstrap4\ActiveForm;

$this->title = 'Update KGI 1';
$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kpi',

	],
	'action' => Yii::$app->homeUrl . 'kpi/kgi1/save-update-kgi1'

]);
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 border create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class="col-12 text-left font-size24 font-weight-bold">
				Update : <?= $kgi1["kgi1Name"] ?>
			</div>
			<div class="col-12 mt-20">
				<div class="row">
					<div class="col-12">
						<input type="text" name="kgi1Name" class="form-control" value="<?= $kgi1["kgi1Name"] ?>" placeholder="KGI 1 name" required>
					</div>
					<div class="col-lg-3 mt-10">
						<span class="label-input ml-1">Branch</span>
						<select class="form-control" required name="branch" id="branch-kgi">
							<option value="<?= $kgi1["branchId"] ?>"><?= Branch::branchName($kgi1["branchId"]) ?></option>
							<?php
							if (isset($branch) && count($branch) > 0) {
								foreach ($branch as $b) : ?>
									<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3 mt-10">
						<span class="label-input ml-1">Code</span>
						<select class="form-control" required name="code">
							<option value="<?= $kgi1["code"] ?>"><?= Kgi1::codeText($kgi1["code"]) ?></option>
							<option value="1"><b> > </b>
							</option>
							<option value="2"><b>
									< </b>
							</option>
							<option value="3"><b> = </b></option>
						</select>
					</div>
					<div class="col-lg-3 mt-10">
						<span class="label-input ml-1">Target amount</span>
						<input type="number" class="form-control text-right" min="0" name="targetAmount" value="<?= $kgi1["targetAmount"] ?>" required>
					</div>
					<div class="col-lg-3 mt-10">
						<span class="label-input ml-1">Amount Type</span>
						<select class="form-control" name="amountType" required>
							<option value="<?= $kgi1['amountType'] ?>"><?= Kgi1::amountType($kgi1["amountType"]) ?></option>
							<option value="1">Number</option>
							<option value="2">%</option>
						</select>
					</div>
					<div class="col-12 mt-10">
						<span class="label-input ml-1">Detail</span>
						<textarea name="detail" class="form-control" style="height: 100px;"><?= $kgi1["detail"] ?></textarea>
					</div>

				</div>
			</div>

			<div class="col-12">
				<div class="row">
					<div class="col-12 mt-40 text-right">
						<a href="<?= Yii::$app->homeUrl ?>kpi/kgi1/index" class="btn btn-outline-secondary mr-20">
							<i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back
						</a>
						<input type="hidden" name="kgi1Id" value="<?= $kgi1['kgi1Id'] ?>">
						<button type="submit" class="btn button-yellow">
							<i class="fa fa-edit mr-2" aria-hidden="true"></i>Update
						</button>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>