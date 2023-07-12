<?php

use common\models\ModelMaster;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Step;
use yii\bootstrap4\ActiveForm;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use kartik\date\DatePicker;

$this->title = 'Lower management';
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
			"branch" => $branch,
			"isManager" => $isManager,
			"currentMonth" => $currentMonth,
			"currentYear" => $currentYear,
			"branchId" => $branchId,
			"subFieldGroupId" => $subFieldGroupId,
			"jobTypes" => $jobTypes,
			"jobTypeId" => $jobTypeId,
			"groupFields" => $groupFields,
			"fields" => $fields,
			"fieldPostId" => $fieldPostId,
			"fieldId" => $fieldId,
			"isAdmin" => $isAdmin,
			"employeeBranch" => $employeeBranch,
		])
		?>
	</div>
	<div class="row">
		<div class="col-lg-9 col-md-9 col-6 mt-10 background-white mt-20"></div>
		<div class="col-lg-3 col-md-3 col-6 mt-10 background-white mt-20">
			<?=
			DatePicker::widget([
				'name' => 'checkDate',
				'type' => DatePicker::TYPE_INPUT,
				'id' => 'checkDate',
				'value' => $checkDate,
				'options' => [
					'placeholder' => 'Go to due date'
				],
				'pluginOptions' => [
					'autoclose' => true,
					'format' => 'yyyy-mm-dd',
				]
			]);
			?>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
	<div class="row">

		<?php
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $jobTypeId => $job) :
		?>
				<div class="col-12 mb-20 pt-20 pb-20 " style="border-bottom: lightgray solid thin;">
					<div class="row">
						<div class="col-6 font-size22 mb-10 pl-40"><b><?= JobType::jobTypeName($jobTypeId) ?></b></div>
						<div class="col-6 font-size18 mb-10 text-right">
							<!-- <b>Total amount : <?php // number_format(JobType::jobTypeAmount($jobTypeId), 2) 
											?> THB</b> -->

						</div>
					</div>
					<div class="col-12">
						<table class="table">
							<thead>
								<tr>
									<th>Status</th>
									<?php
									$steps = JobType::jobTypeStep($jobTypeId);
									if (count($steps) > 0) {
										$i = 1;
										foreach ($steps as $js) : ?>
											<th class="text-center">
												Step <?= $i ?>
											</th>
									<?php
											$i++;
										endforeach;
									}

									?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>Finished</th>
									<?php
									$total = 0;
									if (count($steps) > 0) {
										foreach ($steps as $jobS) :
											foreach ($job as $stepId => $js) :
												if (isset($js["finished"]) && $stepId == $jobS["stepId"]) {
													$total += $js["finished"];
									?>
													<td class="text-center">
														<?php
														if ($js["finished"] > 0) {
														?>
															<a href="<?= Yii::$app->homeUrl ?>job/job-summarize/list-job-type/<?= ModelMaster::encodeParams([
																										"type" => "finished",
																										"jobTypeId" => $jobTypeId,
																										"stepId" => $stepId,
																										"currentMonth" => $currentMonth["value"],
																										"currentYear" => $currentYear,
																										"checkDate" => $checkDate
																									]) ?>">
																<?= $js["finished"] ?>
															</a>
														<?php
														}
														?>
													</td>
												<?php
												} else if (!isset($js["finished"]) && $stepId == $jobS["stepId"]) { ?>
													<td class="text-center">0</td>
									<?php
												}
												$i++;
											endforeach;
										endforeach;
									}
									?>
									<!-- <td class="text-center"><?php // $total 
														?></td> -->
								</tr>
								<tr>
									<th>On process</th>
									<?php
									$total = 0;
									if (count($steps) > 0) {
										foreach ($steps as $jobS) :
											foreach ($job as $stepId => $js) :
												if (isset($js["onProcess"]) && $stepId == $jobS["stepId"]) {
													$total += $js["onProcess"];
									?>
													<td class="text-center">
														<?php
														if ($js["onProcess"] > 0) {
														?>
															<a href="<?= Yii::$app->homeUrl ?>job/job-summarize/list-job-type/<?= ModelMaster::encodeParams([
																										"type" => "onProcess",
																										"jobTypeId" => $jobTypeId,
																										"stepId" => $stepId,
																										"currentMonth" => $currentMonth["value"],
																										"currentYear" => $currentYear,
																										"checkDate" => $checkDate
																									]) ?>">
																<?= $js["onProcess"] ?>
															</a>
														<?php
														}
														?>
													</td>
												<?php
												} else if (!isset($js["onProcess"]) && $stepId == $jobS["stepId"]) { ?>
													<td class="text-center">0</td>
									<?php
												}
												$i++;
											endforeach;
										endforeach;
									}
									?>

								</tr>
							</tbody>
						</table>
					</div>
				</div>
		<?php
			endforeach;
		}
		?>

	</div>

</div>