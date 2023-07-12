<?php

use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobTypeStep;
use kartik\date\DatePicker;
?>
<div class="col-12">
	<hr>
</div>
<div class="col-lg-9 col-md-9 col-sm-8 col-12">
	<div class="row">
		<?php
		if ($job["checkListPath"] != null) {
		?>
			<div class="col-12 mt-20">
				<b>Manual and check list</b>
			</div>

			<div class="col-12 mt-10 font-size16">
				Check list file :
				<a href="<?= Yii::$app->homeUrl . $job["checkListPath"] ?>" class="no-underline-black">
					<?= $job["jobName"] ?>
					<span class="font-size14">
						<< Download </span>
				</a>

			</div>

		<?php
		} else { ?>
			<div class="col-12 mt-20">
				<b>Upload Manual and check list file.</b>
			</div>
		<?php

		}
		?>
		<div class="col-8 mt-20">
			<div class="form-group">
				<div class="input-group">
					<input type="text" class="form-control" readonly>
					<div class="input-group-btn">
						<span class="fileUpload btn button-blue">
							<div id="upload" class="uplode-btn"><i class="fa fa-file mr-10" aria-hidden="true"></i>Check list File .xlsx</div>
							<input type="file" name="checkList" class="upload up" id="up" onchange="readURL(this);" />
						</span><!-- btn-orange -->
					</div><!-- btn -->
				</div><!-- group -->
			</div><!-- form-group -->
		</div>
	</div>
</div>
<div class="col-lg-3 col-md-3 col-sm-4 text-left z-index-10">
	<label class="label-input">Start Date</label>
	<?=
	DatePicker::widget([
		'name' => 'trueDate',
		'type' => DatePicker::TYPE_INPUT,
		'value' => $job["startDate"] != null ? explode(" ", $job["startDate"])[0] : null,
		'pluginOptions' => [
			'autoclose' => true,
			'format' => 'yyyy-mm-dd'
		]
	]);
	?>

</div>
<div class="col-12 mt-20 font-size18">
	<div class="row">
		<div class="col-4">
			<select class="form-control" onchange="javascript:changeJobType()" id="jobType">
				<option value="<?= $job["jobTypeName"] ?>"><?= $job["jobTypeName"] ?></option>
				<?php
				if (isset($jobTypes) & count($jobTypes) > 0) {

					foreach ($jobTypes as $jt) : ?>
						<option value="<?= $jt['jobTypeId'] ?>"><?= $jt['jobTypeName'] ?></option>
				<?php

					endforeach;
				}

				?>
			</select>
		</div>
		<div class="col-8">
			<b><a href="javascript:editDocument(<?= $job["jobTypeId"] ?>)" class="btn button-sky ml-10" id="see-document"><i class="fa fa-book mr-10" aria-hidden="true"></i>Documents List</a></b>
		</div>
	</div>
