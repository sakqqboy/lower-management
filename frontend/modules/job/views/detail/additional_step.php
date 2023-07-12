<?php

use frontend\models\lower_management\AdditionalStep;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobTypeStep;
use kartik\date\DatePicker;

$a = 1;
$total = count($additional);
$lastStatus = "";
$showNextStep = 0;
foreach ($additional as $add) :
	$id = Yii::$app->security->generateRandomString(12);
	$class = JobStep::createClass($add["status"], $add["dueDate"]);
	$showNextStep = AdditionalStep::isShowNextStep($jobStepId, $add["additionalStepId"]);
	if ($showNextStep == 0) {
		$lastStatus = "none";
	}
?>
	<div class="row mt-10" id="add-<?= $add["additionalStepId"] ?>-<?= $id ?>">
		<div class="col-3 pl-40 ">
			<input type="text" value="<?= $add["additionalStepName"] ?>" class="form-control" name="additionalStep[<?= $add["additionalStepId"] ?>]" required>
		</div>
		<div class="col-2 " style="z-index:10;">
			<?php
			if (isset($add["firstDueDate"]) && $add["firstDueDate"] != null) {
				$firstDueDate = explode(" ", $add["firstDueDate"]);
			} else {
				$firstDueDate[0] = null;
			}
			?>
			<?= DatePicker::widget([
				'name' => 'firstSubDueDate[' . $add["additionalStepId"] . ']',
				'type' => DatePicker::TYPE_INPUT,

				'disabled' => true,
				'value' => isset($firstDueDate[0]) ? $firstDueDate[0] : null,
				'pluginOptions' => [
					'autoclose' => true,
					'format' => 'yyyy-mm-dd',
				],
				'options' => ['class' => 'form-control text-center']
			]); ?>

		</div>
		<div class="col-2 " style="z-index:10;">
			<?php
			if (isset($add["dueDate"]) && $add["dueDate"] != null) {
				$date = explode(" ", $add["dueDate"]);
			} else {
				$date[0] = null;
			}
			?>
			<?= DatePicker::widget([
				'name' => 'subDueDate[' . $add["additionalStepId"] . ']',
				'type' => DatePicker::TYPE_INPUT,

				'disabled' => $add["status"] == JobStep::STATUS_COMPLETE ? true : false,
				'value' => isset($date[0]) ? $date[0] : null,
				'pluginOptions' => [
					'autoclose' => true,
					'format' => 'yyyy-mm-dd',
				],
				'options' => ['class' => 'form-control text-center']
			]); ?>

		</div>
		<div class="col-1 text-center pt-2">
			<img src="<?= Yii::$app->homeUrl ?>images/icon/crossed.png" class="add-image-step" onclick="javascript:deleteOldAdditionalStep(<?= $add['additionalStepId'] ?>,'<?= $id ?>')">
		</div>
		<div class="col-2 text-center pt-1">
			<div class="col-12 pt-1 pb-1 <?= $class["class"] ?> ">

				<?php
				if ($add["dueDate"] == null) {
					$text = 'Not set due date';
				} else {
					$text = '(over)';
				}
				?>
				<?php
				if ($class["over"] == 0 && $add["dueDate"] == NULL) { ?>
					Not set due date
				<?php
				} else { ?>
					<?= JobStep::StatusText($add["status"]) ?>
					<?= $class["over"] == 1 && $add["status"] == JobStep::STATUS_INPROCESS ? $text : '' ?>
				<?php
				}
				?>


			</div>
		</div>

		<div class="col-2 text-right pt-10 pl-5">
			<?php

			if ($add["status"] == JobStep::STATUS_INPROCESS && $add["dueDate"] != null) {
			?>
				<input type="checkbox" class="checkbox-sm mt-2" style="display:<?= $lastStatus ?>" name="subComplete[<?= $add["additionalStepId"] ?>]" value="<?= $add["additionalStepId"] ?>" id="subJobStep-<?= $i ?>-<?= $a ?>" onchange="javascript:showCompleteSubStep(<?= $i ?>,<?= $total ?>,<?= $a ?>)">
				<input type="hidden" name="currrentSubStep" value="<?= $add["additionalStepId"] ?>">
				<?php
				$isCancel = AdditionalStep::isCancel($add["additionalStepId"]);
				if ($isCancel == 1) {
				?>
					<a href="javascript:cancelSubDetail(<?= $add["additionalStepId"] ?>)" class="font-size14 p-2 no-underline">
						<i class="fa fa-info" aria-hidden="true"></i>&nbsp;Cancel Detail
					</a>
				<?php }
			}
			if ($add["status"] == JobStep::STATUS_COMPLETE) { ?>
				<i class="fa fa-check text-success font-size16 mr-10" aria-hidden="true"></i>
				<?php
				//$totalSubStepComplete = AdditionalStep::CountCompleteStep($add["jobId"], $step["stepId"], $add["jobCategoryId"]);
				//if ($a ==  $totalSubStepComplete) 
				if (isset($canCancel["add"]) && $canCancel["add"] == $add["additionalStepId"]) {
				?>
					<input type="hidden" id="additionalStepId" value="<?= $add["additionalStepId"] ?>">
					<a class="button-red font-size12 p-2 no-underline" id="cancel-sub-complete">
						<i class="fa fa-times" aria-hidden="true"></i>&nbsp;Cancel complete
					</a>
			<?php
				}
			}
			?>
		</div>
	</div>
<?php
	$a++;
	if ($add["status"] == JobStep::STATUS_INPROCESS) {
		$lastStatus = "none";
	} else {
		$lastStatus = "";
	}
endforeach;
?>
<input type="hidden" value="<?= $total ?>" id="totalSubStep-<?= $i ?>">