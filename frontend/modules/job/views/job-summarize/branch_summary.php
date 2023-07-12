<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Job;
use yii\bootstrap4\ActiveForm;

$this->title = 'Summarize';
?>
<div class="body-content pt-20 mb-50">
	<?php
	$form = ActiveForm::begin([
		'id' => 'filter-job-type',
		'method' => 'post',
		'options' => [
			'enctype' => 'multipart/form-data',
		],
	]);
	?>
	<div class="col-12  pt-20 filter-row">
		<?= $this->render('filter', [
			"currentMonth" => $currentMonth,
			"currentYear" => $year,
			"category" => $category,
			"fields" => $fields,
			"teams" => $teams,
			"cleints" => $clients,
			"branchId" => $branchId,
			"clients" => $clients,
			"fieldId" => $fieldId,
			"categoryId" => $categoryId,
			"clientId" => $clientId,
			"teamId" => $teamId,
		])
		?>
	</div>
	<?php ActiveForm::end(); ?>
	<div class="col-12 font-size28 font-weight-bold mt-20 mb-20 text-center">
		<?= $branchName ?>
		<?php
		if ($branchFlag != '') { ?>
			<img src="<?= Yii::$app->homeUrl ?>images/flag/<?= $branchFlag ?>" class="flag-normal ml-20">
		<?php
		}
		?>
	</div>
	<div class="col-12">

		<table class="table table-hover">
			<thead>
				<tr>
					<th>No.</th>
					<th>Job Type</th>
					<th class="text-center">Total Inprocess</th>
					<th class="text-center">Total Finished</th>
				<tr>
			</thead>
			<tbody>
				<?php
				$totalInprocess = 0;
				$totalFinished = 0;
				if (isset($jobTypeBranch) && count($jobTypeBranch) > 0) {
					$i = 1;
					foreach ($jobTypeBranch as $jobTypeId => $jobType) : ?>
						<tr>
							<td><?= $i ?></td>
							<td><?= $jobType["jobTypeName"] ?></td>
							<td class="text-center">
								<b>
									<?php
									if ($jobType["totalInprocess"] > 0) {
									?>
										<a href="<?= Yii::$app->homeUrl ?>job/job-summarize/list-job-type/<?= ModelMaster::encodeParams([
																					"jobTypeId" => $jobTypeId,
																					"fiscalYear" => $year,
																					"status" => Job::STATUS_INPROCESS,
																					"fieldId" => $fieldId,
																					"categoryId" => $categoryId,
																					"clientId" => $clientId,
																					"teamId" => $teamId,
																					"currentMonthValue" => $currentMonthValue,
																					"branchId" => $branchId
																				]) ?>">
											<?= $jobType["totalInprocess"] ?>
										</a>
									<?php
									} else { ?>
										<?= $jobType["totalInprocess"] ?>
									<?php
									}
									?>
								</b>
							</td>
							<td class="text-center">
								<b>
									<?php
									if ($jobType["totalFinished"] > 0) {
									?>
										<a href="<?= Yii::$app->homeUrl ?>job/job-summarize/list-job-type/<?= ModelMaster::encodeParams([
																					"jobTypeId" => $jobTypeId,
																					"fiscalYear" => $year,
																					"status" => Job::STATUS_COMPLETE,
																					"fieldId" => $fieldId,
																					"categoryId" => $categoryId,
																					"clientId" => $clientId,
																					"teamId" => $teamId,
																					"currentMonthValue" => $currentMonthValue,
																					"branchId" => $branchId
																				]) ?>">
											<?= $jobType["totalFinished"] ?>
										</a>
									<?php
									} else { ?>
										<?= $jobType["totalFinished"] ?>
									<?php
									}
									?>
								</b>
							</td>
						</tr>
				<?php
						$totalInprocess += $jobType["totalInprocess"];
						$totalFinished += $jobType["totalFinished"];
						$i++;
					endforeach;
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td><b>Total</b></td>
					<td class="text-center"><b><?= number_format($totalInprocess) ?></b></td>
					<td class="text-center"><b><?= number_format($totalFinished) ?></b></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>