<?php

use common\models\ModelMaster;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Step;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;

$this->title = 'Lower management';
?>
<div class="body-content pt-20 mb-50">

	<div class="col-12  pt-20 filter-row">
		<?= $this->render('filter', [
			"branch" => $branch,
			"isManager" => $isManager,
			"currentMonth" => $currentMonth,
			"currentYear" => $currentYear,
			"fields" => $fields,
			"fieldId" => $fieldId,
			"branchId" => $branchId,
			"groupFieldId" => $groupFieldId,
			"groups" => $groups
		])
		?>
	</div>
	<div class="row">
		<?php
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $jobTypeId => $job) :
		?>
				<div class="col-12 mb-20 pt-20 pb-20 " style="border-bottom: lightgray solid thin;">
					<div class="row">
						<div class="col-6 font-size22 mb-10"><b><?= JobType::jobTypeName($jobTypeId) . $jobTypeId ?></b></div>
						<div class="col-6 font-size18 mb-10 text-right">
							<b>Total amount : <?= number_format(JobType::jobTypeAmount($jobTypeId), 2) ?> THB</b>

						</div>
					</div>
					<div class="col-12">
						<table class="table">
							<thead>
								<tr>
									<th>Status</th>
									<?php
									$i = 1;
									foreach ($job as $stepId => $js) : ?>
										<th class="text-center">
											Step <?= $i ?>
										</th>
									<?php
										$i++;
									endforeach;
									?>
									<!-- <th class="text-center">Complete</th> -->
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>Finished</th>
									<?php
									$total = 0;
									foreach ($job as $stepId => $js) :
										if (isset($js["finished"])) {
											$total += $js["finished"];
									?>
											<td class="text-center">
												<?php
												if ($js["finished"] > 0) {
												?>
													<a href="<?= Yii::$app->homeUrl ?>job/default/list-job-type/<?= ModelMaster::encodeParams([
																							"type" => "finished",
																							"jobTypeId" => $jobTypeId,
																							"stepId" => $stepId,
																							"currentMonth" => $currentMonth,
																							"currentYear" => $currentYear,
																						]) ?>">
														<?= $js["finished"] ?>
													</a>
												<?php
												} else {
													echo 0;
												}
												?>
											</td>
									<?php
										} else {
											echo '<td class="text-center">0</td>';
										}
										$i++;
									endforeach;
									?>
									<!-- <td class="text-center"><?php // $total 
														?></td> -->
								</tr>
								<tr>
									<th>On process</th>
									<?php
									$total = 0;
									foreach ($job as $jobStepId => $js) :
										if (isset($js["onProcess"])) {
											$total += $js["onProcess"];
									?>
											<td class="text-center">
												<?php
												if ($js["onProcess"] > 0) {
												?>
													<a href="<?= Yii::$app->homeUrl ?>job/default/list-job-type/<?= ModelMaster::encodeParams([
																							"type" => "onProcess",
																							"jobTypeId" => $jobTypeId,
																							"stepId" => $jobStepId,
																							"currentMonth" => $currentMonth,
																							"currentYear" => $currentYear,
																						]) ?>">
														<?= $js["onProcess"] ?>
													</a>
												<?php
												} else {
													echo 0;
												}
												?>
											</td>
									<?php
										} else {
											echo '<td class="text-center">0</td>';
										}
										$i++;
									endforeach;
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