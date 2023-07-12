<?php

use common\models\ModelMaster;

$this->title = 'Yearly Job Analysis';
?>
<div class="body-content pt-20 mb-50">
	<div class="col-12">
		<div class="row">
			<div class="col-lg-3">
				<div class="col-12 p-0">
					<select id="analysis-type" class="form-control text-left font-size16 font-weight-bold">
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/yearly">
							<b>Yearly Job Analysis </b>
						</option>
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/index">
							<b>Monthly Job Analysis </b>
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
								foreach ($date as $index => $dateData) :
								?>
									<th class="font-size12"><?= $dateData ?>
										<div class="col-12 p-0 mt-1">
											<select class="filter-select" id="filterYearlyYear<?= $i ?>" onchange="javascript:filterYearlyAnalysisJob()">
												<option value="<?= $dateData ?>"><?= $dateData ?></option>
												<?php
												foreach ($jobCategoryFiscalYear as $year) : ?>
													<option value="<?= $year ?>"><?= $year ?></option>
												<?php
												endforeach
												?>

											</select>
										</div>
										<div class="col-12 p-0 mt-1">
											<select class="filter-select" id="filterYearlyMonth<?= $i ?>" onchange="javascript:filterYearlyAnalysisJob()">
												<option value="<?= $defaultMonth[$i]["value"] ?>"><?= $defaultMonth[$i]["text"] ?></option>
												<option value="">All</option>
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
							//throw new exception(print_r($data, true));
							if (count($data) > 0) {
								foreach ($data as $month => $dataYear) :
									if ($month == 0) {
										$text = '1 month';
									}
									if ($month == 1) {
										$text = '2 months';
									}
									if ($month == 2) {
										$text = '3 months';
									}
									if ($month == 3) {
										$text = '4 months';
									}
									if ($month == 4) {
										$text = '5 months';
									}
									if ($month == 5) {
										$text = '6 months';
									}
									if ($month == 6) {
										$text = 'Over';
									}
							?>
									<tr>
										<td class="text-left"><?= $text ?></td>
										<?php
										foreach ($dataYear as $year => $number) : ?>
											<td>
												<?php
												if ($number > 0) {
												?>
													<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail-yearly/<?= ModelMaster::encodeParams([
																							"year" => $date[$year],
																							"month" => $month,
																							"jobTypeId" => $jobTypeId,
																							"defaultMonth" => $defaultMonth["value"],
																							"branchId" => $employeeBranch["branchId"],
																							"textMonth" => $text,
																							"teamId" => '',
																							"personId" => '',
																							"stepId" => ''

																						]) ?>" class="no-underline-black">
														<?= $number ?>
													</a>
												<?php
												} else {
													echo $number;
												}
												?>
												<div class="font-size12">
													(<?= $total > 0 ? number_format(($number / $total) * 100, 2) : 0 ?>%)
												</div>
											</td>

										<?php
										endforeach; ?>
									</tr>
							<?php
								endforeach;
							}
							?>
							<tr>
								<td>Total</td>
								<?php
								if (count($totalYear) > 0) {
									foreach ($dataYear as $year => $number) : ?>
										<td>
											<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail-yearly/<?= ModelMaster::encodeParams([
																					"year" => $date[$year],
																					"month" => 99,
																					"jobTypeId" => $jobTypeId,
																					"defaultMonth" => $defaultMonth["value"],
																					"branchId" => $employeeBranch["branchId"],
																					"textMonth" => $text,
																					"teamId" => '',
																					"personId" => '',
																					"stepId" => ''

																				]) ?>" class="no-underline-black">

												<?= isset($totalYear[$year]) ? $totalYear[$year] : 0 ?>
											</a>
											<div class="font-size12">
												(<?= $total > 0 ? number_format(((isset($totalYear[$year]) ? $totalYear[$year] : 0) / $total) * 100, 2) : 0 ?>%)
											</div>
										</td>

									<?php
									endforeach;
								} else { ?>
									<td>0
										<div class="font-size12">
											(0.00%)
										</div>
									</td>
								<?php
								}
								?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-lg-9 ">
				<div class="row">
					<div class="col-lg-3">
						<select class="form-control" id="branchYearlyAnalysis" onchange="javascript:filterYearlyAnalysisJob()">
							<option value="<?= $employeeBranch["branchId"] ?>"><?= $employeeBranch["branchName"] ?></option>
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
						<select class="form-control" id="teamYearlyAnalysis" onchange="javascript:filterYearlyAnalysisJob()">
							<option value="">Team</option>
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
						<select class="form-control" id="personYearlyAnalysis" onchange="javascript:filterYearlyAnalysisJob()">
							<option value="">Person</option>

						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" id="jobTypeYearlyAnalysis" onchange="javascript:filterYearlyAnalysisJob()">
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
						<select class="form-control" id="stepYearlyAnalysis" onchange="javascript:filterYearlyAnalysisJob()">
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
						<?= $this->render('yearly_chart', [
							"xData" => $xData,
							"values" => $values,
							"chartName" => $chartName
						]) ?>
					</div>
					<div class="col-12 mt-20" style="overflow-x: auto;">
						<?= $this->render('yearly_detail', [
							"datePeriod" => $datePeriod,
							"color" => $color,
							"xData" => $xData,
							"dataOnprocess" => $dataOnprocess,
							"date" => $date,
							"jobTypeId" => $jobTypeId,
							"branchId" => $employeeBranch["branchId"],
							"selectMonth" => $defaultMonth,
							"teamId" => '',
							"personId" => '',
							"stepId" => ''
							//"over" => $over
						]) ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>