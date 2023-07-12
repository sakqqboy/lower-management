<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;
use kartik\date\DatePicker;
?>
<?php
$message = "Month";
if ($jobCate["categoryName"] != "Spot") {
	if ($jobCate["categoryName"] == "Half year") {
		$message = "Month of due date";
	}
	if ($jobCate["categoryName"] == "Yearly") {
		$message = "End of Month of accounting term";
	}
	if ($jobCate["categoryName"] == "Monthly") {
		$message = "Month of order ";
	}
	if ($jobCate["categoryName"] == "Quaterly") {
		$message = "Month of due date ";
	}
}
?>
<div class="col-12">
	<hr>
</div>
<div class="row">
	<div class="col-lg-6 col-md-6 col-12 mt-20 font-size18 ">
		<b>Category : : <?= $jobCate["categoryName"] ?>&nbsp;&nbsp; Target Date</b>&nbsp;&nbsp;
		<b>[</b>
		<i class="fa fa-edit ml-10 change-job-name" aria-hidden="true" id="change-job-category"> <u>change Category</u></i>
		&nbsp;<b>]</b>

	</div>

	<div class="col-lg-4 col-md-4 col-12 mt-20 ">
		<label class="label-input"><?= $message ?></label>
		<input type="text" name="currentMonth" id="startMonth0" placeholder="<?= $message ?>" class="form-control" readonly onclick="javascript:showMonthCalendar(0)" required value="<?= $jobCate["startMonthText"] ?>">
		<div class="col-12 month-calendar-box" id="month-calendar0">
			<?= $this->render('month_calendar', ["i" => 0]) ?>
		</div>

	</div>
	<div class="col-lg-2 col-md-2 col-12 mt-20">
		<label class="label-input">Fiscal Year</label>
		<input type="text" name="fiscalYear" placeholder="fiscalYear" class="form-control" required value="<?= $jobCate["fiscalYear"] ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" id="fiscalYear">
	</div>
