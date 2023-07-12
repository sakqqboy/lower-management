<?php

use common\models\lower_management\Kgi;
use yii\bootstrap4\ActiveForm;

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kpi',

	],
	"action" => Yii::$app->homeUrl . 'kpi/update/save-update'

]);
?>
<div class="modal-schedule" id="modal-add-schedule">
	<div class="modal-kpi-content" id="modal-content">
		<div class="col-12">
			<div class="row border-bottom header-modal">
				<div class="col-lg-11 col-10 font-size14 pt-3 font-weight-bold">
					<div class="row">
						<div class="text-center circle-warning">
							KPI
						</div>
						<span>KPI Progress</span>
					</div>
				</div>
				<div class="col-lg-1 col-2 text-right close-modal-schedule">
					<i class="fa fa-window-close" aria-hidden="true"></i>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10">
			<div class="row">

				<div class="col-12 font-size16 font-weight-bold"><?= $kpi["kpiName"] ?></div>
				<input type="hidden" name="kpiId" value="<?= $kpi["kpiId"] ?>">
				<input type="hidden" name="pkpiId" value="<?= $pkpi["personalKpiId"] ?>">
				<input type="hidden" name="select-day-kpi" value="" id="select-day-kpi">
				<input type="hidden" name="select-month-kpi" value="" id="select-month-kpi">
				<input type="hidden" name="select-year-kpi" value="" id="select-year-kpi">
				<div class="col-12 text-left mt-10">
					<span id="date-text" class="font-size16 mt-20"></span>
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 pb-10 font-size16 text-left font-weight-bold">
			<label class="label-input ">Amount</label>
			<input type="number" class="form-control text-right font-weight-bold font-size18" required name="amount" style="height:50px;">
		</div>
		<div class="col-12 mt-10 pb-10 font-size16 text-left font-weight-bold">
			<label class="label-input ">Detail</label>
			<textarea class="form-control" style="height:250px;" name="personalDetail" required></textarea>
		</div>
		<div class="col-12 mt-10">
			<div class="row">
				<div class="col-3 font-size14 text-left font-weight-bold pt-1">Attach file</div>
				<div class="col-9 font-size16 text-right font-weight-bold">
					<div class="form-group">
						<div class="input-group">
							<input type="text" class="form-control" readonly>
							<div class="input-group-btn">
								<span class="fileUpload btn button-turqouise" style="height: 38px;padding-top:7px;">
									<span class="" id="upload"><i class="fa fa-file mr-2" aria-hidden="true"></i> Evidence File</span>
									<input type="file" name="kpiFile" class="upload up" id="up" onchange="readURL(this);" />
								</span><!-- btn-orange -->
							</div><!-- btn -->
						</div><!-- group -->
					</div><!-- form-group -->
				</div>
			</div>
		</div>
		<div class="col-12 mt-10 text-right pr-1">
			<button class="btn btn-add no-no-underline-white font-size16 mr-10">
				<i class="fa fa-edit mr-1" aria-hidden="true"></i>
				Update
			</button>
		</div>
	</div>

</div>

<input type="hidden" id="yearKpi" value="<?= $yearKpi ?>" name="year">
<input type="hidden" id="monthKpi" value="<?= $monthKpi ?>" name=month>
<?php ActiveForm::end(); ?>