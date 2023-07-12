<?php

use yii\bootstrap4\ActiveForm;
use kartik\date\DatePicker;

$this->title = 'Next Target';
?>
<div class="body-content pt-20 container mb-40">
	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="col-12 job-name-detail">
					<?= $job["jobName"] ?>
				</div>
				<div class="col-12 client-name-detail mt-20">
					Client : : <?= $job["clientName"] ?>
				</div>
			</div>
		</div>
		<div class="col-12">
			<hr>
		</div>
		<div class="col-12 font-size14 mt-10">
			<b>Job Type &nbsp;&nbsp;:&nbsp;&nbsp;<?= $job["jobTypeName"] ?></b>&nbsp;&nbsp;|&nbsp;&nbsp;
			<b>Category&nbsp;&nbsp;:&nbsp; &nbsp; <?= $job["categoryName"] ?></b>
		</div>

		<div class="col-12">
			<hr>
		</div>
		<div class="col-12 font-size16 mt-10">
			<b>Current Target&nbsp;&nbsp;:&nbsp;&nbsp;<?= $current["targetDate"] ?></b>&nbsp;&nbsp;|&nbsp;&nbsp;
			<b>Status&nbsp;&nbsp;:&nbsp; &nbsp; <?= $current["status"] ?></b>&nbsp;&nbsp;|&nbsp;&nbsp;
			<b>Target No.&nbsp;&nbsp;:&nbsp; &nbsp; <?= $current["targetNo"] ?></b>
			<?php
			if ($current["status"] == "Inprocess") { ?>
				<br><br>
				<span class="text-danger">
					* * If create next target date, Current target date status will changed to <b>"Complete"</b> automatically.
				</span>
			<?php
			}
			?>

		</div>
		<?php $form = ActiveForm::begin([
			'options' => [
				'class' => 'panel panel-default form-horizontal',
				'enctype' => 'multipart/form-data',
				'method' => 'post',
				'id' => 'detail-job-form',
			],
			'action' => Yii::$app->homeUrl . 'job/detail/create-next'
		]); ?>
		<div class="row">
			<div class="col-4">
				<div class="col-12  font-size16 mt-20 pt-1">
					<b>Next Target Date</b>
					<input type="hidden" value="<?= $nextJobCategory["jobCategoryId"] ?>" name="nextJobCategoryId">
					<input type="hidden" value="<?= $job["jobId"] ?>" name="jId">
					<input type="hidden" value="<?= $current["currentJobCategoryId"] ?>" name="currentJobCategoryId">
				</div>
				<div class="col-12 mt-20" style="z-index:10;">
					<?php
					if (isset($nextJobCategory["targetDate"])) {
						$date = explode(" ", $nextJobCategory["targetDate"]);
						$nextTargetDate = date('Y-m-d', strtotime($currentJobCategory["targetDate"] . "+1 month"));
					} else {
						$date[0] = null;
					}
					?>
					<?= DatePicker::widget([
						'name' => 'newTargetDate',
						'id' => 'newTargetDate',
						'type' => DatePicker::TYPE_INPUT,
						'value' =>  isset($nextTargetDate) ? $nextTargetDate : null,
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd'
						]
					]); ?>
				</div>
			</div>
			<div class="col-4">
				<div class="col-12  font-size16 mt-20 pt-1">
					<b>Fiscal Year</b>
				</div>
				<?php
				if (isset($fiscalYear) && $fiscalYear == null) {
					$fiscalYear = date('Y');
				}
				?>
				<div class="col-12 mt-20">
					<input type="text" class="form-control" value="<?= $fiscalYear ?>" placeholder="Fiscal Year" name="fiscalYear" id="fiscalYear" required>
				</div>
			</div>
		</div>



		<div class="col-12 font-size16 mt-20 pt-1">
			<b>Next Due Date</b>
		</div>
		<input type="hidden" name="pUrl" value="<?= $previousUrl ?>">
		<div class="col-12 font-size16 mt-20 pt-1">
			<div class="row">
				<?php
				$i = 1;
				foreach ($steps as $s) :
				?>
					<div class="col-3 mb-10">
						&nbsp;&nbsp;&nbsp;<?= $i ?> . <?= $s["stepName"] ?>
					</div>
					<div class="col-4 mb-10">
						<?= DatePicker::widget([
							'name' => 'stepDueDate[' . $s["stepId"] . ']',
							'id' => 'step' . $i,
							'value' => isset($nextDueDate[$s["stepId"]]) ? $nextDueDate[$s["stepId"]] : null,
							'type' => DatePicker::TYPE_INPUT,
							'options' => ['placeholder' => 'Select Due Date'],
							'pluginOptions' => [
								'autoclose' => true,
								'format' => 'yyyy-mm-dd'
							]
						]);
						?>
					</div>
					<div class="col-5 mb-10 text-right">
					</div>
					<?php
					if (isset($sub[$s["stepId"]]) && count($sub[$s["stepId"]]) > 0) { //subStep
						$j = 1;
						foreach ($sub[$s["stepId"]] as $additionalStepId => $data) : ?>
							<div class="col-3 mb-10 pl-40">
								&nbsp;&nbsp;&nbsp;<?= $i . '.' . $j ?> . <?= $data["name"] ?>
								<input type="hidden" name="subStepName[<?= $s["stepId"] ?>][<?= $data["sort"] ?>]" value="<?= $data["name"] ?>">
							</div>
							<div class="col-4 mb-10">
								<?= DatePicker::widget([
									'name' => 'subStepDueDate[' . $s["stepId"] . '][' . $data["sort"] . ']',
									'id' => $additionalStepId,
									'type' => DatePicker::TYPE_INPUT,
									'options' => ['placeholder' => 'Select Due Date'],
									'pluginOptions' => [
										'autoclose' => true,
										'format' => 'yyyy-mm-dd'
									]
								]);
								?>
							</div>
							<div class="col-5 mb-10 text-right">
							</div>
				<?php
							$j++;
						endforeach;
					}
					$i++;
				endforeach;

				?>
			</div>

		</div>
		<div class="col-12  mt-20 text-right">
			<button class="btn button-blue" id="send-approve" type="submit" onclick="javascript:disableButton()">
				<i class="fa fa-check" aria-hidden="true"></i> &nbsp;
				Create Next Due
			</button>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>