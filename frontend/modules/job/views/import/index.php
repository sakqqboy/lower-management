<?php

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
$this->title = "Import Job";
?>
<div class="body-content  pt-20 container">
	<div class="row">
		<div class="col-12 mb-20">
			<span class="font-size20 font-weight-bold ">Upload Jobs excel file</span>
		</div>
		<div class="col-12 border pt-20 pb-20 pr-30">
			<div class="row">
				<div class="col-lg-5 col-md-5 col-12">
					<div class="col-lg-12 col-md-6 col-12 mb-20">
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
										<input type="file" name="jobFile" class="upload up" id="up" onchange="readURL(this);" />
									</span><!-- btn-orange -->
								</div><!-- btn -->
							</div><!-- group -->
						</div><!-- form-group -->
					</div>
					<div class="col-lg-12 mt-20">
						<button class="btn button-sky" type="submit"><i class="fa fa-upload mr-1" aria-hidden="true"></i> UPLOAD</button>
					</div>
					<div class="col-12 mt-20">
						<b>Preparing data carefully points</b><br>
						<div class="col-12 mt-10 font-size14">
							<div class="row">
								<div class="col-3 pl-0 pr-0">1. "Client"</div>
								<div class="col-9 pl-0 pr-0"> &#187; Copy client's name from each branch
									<a href="<?= Yii::$app->homeUrl ?>client/default/client-list" target="_blank"><b>client</b>.</a>
								</div>
							</div>
						</div>
						<div class="col-12 mt-10 font-size14">
							<div class="row">
								<div class="col-3 pl-0 pr-0">2. "Field"</div>
								<div class="col-9 pl-0 pr-0"> &#187; Copy fild's name from each branch
									<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/field" target="_blank"><b>field</b>.</a>
								</div>
							</div>
						</div>
						<div class="col-12 mt-10 font-size14">
							<div class="row">
								<div class="col-3 pl-0 pr-0">3. "Category"</div>
								<div class="col-9 pl-0 pr-0"> &#187; "Monthly","Yearly","Half year","Spot".</div>
							</div>
						</div>
						<div class="col-12 mt-10 font-size14">
							<div class="row">
								<div class="col-3 pl-0 pr-0">4. "Job Type"</div>
								<div class="col-9 pl-0 pr-0">&#187; Copy job type name from each branch
									<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/job-type" target="_blank"><b>job type</b></a>.
								</div>
							</div>

						</div>
						<div class="col-12 mt-10 font-size14">
							<div class="row">
								<div class="col-3 pl-0 pr-0">5. "Month"</div>
								<div class="col-9 pl-0 pr-0"> &#187; 1 - 12</div>
							</div>

						</div>
						<div class="col-12 mt-10 font-size14">
							<div class="row">
								<div class="col-3 pl-0 pr-0">6. "Team"</div>
								<div class="col-9 pl-0 pr-0"> &#187; Copy team name form each branch
									<a href="<?= Yii::$app->homeUrl ?>setting/structure/team" target="_blank"><b>team</b></a>.
								</div>
							</div>
						</div>
						<div class="col-12 mt-10 font-size14">
							<div class="row">
								<div class="col-3 pl-0 pr-0">7. "Currency code"</div>
								<div class="col-9 pl-0 pr-0"> &#187; Just 3 letters (all caps, Ex. USD, THB, JPY)<br>
								</div>
							</div>
						</div>
						<div class="col-12 mt-20 font-size16 font-weight-bold text-center">
							* * * All fields cannot be empty * * *
						</div>
						<div class="col-12 mt-14 font-size16 text-right mt-10">
							<a href="<?= Yii::$app->homeUrl ?>file/job/master/importJob.xlsx" class="no-underline-black ">
								&#187; &#187; &#187; Download import jobs master file &#171; &#171; &#171;
							</a>
						</div>


					</div>
				</div>
				<div class="col-lg-7 col-md-7 col-12 pt-10 upload-result">
					<span class="font-size18 font-weight-bold ">Result</span>
					<?php
					if ($total != '') {
						echo $total . ' Records';
					}
					//throw new Exception($total);
					if (isset($success) & count($success) > 0) { ?>
						<div class="col-12 mt-10 pl-20 pb-10 font-size16 font-weight-bold ">
							Imported : <?= count($success) ?> jobs.

						</div>
						<div class="col-12 mt-10 pl-40 pb-10 font-size14">
							<div class="row">
								<?php
								$i = 1;
								foreach ($success as $s) : ?>
									<div class="col-4"><?= $i . '. ' . $s["jobName"] ?></div>
									<div class="col-4"><?= $s["clientName"] ?></div>
									<div class="col-2"><?= $s["categoryName"] ?></div>
									<div class="col-2"><?= $s["jobType"] ?></div>
								<?php
									$i++;
								endforeach;
								?>
							</div>
						</div>
					<?php


					}
					if (isset($fail) & count($fail) > 0) { ?>
						<div class="col-12 mt-10 pl-20 pb-10 font-size16 font-weight-bold ">
							Fail : <?= count($fail) ?> jobs,<br> please check the accuracy of the information.

						</div>
						<div class="col-12 mt-10 pl-40 pb-10 font-size14 text-danger">
							<div class="row">
								<?php
								$i = 1;
								foreach ($fail as $index => $f) : ?>
									<div class="col-3"><?= "(" . ($index + 1) . ")"  . $f["jobName"] ?></div>
									<div class="col-5"><?= $f["clientName"] ?></div>
									<div class="col-4"><?= $f["error"] ?></div>
								<?php
									$i++;
								endforeach;
								?>
							</div>
						</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php ActiveForm::end(); ?>