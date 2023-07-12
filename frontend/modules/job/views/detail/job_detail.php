<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\SubFieldGroup;
use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;

$this->title = 'Job Detail';
?>
<div class="body-content pt-20 container">
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-6 col-7">
			<div class="row">
				<div class="col-12 job-name-detail">
					<span id="job-name">
						<span id="new-name"><?= $job["jobName"] ?></span>
						<i class="fa fa-edit ml-10 change-job-name" aria-hidden="true" id="change-job-name"></i>
					</span>
					<div class="row edit-job-name" id="edit-job-name">
						<div class="col-10">
							<input type="text" value="<?= $job["jobName"] ?>" class="form-control font-size26 font-weight-bold" id="new-job-Name">
						</div>
						<div class="col-2 font-size26 pt-10">
							<i class="fa fa-check  change-job-name text-success" aria-hidden="true" id="confirm-job-name"></i>
							<i class="fa fa-times ml-20 change-job-name text-danger" aria-hidden="true" id="cancel-change-job-name"></i>
						</div>
					</div>
				</div>
				<div class="col-12 client-name-detail mt-20">
					Client : : <?= $job["clientName"] ?>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-5 pt-10" id="add-complain">
			<div class="row">
				<div class="col-1">

				</div>
				<div class="col-3 complain-pic">
					<img src="<?= YIi::$app->homeUrl ?>images/icon/complain.png" class="pic-complain">
				</div>
				<div class="col-8 complain-text text-left">
					Complain & Mistake
				</div>

			</div>
		</div>
		<div class="col-3 mt-20 text-left">
			Start Date : <?= $job["startDate"] != null ? ModelMaster::engDate($job["startDate"] . " 00:00:00", 1) : 'not set' ?>
		</div>
		<div class="col-9 mt-10 text-right complain-massege" id="complain-text">
			<?= $textComplain ?>
		</div>

	</div>
	<?php $form = ActiveForm::begin([
		'options' => [
			'class' => 'panel panel-default form-horizontal',
			'enctype' => 'multipart/form-data',
			'method' => 'post',
			'id' => 'detail-job-form',
		],
		'action' => Yii::$app->homeUrl . 'job/detail/update-job'
	]); ?>
	<div class="row">
		<div class="col-lg-3 col-md-5 col-sm-6 col-6 mt-20">
			<?php
			if (isset($groupFields) && count($groupFields) > 0) { ?>
				<select class="form-control" name="fieldGroup" id="groupField" onchange="fieldInGroup();">
					<?php
					if ($defaultGroup != null && $defaultGroup != '') { ?>
						<option value="<?= $defaultGroup ?>">
							<?= SubFieldGroup::subFieldGroupName($defaultGroup) ?>
						</option>
					<?php
					} else { ?>
						<option value="">Field Group</option>
					<?php
					}
					$groupIndex = 1;
					foreach ($groupFields as $groupId => $sub) : ?>
						<option value="" disabled style="background-color: lightgray;font-weight:bolder;">
							<?= $groupIndex ?>.&nbsp;&nbsp;<?= FieldGroup::fieldGroupName($groupId) ?>
						</option>
						<?php
						if (count($sub) > 0) {
							$subIndex = 1;
							foreach ($sub as $subGroupId => $subGroup) : ?>
								<option value="<?= $subGroupId ?>">&nbsp;&nbsp;&nbsp;
									<?= $groupIndex . '.' . $subIndex ?>&nbsp;&nbsp;<?= $subGroup["name"] ?>
								</option>
					<?php
								$subIndex++;
							endforeach;
						}
						$groupIndex++;
					endforeach;

					?>
				</select>
			<?php
			} ?>
		</div>
		<div class="col-lg-3 col-md-5 col-sm-6 col-6 mt-20">
			<select class="form-control" name="field" id="all-field" required>
				<?php
				if (isset($fields) && count($fields) > 0) { ?>
					<option value="<?= $job['fieldId'] ?>">
						<?= Field::fieldName($job["fieldId"]) ?>
					</option>
					<?php
					foreach ($fields as $b) : ?>
						<option value="<?= $b['fieldId'] ?>"><?= $b['fieldName'] ?></option>
					<?php
					endforeach;

					?>
				<?php
				} ?>
			</select>
		</div>
		<?= $this->render('step_due', [
			"jobStep" => $jobStep,
			"job" => $job,
			"totalStepComplete" => $totalStepComplete,
			"jobTypes" => $jobTypes,
			"canCancel" => $canCancel,
			"relateJob" => $relateJob
		]) ?>

	</div>
	<div class="col-12 mt-20">
		<?= $this->render('category_target', [
			"jobCate" => $jobCate,
			"jobId" => $job["jobId"],
			"hasMore" => $hasMore,
			"category" => $category,
			"jobStatus" => $job["status"],
			"report" => $job["report"],
			"previousUrl" => $previousUrl,
			"isSubmitReport" => $isSubmitReport,
			"submitDate" => $submitDate
		]) ?>
	</div>
	<div class="row mt-20">
		<input type="hidden" id="job-branch" value="<?= $job["branchId"] ?>">
		<?= $this->render('response_person', [
			"response" => $response,
			"job" => $job,
			"email" => $email,
			"team" => $team,
			"pic" => $pic,
			"currentEmail" => $currentEmail,
			"approver" => $approver,
			"jobApprover" => $jobApprover
		]) ?>

	</div>
	<div class="mb-40">
		<?= $this->render('fee', [
			"job" => $job,
			"currency" => $currency
		]) ?>

	</div>
	<div class="row mb-50">
		<div class="col-12  text-right">
			<button type="submit" <?= $job["status"] == Job::STATUS_COMPLETE ? "disabled" : '' ?> class="btn button-update" id="send-approve">
				<img src="<?= YIi::$app->homeUrl ?>images/icon/check.png" class="submit-button">
				<b>Send Approve</b>
			</button>
		</div>
	</div>
	<input type="hidden" name="jId" value="<?= $job["jobId"] ?>" id="jId">
	<input type="hidden" name="pUrl" value="<?= $previousUrl ?>">
	<?php ActiveForm::end(); ?>
</div>
<div class="col-12">
	<?= $this->render('complain', ["jobId" => $job["jobId"]]) ?>
</div>
<div class="col-12">
	<?= $this->render('reason') ?>
</div>
<div class="col-12">
	<?= $this->render('reason_add') ?>
</div>
<div class="col-12">
	<?= $this->render('cancel_detail') ?>
</div>
<div class="col-12">
	<?= $this->render('add_comment') ?>
</div>
<div class="col-12">
	<?= $this->render('show_comment') ?>
</div>
<div class="col-12">
	<?= $this->render('submit_date') ?>
</div>
<?php
echo $this->render('document', [
	"jobTypeId" => $job["jobTypeId"]
]);
?>