</div>
<div class="col-lg-12 mt-40">
	<div class="col-lg-12 mb-30 font-size16 head-step">
		<div class="row">
			<div class="col-3 text-left">
				Step
			</div>
			<div class="col-2 text-center">
				Due Date
			</div>
			<div class="col-2 text-center">
				Update Due Date
			</div>
			<div class="col-1 text-center">
				&nbsp;
			</div>
			<div class="col-2 text-center">
				Staus
			</div>
			<div class="col-2 text-right font-size18">
				Complete
			</div>
			<!-- <div class="col-2 text-center">
				Set next Step
			</div> -->
		</div>
	</div>
	<?php
	$m = 0;
	if (isset($jobStep) && count($jobStep) > 0) {
		$i = 1;

		$total = count($jobStep);
		$lastStatus = "";
		$showNextStep = 0;
		foreach ($jobStep as $jobStepId => $step) :
			$class = JobStep::createClass($step["status"], $step["dueDate"]);
			$showNextStep = JobStep::isShowNextStep($jobStepId);
			if ($showNextStep == 0) {
				$lastStatus = "none";
			}
	?>
			<div class="row mt-20">
				<div class="col-3 font-size14">
					<?= $i ?>. <?= $step["stepName"] ?>
				</div>
				<div class="col-2" style="z-index:10;">
					<?php
					if (isset($step["dueDate"]) && $step["dueDate"] != null) {
						$date = explode(" ", $step["dueDate"]);
					} else {
						$date[0] = null;
					}
					?>
					<?php
					if (isset($step["firstDueDate"]) && $step["firstDueDate"] != null) {
						$firstDate = explode(" ", $step["firstDueDate"]);
					} else {
						$firstDate[0] = null;
					}
					?>
					<?= DatePicker::widget([
						'name' => 'dueDateFirstSet[' . $jobStepId . ']',
						'type' => DatePicker::TYPE_INPUT,
						'disabled' => true,
						//'disabled' => $step["status"] == JobStep::STATUS_COMPLETE ? true : false,
						'value' =>  $firstDate[0] != '' ? $firstDate[0] : null,
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd',

						],
						'options' => [
							'class' => 'form-control text-center',
							'required' => true
						]
					]); ?>

				</div>
				<div class="col-2 text-center">
					<?= DatePicker::widget([
						'name' => 'dueDate[' . $jobStepId . ']',
						'type' => DatePicker::TYPE_INPUT,
						'disabled' => $step["status"] == JobStep::STATUS_COMPLETE ? true : false,
						'value' => isset($date[0]) ? $date[0] : null,
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd',
						],
						'options' => ['class' => 'form-control text-center']
					]); ?>

				</div>
				<div class="col-1 text-center pt-2">
					<span class="">
						<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image-step-update" onclick="javascript:additionalStepUpdate(<?= $step['stepId'] ?>)">
					</span>
					<?php
					if ($step["history"] == 1) {
					?>
						<span>
							<i class="fa fa-history pointer" aria-hidden="true" onclick="javascript:showStepHistory(<?= $jobStepId ?>,<?= $m ?>)"></i>
						</span>
						<div class="job-step-history-update" id="history-<?= $m ?>"></div>
					<?php
					}
					?>
				</div>
				<div class="col-2 text-center">
					<div class="col-12 font-size12 <?= $class["class"] ?> ">
						<?php
						if ($step["dueDate"] == null) {
							$text = 'Not set due date';
						} else {
							$text = '(over)';
						}
						?>
						<?php
						if ($class["over"] == 0 && $step["dueDate"] == NULL) { ?>
							Not set due date
						<?php
						} else { ?>
							<?= JobStep::StatusText($step["status"]) ?>
							<?= $class["over"] == 1 && $step["status"] == JobStep::STATUS_INPROCESS ? $text : '' ?>
						<?php
						}
						?>


					</div>
					<div class="col-12">
						<a href="javascript:showAddComment(<?= $jobStepId ?>)" class="btn button-yellow button-xs mt-1">
							<i class="fa fa-plus pointer" aria-hidden="true"></i> comment
						</a>

					</div>
				</div>

				<div class="col-2 text-right pt-10">
					<?php
					if ($step["status"] == JobStep::STATUS_INPROCESS && $step["dueDate"] != null) {
					?>
						<input type="checkbox" class="checkbox-sm mt-1" style="display:<?= $lastStatus ?>" name="complete[]" value="<?= $jobStepId ?>" id="jobStep<?= $i ?>" onchange="javascript:showCompleteJob(<?= $total ?>,<?= $i ?>)">
						<input type="hidden" name="currrentStep" value="<?= $jobStepId ?>">
						<input type="hidden" name="laststatus" value="<?= $lastStatus ?>">
						<?php
						if ($step["isCancel"] == 1) { ?>
							<a href="javascript:cancelDetail(<?= $jobStepId ?>)" class="font-size14 p-2 no-underline">
								<i class="fa fa-info" aria-hidden="true"></i>&nbsp;Cancel Detail
							</a>
						<?php }
					}

					if ($step["status"] == JobStep::STATUS_COMPLETE) { ?>
						<i class="fa fa-check text-success font-size16 mr-10" aria-hidden="true"></i>
						<?php
						//if ($i ==  $totalStepComplete) 
						if (isset($canCancel["jStep"]) && $canCancel["jStep"] == $jobStepId) { ?>
							<input type="hidden" id="stepCancel" value="<?= $jobStepId ?>">
							<a class="button-red font-size12 p-2 no-underline" id="cancel-complete">
								<i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel complete
							</a>
					<?php
						}
					}
					?>

				</div>

			</div>
			<input type="hidden" id="sort-<?= $step["stepId"] ?>" value="<?= count($step["additionalStep"]) ?>">
			<?php
			if (count($step["additionalStep"]) > 0) {

				echo $this->render('additional_step', [
					"additional" => $step["additionalStep"],
					"jobStepId" => $jobStepId,
					"step" => $step,
					"i" => $i,
					"canCancel" => $canCancel
				]);
			}
			?>
			<div id="sub-step-<?= $step["stepId"] ?>" class="row pb-10">
			</div>
	<?php
			$i++;
			$m++;
		/*if ($step["status"] == JobStep::STATUS_INPROCESS) {
				$lastStatus = "none";
			} else {
				$lastStatus = "";
			}*/
		endforeach;
	}
	?>

	<input type="hidden" id="total-step" value="<?= $m ?>">

</div>
<div class="col-12">
	<div class="row">
		<div class="col-6">
			<label class="label-input">Related job</label>
			<input type="text" name="relatedJob" id="relatedJob" class="form-control" placeholder="Related job url">
		</div>
		<div class="col-6">
			<?php
			if (isset($relateJob) && $relateJob != '') { ?>
				<label class="label-input">Related job</label>
				<div class="col-12 font-size12">
					<a href="<?= $job['relatedJob'] ?>" class="no-underline" target="_blank"><?= $relateJob ?></a>
				</div>
			<?php
			}

			?>


		</div>
	</div>


</div>