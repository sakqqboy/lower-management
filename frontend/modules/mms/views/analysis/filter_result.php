<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\Team;

$this->title = 'Monthly Job Analysis';
?>
<div class="body-content pt-20 mb-50">
	<div class="col-12">
		<div class="row">
			<div class="col-lg-3">
				<div class="col-12 text-center font-size18  p-0">
					<select id="analysis-type" class="form-control text-left font-size16 font-weight-bold">
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/index">
							<b>Monthly Job Analysis </b>
						</option>
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/yearly">
							<b>Yearly Job Analysis </b>
						</option>
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/job-type">
							<b>Job Type Analysis </b>
						</option>
					</select>
				</div>
				<div class="col-12 text-center mt-10" style="padding: 0px;">
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th class="font-size12">Day</th>
								<?php
								$i = 0;
								foreach ($date as $dateData) :
								?>
									<th class="font-size12"><?= $dateData["year"] ?> <?= ModelMaster::shotMonthText((int)$dateData["month"]) ?>
										<div class="col-12 p-0 mt-1">
											<select class="filter-select" id="filterYear<?= $i ?>" onchange="javascript:filterAnalysisJob()">
												<option value="<?= $dateData["year"] ?>"><?= $dateData["year"] ?></option>
												<?php
												foreach ($jobCategoryFiscalYear as $year) : ?>
													<option value="<?= $year ?>"><?= $year ?></option>
												<?php
												endforeach
												?>

											</select>
										</div>
										<div class="col-12 p-0 mt-1">
											<select class="filter-select" id="filterMonth<?= $i ?>" onchange="javascript:filterAnalysisJob()">
												<option value="<?= $dateData["month"] ?>"><?= ModelMaster::shotMonthText((int)$dateData["month"]) ?></option>
												<?php

												foreach ($months as $index => $month) : ?>
													<option value="<?= $index ?>"><?= $month ?></option>
												<?php
												endforeach
												?>

											</select>
										</div>
									</th>
								<?php
									$i++;
								endforeach;
								?>
							</tr>
						</thead>
						<tbody>
							<?php
							if (count($data) > 0) {
								$period = 5;
								foreach ($data as $index => $dataYear) :
									if ($index == 0) {
										$text = '1 - 5';
									}
									if ($index == 1) {
										$text = '6 - 10';
									}
									if ($index == 2) {
										$text = '11 - 15';
									}
									if ($index == 3) {
										$text = '16 - 20';
									}
									if ($index == 4) {
										$text = '21 - 25';
									}
									if ($index == 5) {
										$text = '26 - 31';
									}
									if ($index == 6) {
										$text = 'Over';
									}
							?>
									<tr>
										<td><?= $text ?></td>
										<?php

										foreach ($dataYear as $year => $dataMonth) :
											//ksort($dataMonth);
											foreach ($dataMonth as $month => $value) : ?>
												<td>

													<?php
													if ($value > 0) {
													?>
														<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail1-monthly/<?= ModelMaster::encodeParams([
																								"year" => $date[$year]["year"],
																								"month" => $month,
																								"period" => $period,
																								"jobTypeId" => $jobTypeId,
																								"branchId" => $selectBranch["branchId"],
																								"teamId" => $teamId,
																								"personId" => $personId,
																								"stepId" => $stepId

																							]) ?>" class="no-underline-black">
															<?= $value ?>
														</a>
													<?php
													} else { ?>
														<?= $value ?>
													<?php
													}
													?>
													<br>
													<span class="font-size12 ">
														(<?= $total > 0 ? number_format(($value / $total) * 100, 2) : 0 ?>%)
													</span>
												</td>
										<?php
											endforeach;

										endforeach
										?>
									</tr>
								<?php
									$period += 5;
								endforeach; ?>

								<tr>
									<td><b>Total</b></td>
									<?php
									if (count($totalMonth) > 0) {
										foreach ($totalMonth as $year => $monthTotal) :
											foreach ($monthTotal as $month => $value) :
									?>
												<td>
													<?php
													if ($value > 0) {
													?>
														<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail1-monthly/<?= ModelMaster::encodeParams([
																								"year" => $date[$year]["year"],
																								"month" => $month,
																								"period" => 0,
																								"jobTypeId" => $jobTypeId,
																								"branchId" => $selectBranch["branchId"],
																								"teamId" => $teamId,
																								"personId" => $personId,
																								"stepId" => $stepId

																							]) ?>" class="no-underline-black">


															<?= $value ?>
														</a>
													<?php
													} else { ?>
														<?= $value ?>
													<?php
													}
													?>
													<br>
													<span class="font-size12 ">
														(<?= $total > 0 ? number_format(($value / $total) * 100, 2) : 0 ?>%)
													</span>
												</td>
									<?php
											endforeach;
										endforeach;
									}
									?>
								</tr>
							<?php
							}
							?>


						</tbody>

					</table>
				</div>

			</div>
			<div class="col-lg-9 ">
				<div class="row">
					<div class="col-lg-3">
						<select class="form-control" id="branchAnalysis" onchange="javascript:filterAnalysisJob()">
							<option value="<?= $selectBranch["branchId"] ?>"><?= $selectBranch["branchName"] ?></option>
							<?php
							if (count($branch) > 0) {
								foreach ($branch as $b) : ?>
									<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" id="teamAnalysis" onchange="javascript:filterAnalysisJob()">
							<?php
							if (isset($teamId) && $teamId != '') { ?>
								<option value="<?= $teamId ?>"><?= Team::teamName($teamId) ?></option>

							<?php
							} else { ?>
								<option value="">Team</option>
							<?php

							}
							?>

							<?php
							if (isset($teams) && count($teams) > 0) {
								foreach ($teams as $team) : ?>
									<option value="<?= $team['teamId'] ?>"><?= $team['teamName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" id="personAnalysis" onchange="javascript:filterAnalysisJob()">
							<?php
							if (isset($personId) && $personId != '') { ?>
								<option value="<?= $personId ?>"><?= Employee::employeeName($personId) ?></option>

							<?php
							} else { ?>
								<option value="">Person</option>
							<?php

							}
							?>

							<?php
							if (isset($persons) && count($persons) > 0) {
								foreach ($persons as $person) : ?>
									<option value="<?= $person['employeeId'] ?>"><?= $person['employeeNickName'] ?></option>
							<?php
								endforeach;
							}
							?>

						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" id="jobTypeAnalysis" onchange="javascript:filterAnalysisJob()">
							<option value="<?= $jobTypeId ?>"><?= $chartName ?></option>
							<?php
							if (count($jobType) > 0) {
								foreach ($jobType as $jt) : ?>
									<option value="<?= $jt['jobTypeId'] ?>"><?= $jt['jobTypeName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-6"></div>
					<div class="col-lg-6 mt-10">
						<select class="form-control" id="stepAnalysis" onchange="javascript:filterAnalysisJob()">
							<?php
							if (isset($stepId) && $stepId != '') { ?>
								<option value="<?= $stepId ?>"><?= Step::stepName($stepId) ?></option>
							<?php
							}
							?>
							<option value="">All steps</option>
							<?php
							if (count($steps) > 0) {
								foreach ($steps as $s) : ?>
									<option value="<?= $s['stepId'] ?>"><?= $s['sort'] . '. ' . $s['stepName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>

					<div class="col-12 mt-40 ">
						<?= $this->render('chart', [
							"xData" => $xData,
							"values" => $values,
							"chartName" => $chartName
						]) ?>
					</div>
					<div class="col-12 mt-20" style="overflow-x: auto;">
						<?= $this->render('detail', [
							"dataDay" => $dataDay,
							"color" => $color,
							"xData" => $xData,
							"over" => $over,
							"dataOnprocess" => $dataOnprocess,
							"jobTypeId" => $jobTypeId,
							"branchId" => $selectBranch["branchId"],
							"date" => $date,
							"teamId" => $teamId,
							"personId" => $personId,
							"stepId" => $stepId
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>