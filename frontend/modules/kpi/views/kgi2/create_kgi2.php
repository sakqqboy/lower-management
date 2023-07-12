<?php

use common\models\ModelMaster;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create KGI 2';

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kgi2',

	],

]);
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 border create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class="col-12 text-center font-size24 font-weight-bold">
				Create KGI 2
			</div>
			<div class="col-12 mt-20">
				<div class="row">
					<div class="col-12">
						<input type="text" name="kgi2Name" class="form-control" placeholder="KGI 2 name" required>
					</div>
					<div class="col-lg-3 col-md-3 col-6 mt-10">
						<span class="label-input ml-1">Branch</span>
						<select class="form-control" required name="branch" id="branch-kgi2" onchange="javascript:branchKgi1()">
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
					<div class="col-lg-3 col-md-3 col-6 mt-10">
						<span class="label-input ml-1">Main KGI 1</span>
						<select class="form-control" required name="mainKgi" id="main-kgi" require>
							<option value="">Main KGI 1</option>
						</select>
					</div>
					<div class="col-12 text-left  mt-10">
						<label class="label-input">KGI 1</label>
						<?=
						Select2::widget([
							'name' => 'kgi1[]',
							'data' => [],
							'theme' => 'krajee',
							'id' => 'kgi1',
							'options' => [
								'multiple' => true,
								'autocomplete' => 'off',
								'class' => 'form-control',
								'placeholder' => 'Select KGI 1..',
							],
							'pluginOptions' => [
								'allowClear' => true,

							],
						]);
						?>
					</div>
					<div class="col-12 mt-10">
						<span class="label-input ml-1">Detail</span>
						<textarea name="detail" class="form-control" style="height: 100px;"></textarea>
					</div>

				</div>
			</div>

			<div class="col-12">
				<div class="row">
					<div class="col-12 mt-30 text-right">
						<a href="<?= Yii::$app->homeUrl ?>kpi/kgi2/index" class="btn btn-outline-secondary mr-20">
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