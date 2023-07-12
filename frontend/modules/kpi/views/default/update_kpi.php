<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi;
use frontend\models\lower_management\Kpi;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use yii\bootstrap4\ActiveForm;

$this->title = 'Update KPI';

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kgi',

	],
	'action' => Yii::$app->homeUrl . 'kpi/default/save-update-kpi'

]);
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 border create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class="col-12 text-center font-size24 font-weight-bold">
				Update KPI : <?= $kpi["kpiName"] ?>
			</div>
			<div class="col-12 font-size16">
				KGI : <b><?= $kgi["kgiName"] ?></b>
			</div>
			<div class="col-lg-12 mt-10 font-size18">
				Team : <b><?= Team::teamName($kgi["teamId"]) ?></b>,&nbsp;&nbsp;&nbsp;Team Position : <b><?= TeamPosition::positionName($kgi["teamPositionId"]) ?></b>
			</div>
			<div class="col-12 mt-20 ">
				<div class="row">
					<div class="col-lg-12">
						<input type="text" name="kpiName" class="form-control font-weight-bold" placeholder="KPI name" required value="<?= $kpi->kpiName ?>">
					</div>
					<div class="col-12 mt-20">
						<span class="label-input ml-1">KPI detail</span>
						<textarea class="form-control" style="height:120px;" name="kpiDetail"><?= $kpi->kpiDetail ?></textarea>
					</div>
					<div class="col-12 mt-20">
						<div class="row">
							<div class="col-lg-3">
								<span class="label-input ml-1">Unit</span>
								<select class="form-control" required name="unit">
									<option value="<?= $kpi["unit"] ?>"><?= Kpi::unitName($kpi["unit"]) ?></option>
									<?php
									if (isset($kpiUnit) && count($kpiUnit) > 0) {
										foreach ($kpiUnit as $unit) : ?>
											<option value="<?= $unit['kpiUnitId'] ?>"><?= $unit['name'] ?></option>
									<?php
										endforeach;
									}
									?>
								</select>
							</div>
							<div class="col-lg-3">
								<?php
								if ($kpi["symbolCheck"] == 1) {
									$check = '>';
								}
								if ($kpi["symbolCheck"] == 2) {
									$check = '<';
								}
								if ($kpi["symbolCheck"] == 3) {
									$check = '=';
								}
								if ($kpi["amountType"] == 2) {
									$percent = '%';
								} else {
									$percent = 'Number';
								}
								?>
								<span class="label-input ml-1">Check</span>
								<select class="form-control" required name="check">
									<option value="<?= $kpi['symbolCheck'] ?>"><?= $check ?></option>
									<option value="1"><b> > </b></option>
									<option value="2"><b>
											< </b>
									</option>
									<option value="3"><b> = </b></option>
								</select>
							</div>
							<div class="col-lg-3">
								<span class="label-input ml-1">Target amount</span>
								<input type="number" class="form-control text-right" min="0" name="targetAmount" required value="<?= $kpi["targetAmount"] ?>">

							</div>
							<div class="col-lg-3">
								<span class="label-input ml-1">Amount Type</span>
								<select class="form-control" name="type">
									<option value="<?= $kpi["amountType"] ?>"><?= $percent ?></option>
									<option value="1">Number</option>
									<option value="2">%</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-12 text-right mt-20">
						<a href="<?= Yii::$app->request->referrer ?>" class="btn btn-outline-secondary mr-20">
							<i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back
						</a>
						<input type="hidden" name="kpiId" value="<?= $kpi['kpiId'] ?>">
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