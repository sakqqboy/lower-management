<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;
?>
<div class="col-12 mb-40">
	<div class="row">
		<div class="col-12 mb-20">
			<div class="row">
				<div class="col-lg-10 col-md-9 col-8 text-right">
					<div class="row">
						<div class="expland-color over-due"></div><span class="font-size16 mr-20">Over</span>
						<div class="expland-color complete-due"></div><span class="font-size16 mr-20">Complete</span>
						<div class="expland-color step-due"></div><span class="font-size16 mr-20">Step due date</span>
						<div class="expland-color final-due"></div><span class="font-size16 mr-20">Final date</span>
						<i class="fa fa-circle step-due-circle  mr-20" aria-hidden="true"></i>&nbsp;&nbsp;Step Due&nbsp;&nbsp;&nbsp;&nbsp;
						<i class="fa fa-circle final-due-circle  mr-20" aria-hidden="true"></i>&nbsp;&nbsp;Final Due
					</div>
				</div>
				<div class="col-lg-2 col-md-3 col-4 text-right">
					<select class="form-control" id="filter-status" onchange="javascript:filterJobCarlendar()">
						<option value="">Status</option>
						<?php
						foreach ($jobStatus as $status => $text) : ?>
							<option value="<?= $status ?>"><?= $text ?></option>
						<?php
						endforeach;
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-12 mb-10 next-year">
			<div class="row">
				<div class="col-2 text-right">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/previous.png" class="carlendar-button" id="previous-year">
				</div>
				<div class="col-8 text-center current-date" id="current-year"><?= date('Y') ?></div>
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
	<input type="hidden" value="<?= (int)date('Y') ?>" id="year">
	<input type="hidden" value="<?= (int)date('m') ?>" id="month">

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
		<div class="col-12" id="result-date">
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
						<div class="big-box-day">
							<div class="<?= $box ?> <?= $other ?>">
								<div class="date-number text-right"><?= $day ?></div>
								<?php
								$jobs = Job::getDateJobs($value["date"]);
								if (count($jobs) > 0) {

									if (isset($jobs["due"]) && count($jobs["due"]) > 0) {
										foreach ($jobs["due"] as $jobStepId => $detail) :
											$class = JobStep::createClass($detail["status"], $value["date"]);
								?>
											<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $detail["jobId"]]) ?>" class="no-underline ">
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
											</a>
										<?php
										endforeach;
									}
									if (isset($jobs["target"]) && count($jobs["target"]) > 0) {
										foreach ($jobs["target"] as $jobCateId => $detail) :
											$class = JobCategory::createClass($detail["status"], $value["date"]);
										?>
											<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $detail["jobId"]]) ?>" class="no-underline ">
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
											</a>
								<?php
										endforeach;
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