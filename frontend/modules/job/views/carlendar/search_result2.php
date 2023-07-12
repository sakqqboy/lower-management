<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;

//throw new Exception($stepCheck . '=>' . $finalCheck);
$this->title = 'Carlendar'
?>
<div class="body-content pt-20 mb-40">
	<div class="col-12 pt-20 filter-row mb-20" id="filter-carlendar">
		<?= $this->render('filter_search', [
			"branch" => $branch,
			"category" => $category,
			"fields" => $fields,
			"branchId" => $branchId,
			"personId" => $personId,
			"categoryId" => $categoryId,
			"fieldId" => $fieldId,
			"postTeamId" => $postTeamId,
			"team" => $team,
			"postStatus" => $postStatus,
			"persons" => $persons
		]) ?>
	</div>
	<div class="col-12">
		<div class="col-lg-4 col-md-4 col-6 mb-20 row">
			<select class="form-control" id="show-carlendar">
				<option value="2">Schedule</option>
				<option value="1">List</option>
			</select>
		</div>
		<div class="col-12">
			<div class="row">
				<div class="col-12 mb-20">
					<div class="row">
						<div class="col-lg-9 col-md-7 col-12 mt-10">
							<div class="row">
								<div class="expland-color over-due"></div><span class="font-size16 mr-20">Over</span>
								<div class="expland-color complete-due"></div><span class="font-size16 mr-20">Complete</span>
								<div class="expland-color step-due"></div><span class="font-size16 mr-20">Step due date</span>
								<div class="expland-color final-due"></div><span class="font-size16 mr-20">Final date</span>
								<i class="fa fa-circle step-due-circle  mr-20" aria-hidden="true"></i>&nbsp;&nbsp;Step Due&nbsp;&nbsp;&nbsp;&nbsp;
								<i class="fa fa-circle final-due-circle  mr-20" aria-hidden="true"></i>&nbsp;&nbsp;Final Due
							</div>
						</div>
						<div class="col-lg-3 col-md-5 co-12 mt-10">
							<div class="row">
								<div class="col-lg-6 col-12">
									<input type="checkbox" class="checkbox-sm" id="step-due-check" onchange="javascript:checkcheck()" <?= $stepCheck == 1 ? 'value="1" checked' : 'value="0"' ?>>
									Step due Date
								</div>
								<div class="col-lg-6 col-12">
									<input type="checkbox" class="checkbox-sm" id="final-due-check" onchange="javascript:checkcheck()" <?= $finalCheck == 1 ? 'value="1" checked' : 'value="0"' ?>>
									Final due Date
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-12 mb-10 next-year">
					<div class="row">
						<div class="col-2 text-right">
							<img src="<?= Yii::$app->homeUrl ?>images/icon/previous.png" class="carlendar-button" id="previous-year">
						</div>
						<div class="col-8 text-center current-date" id="current-year"><?= $postYear ?></div>
						<div class="col-2 text-left">
							<img src="<?= Yii::$app->homeUrl ?>images/icon/next.png" class="carlendar-button" id="next-year">
						</div>
					</div>

				</div>
				<div class="col-lg-6 col-md-6 col-12 mb-10">
					<div class="row">
						<div class="col-2 text-right">
							<img src="<?= Yii::$app->homeUrl ?>images/icon/previous.png" class="carlendar-button" id="previous-month">
						</div>
						<div class="col-8 text-center current-date" id="current-date"><?= $selectDate ?></div>
						<div class="col-2 text-left">
							<img src="<?= Yii::$app->homeUrl ?>images/icon/next.png" class="carlendar-button" id="next-month">
						</div>
					</div>

				</div>
				<div class="col-12">
					<hr>
				</div>
			</div>

			<input type="hidden" value="<?= (int)$postYear ?>" id="year">
			<input type="hidden" value="<?= (int)$postMonth ?>" id="month">

			<div class="row text-center mb-10">
				<div class="title-day">Monday</div>
				<div class="title-day">Tuesday</div>
				<div class="title-day">Wednesday</div>
				<div class="title-day">Thursday</div>
				<div class="title-day">Friday</div>
				<div class="title-day">Saturday</div>
				<div class="title-day">Sunday</div>
			</div>
			<div class="row">
				<div class="col-12">
					<?php
					if (isset($dateValue) && count($dateValue) > 0) {
						$totalCount = 0;
						$day = 1;
						$other = '';
						foreach ($dateValue as $index => $value) :
							//throw new Exception(print_r($value, true));
							$dateArr = explode('-', $value["date"]);
							$day = (int)$dateArr[2];
							$month = $dateArr[1];
							$year = $dateArr[0];
							if ((int)$month != (int)$selectMonth) {
								$other = "other-month";
							} else {
								$other = '';
							}
							if (($totalCount % 7) == 0) { ?>
								<div class="row">
								<?php
							}
							if ($value["date"] == date('Y-m-d 00:00:00')) {
								$box = 'sub-box-today';
							} else {
								$box = 'sub-box';
							}
								?>
								<div class="big-box-day" onclick="javascript:showJobDate('<?= $value['date'] ?>')">
									<div class="<?= $box ?> <?= $other ?>">
										<div class="date-number text-right"><?= $day ?></div>
										<?php
										if (isset($filter)) {
											$jobs = Job::getDateJobsFilter($value["date"], $filter, $stepCheck, $finalCheck);
										}
										if (count($jobs) > 0) {
											$c = 0;
											if (isset($jobs["due"]) && count($jobs["due"]) > 0) {
												foreach ($jobs["due"] as $jobStepId => $detail) :
													if ($c < 2) {
														$class = JobStep::createClass($detail["status"], $value["date"]);
										?>
														<div class="<?= $class['class'] ?>">
															<?php
															if ($class["over"] == 1) {
															?>
																<i class="fa fa-circle step-due-circle" aria-hidden="true"></i>
															<?php
															}
															?>
															<?= $detail["clientName"] ?> / <?= $detail["jobName"] ?>
														</div>
													<?php
													}
													$c++;
												endforeach;
												if (count($jobs["due"]) > 3) {
													?>
													<div class="col-12 mt-10 font-size14 text-center">
														<b><?= count($jobs["due"]) ?>&nbsp;&nbsp;Due Date</b>
													</div>

													<?php
												}
											}
											if (isset($jobs["target"]) && count($jobs["target"]) > 0) {
												foreach ($jobs["target"] as $jobCateId => $detail) :
													if ($c < 2) {
														$class = JobCategory::createClass($detail["status"], $value["date"]);
													?>
														<div class="<?= $class['class'] ?>">
															<?php
															if ($class["over"] == 1) {
															?>
																<i class="fa fa-circle final-due-circle" aria-hidden="true"></i>
															<?php
															}
															?>
															<?= $detail["clientName"] ?> / <?= $detail["jobName"] ?>
														</div>
													<?php
													}
													$c++;
												endforeach;
												if (count($jobs["target"]) > 3) {
													?>
													<div class="col-12 mt-10 font-size14 text-center">
														<b><?= count($jobs["target"]) ?>&nbsp;&nbsp;Target Date</b>
													</div>
										<?php

												}
											}
										}
										?>

									</div>
								</div>

								<?php
								$totalCount++;
								if (($totalCount % 7) == 0) { ?>
								</div>
					<?php
								}
							endforeach;
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	echo $this->render('job_date');
	?>
</div>