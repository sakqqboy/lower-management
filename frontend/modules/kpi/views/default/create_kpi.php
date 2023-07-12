<?php

use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use yii\bootstrap4\ActiveForm;

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kgi',

	],
	'action' => Yii::$app->homeUrl . 'kpi/default/save-kpi'
]);
$this->title = "Create KPI";
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class=" col-lg-12 font-weight-bold font-size26"><?= $kgi["kgiName"] ?></div>
			<div class="col-lg-12 col-md-6 col-6 mt-10 font-size18">Team : <b><?= Team::teamName($kgi["teamId"]) ?></b>,&nbsp;&nbsp;&nbsp;Team Position : <b><?= TeamPosition::positionName($kgi["teamPositionId"]) ?></b></div>


			<div class="col-12 mt-20 ">
				<div class="row">
					<div class="col-lg-12">
						<input type="text" name="kpiName" class="form-control font-weight-bold" placeholder="KPI name" required>
					</div>
					<div class="col-12 mt-20">
						<span class="label-input ml-1">KPI detail</span>
						<textarea class="form-control" style="height:120px;" name="kpiDetail"></textarea>
					</div>
					<div class="col-12 mt-20">
						<div class="row">
							<div class="col-lg-3">
								<span class="label-input ml-1">Unit</span>
								<select class="form-control" required name="unit">
									<option value="">Unit</option>
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
								<span class="label-input ml-1">Check</span>
								<select class="form-control" required name="check">
									<option value="">Check</option>
									<option value="1"><b> > </b>
									</option>
									<option value="2"><b>
											< </b>
									</option>
									<option value="3"><b> = </b></option>
								</select>
							</div>
							<div class="col-lg-3">
								<span class="label-input ml-1">Target amount</span>
								<input type="number" class="form-control text-right" min="0" name="targetAmount">
							</div>
							<div class="col-lg-3">
								<span class="label-input ml-1">Amount Type</span>
								<select class="form-control" name="type">
									<option value="">Amount Type</option>
									<option value="1">Number</option>
									<option value="2">%</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-12 text-right mt-20">
						<a href="<?= Yii::$app->homeUrl ?>kpi/default/index" class="btn btn-outline-secondary mr-20">
							<i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back
						</a>
						<input type="hidden" name="kgiId" value="<?= $kgi['kgiId'] ?>">
						<button type="submit" class="btn button-blue">
							<i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Submit
						</button>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>