</div>
<div class="row mt-20">
	<div class="col-3 pt-1" style="z-index:10;">
		<?php
		$completeStep = JobStep::stepComplete($jobId);
		$class = [];
		if (isset($jobCate["targetDate"]) && $jobCate["targetDate"] != null) {
			$targetDate = explode(" ", $jobCate["targetDate"]);
			$class = JobCategory::createClass($jobCate["status"], $jobCate["targetDate"]);
		}
		if (isset($jobCate["firstTargetDate"]) && $jobCate["firstTargetDate"] != null) {
			$firstTargetDate = explode(" ", $jobCate["firstTargetDate"]);
		}
		?>
		<label class="label-input">Target Date</label>
		<?= DatePicker::widget([
			'name' => 'targetDateSetFirst',
			'disabled' => true,
			'id' => 'newTargetDate2',
			'type' => DatePicker::TYPE_INPUT,
			'value' => isset($firstTargetDate) && !empty($firstTargetDate) ? $firstTargetDate[0] : null,
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
	<div class="col-3 pt-1" style="z-index:10;">
		<?php
		$completeStep = JobStep::stepComplete($jobId);
		$class = [];
		if (isset($jobCate["targetDate"]) && $jobCate["targetDate"] != null) {
			$targetDate = explode(" ", $jobCate["targetDate"]);
			$class = JobCategory::createClass($jobCate["status"], $jobCate["targetDate"]);
		}
		?>
		<label class="label-input">Lastest update target date</label>
		<?= DatePicker::widget([
			'name' => 'targetDate',
			'id' => 'newTargetDate',
			'type' => DatePicker::TYPE_INPUT,
			'value' => isset($targetDate) && !empty($targetDate) ? $targetDate[0] : null,
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
	<div class="col-2 pt-2 text-center mt-30">
		<div class="col-12 pt-1 pb-1 <?= isset($class["class"]) ? $class["class"] : '' ?> ">

			<?= JobCategory::statusText($jobCate["status"]) ?>
			<?= isset($class["over"]) && $class["over"] == 1 && $jobCate["status"] == JobCategory::STATUS_INPROCESS ? '(over)' : '' ?>

		</div>

	</div>
	<div class="col-2 text-right pt-20 ">
		<div class="col-12 mt-30" style="display:<?= $completeStep == 1 ? '' : 'none' ?>" id="jobCateTarget">
			<?php
			//if ($completeStep == 1) { //every jobs step were complete
			?>
			<input type="checkbox" class="checkbox-sm" name="completeTarget" value="<?= $jobCate["jobCateId"] ?>" id="completeTarget" <?= $jobCate["status"] == JobCategory::STATUS_COMPLETE ? 'checked' : '' ?>>
			<span class="label-default">Complete</span>
			<?php
			//}
			?>
		</div>
		<input type="hidden" name="jcId" value="<?= $jobCate["jobCateId"] ?>" id="jcId">
	</div>
	<div class="col-2 pt-2 text-right">
		<div class="row">
			<input type="hidden" name="hasMore" value="<?= $hasMore ?>">
			<?php
			if ($hasMore == 1 || $jobCate["categoryName"] == "Monthly" || $jobCate["categoryName"] == "Yearly" || $jobCate["categoryName"] == "Quarterly") {

				if ($jobCate["categoryName"] != "Spot") {

			?>
					<input type="hidden" name="cateName2" value="<?= $jobCate["categoryName"] ?>">
					<div class="col-12 mt-30" style="display:<?= $completeStep == 1 ? '' : 'none' ?>" id="jobCateTargetNext">
						<a class="btn <?= $jobStatus == Job::STATUS_COMPLETE ? 'button-red' : 'button-blue' ?>" href="<?= Yii::$app->homeUrl ?>job/detail/next-target/<?= ModelMaster::encodeParams([
																												"jobCategoryId" => $jobCate["jobCateId"],
																												"previousUrl" => $previousUrl
																											]) ?>">
							<i class="fa fa-arrow-right" aria-hidden="true"></i> &nbsp;
							Next Target<?= $jobStatus == Job::STATUS_COMPLETE ? ' !!!' : '' ?>
						</a>

					</div>
			<?php
				}
			}
			?>
		</div>
	</div>

</div>
<div class="col-lg-8 col-12 border mt-30 pt-10 pb-20" id="job-category-zone" style="display:none;">
	<div class="row">
		<input type="hidden" id="isAlert" value="0">
		<div class="col-12 text-right font-size16">
			<i class="fa fa-times  change-job-name text-danger" aria-hidden="true" id="cancel-change-job-category"></i>
		</div>
		<div class="col-lg-4 col-md-5 col-6 text-left mt-10">
			<select class="form-control" name="category">
				<option value="">Category</option>
				<?php
				if (isset($category) && count($category)) {
					foreach ($category as $c) : ?>
						<option value="<?= $c["categoryId"] ?>"><?= $c["categoryName"] ?></option>
				<?php
					endforeach;
				}
				?>
			</select>
		</div>
		<div class="col-lg-8 col-md-7 col-6 text-left mt-10" id="month-set">
			<input type="text" name="startMonth" id="startMonth1" placeholder="Start Month" class="form-control" readonly onclick="javascript:showMonthCalendar(1)">
			<div class="col-12 month-calendar-box" id="month-calendar1">
				<?= $this->render('month_calendar', ["i" => 1]) ?>
			</div>

		</div>
	</div>
</div>
<div class="row border-top mt-40 pb-10 pt-10">
	<div class="col-4 mt-30 text-left font-size18">
		<input type="checkbox" class="checkbox-sm mr-10" name="report" <?= $report == 1 ? 'checked' : '' ?>>Need business report
	</div>
	<div class="col-4 mt-30 text-right font-size18">
		<input type="checkbox" class="checkbox-sm mr-10" id="submit-report" name="submitReport" <?= $isSubmitReport == 1 ? 'checked Disabled' : '' ?> onchange="javascript:showSubmitDate(<?= $jobId ?>,<?= $jobCate['jobCateId'] ?>)">
		<?= $isSubmitReport == 1 ? 'Submit already' : 'Submit business report' ?>
		<?php
		if ($submitDate != '') { ?>
			<span class="font-size12">( <?= $submitDate ?> )</span>
		<?php
		}
		?>
	</div>
	<div class="col-4 mt-20 text-right">
		<a href="<?= Yii::$app->homeUrl ?>job/detail/export-issue?jc=<?= $jobCate["jobCateId"] ?>" class="btn button-turqouise no-underline"> Export </a>
	</div>
</div>