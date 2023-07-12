<?php

use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kgi',

	],
]);
$this->title = "Create KPI";
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class=" col-lg-12 font-weight-bold font-size26 text-center">Create new Kpi</div>

			<div class="col-12 mt-20 ">
				<div class="row">
					<div class="col-lg-12">
						<input type="text" name="kpiName" class="form-control font-weight-bold" placeholder="KPI name" required>
					</div>
					<div class="col-lg-3 col-md-3 col-6 mt-10">
						<span class="label-input ml-1">Branch</span>
						<select class="form-control" required name="branch" id="branch-kgi2" onchange="javascript:branchKpi()">
							<option value="">Select Branch</option>
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
					<div class="col-lg-3 col-md-3 col-6 mt-10">
						<span class="label-input ml-1">Team</span>
						<div class="col-12 border" id="branch-team" style="min-height: 40px;"></div>
					</div>
					<div class="col-lg-3 col-md-3 col-6 mt-10">
						<span class="label-input ml-1">Team Position</span>
						<div class="col-12 border" id="branch-team-position" style="min-height: 40px;"></div>


					</div>
					<div class="col-12 text-left  mt-10">
						<label class="label-input">KGI 2</label>
						<?=
						Select2::widget([
							'name' => 'kgi2[]',
							'data' => [],
							'theme' => 'krajee',
							'id' => 'kgi2',
							'options' => [
								'multiple' => true,
								'autocomplete' => 'off',
								'class' => 'form-control',
								'placeholder' => 'Select KGI 2..',
							],
							'pluginOptions' => [
								'allowClear' => true,

							],
						]);
						?>
					</div>
					<div class="col-12 mt-20">
						<span class="label-input ml-1">KPI detail</span>
						<textarea class="form-control" style="height:120px;" name="kpiDetail"></textarea>
					</div>

					<div class="col-12 text-right mt-20">
						<a href="<?= Yii::$app->homeUrl ?>kpi/kpi/index" class="btn btn-outline-secondary mr-20">
							<i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back
						</a>

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