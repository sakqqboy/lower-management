<?php

use frontend\models\lower_management\JobType;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Alert;
use yii\helpers\ArrayHelper;

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'import',
	],
]);
$this->title = 'Import';
?>

<div class="body-content pt-20 container">
	<div class="row">
		<div class="col-12 mb-20 ">
			<span class="font-size20 font-weight-bold ">Upload Job Type Step excel file</span>
		</div>
		<div class="col-12 border pt-20 pb-20 pr-30">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-12">
					<div class="col-lg-9 col-md-6 col-12 mb-20">
						<select class="form-control" required name="branch">
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
					<div class="col-12 mt-20 ">
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control" readonly>
								<div class="input-group-btn">
									<span class="fileUpload btn button-blue">
										<span class="" id="upload"><i class="fa fa-file" aria-hidden="true"></i> File .xlsx</span>
										<input type="file" name="clientFile" class="upload up" id="up" onchange="readURL(this);" />
									</span><!-- btn-orange -->
								</div><!-- btn -->
							</div><!-- group -->
						</div><!-- form-group -->
					</div>
					<div class="col-lg-12 mt-20">
						<button class="btn button-sky" type="submit"><i class="fa fa-upload mr-1" aria-hidden="true"></i> UPLOAD</button>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-12 pt-10 upload-result border">
					<span class="font-size18 font-weight-bold ">Result</span>
					<?php
					if (isset($count) && $count > 0) { ?>
						<div class="col-12 mt-10 pl-20 pb-20 font-size16 font-weight-bold ">
							Total Import : <?= $count ?> records.
						</div>
						<?php
						if (isset($newJobType) && $newJobType > 0) { ?>
							<div class="col-12 mt-10 pl-20 pb-10 font-size16 font-weight-bold ">
								New Jop Type : <?= $newJobType ?> records.
							</div>
						<?php
						}
						if (isset($updateJobType) && $updateJobType > 0) { ?>
							<div class="col-12 mt-10 pl-20 pb-10 font-size16 font-weight-bold ">
								update Jop Type : <?= $updateJobType ?> records.
							</div>
						<?php
						}
						if (isset($new) & count($new) > 0) { ?>
							<div class="col-12 mt-10 pl-20 pb-10 font-size16 font-weight-bold ">
								New Step : <?= $newStep ?> records.
							</div>
							<?php
							foreach ($new as $jobTypeId => $steps) :
							?>
								<div class="col-12 mt-10 pl-40 pb-10 font-size14">
									<div class="row">
										<div class="col-12 font-size16">
											<b><?= JobType::jobTypeName($jobTypeId) ?></b>
										</div>
										<?php
										$i = 1;
										foreach ($steps as $n) : ?>
											<div class="col-1 text-center"><?= $i ?></div>
											<div class="col-11"><?= $n["stepName"] ?></div>
										<?php
											$i++;
										endforeach;
										?>
									</div>
								</div>
							<?php
							endforeach;
							?>
						<?php


						}
						if (isset($update) & count($update) > 0) { ?>
							<div class="col-12 mt-10 pl-20 pb-10 font-size16 font-weight-bold ">
								Update Step : <?= $updateStep ?> records.

							</div>
							<?php
							foreach ($update as $jobTypeId => $steps) :
							?>
								<div class="col-12 mt-10 pl-40 pb-10 font-size14">
									<div class="row">
										<div class="col-12 font-size16">
											<b><?= JobType::jobTypeName($jobTypeId) ?></b>
										</div>
										<?php
										$i = 1;
										foreach ($steps as $n) : ?>
											<div class="col-1 text-center"><?= $i ?></div>
											<div class="col-11"><?= $n["stepName"] ?></div>
										<?php
											$i++;
										endforeach;
										?>
									</div>
								</div>
					<?php
							endforeach;
						}
					}
					?>
				</div>
			</div>


		</div>

	</div>

</div>
<?php ActiveForm::end(); ?>