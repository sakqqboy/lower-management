<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;

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
					if (isset($filter)) {
						$jobs = Job::getDateJobsFilter($value["date"], $filter, $stepCheck, $finalCheck);
					}

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