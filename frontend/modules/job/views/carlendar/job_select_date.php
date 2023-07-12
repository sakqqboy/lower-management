<?php

use common\models\ModelMaster;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;

if (count($jobs) > 0) {
?>
	<div class="col-12">
		<div class="row">
			<?php
			if (isset($jobs["due"]) && count($jobs["due"]) > 0) {
				foreach ($jobs["due"] as $jobStepId => $detail) :

					$class = JobStep::createClass($detail["status"], $date);
			?>
					<div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-10">
						<div class="col-12 each-date <?= $class['class'] ?>">
							<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $detail["jobId"]]) ?>" class="no-underline" style="z-index: 10;color:white;">

								<?php
								if ($class["over"] == 1) {
								?>
									<i class="fa fa-circle step-due-circle" aria-hidden="true"></i>
								<?php
								}
								?>
								<?= $detail["clientName"] ?><br>
								<?= $detail["jobName"] ?><br>
								<?= $detail["sort"] ?>. <?= $detail["stepName"] ?>

							</a>
						</div>
					</div>
				<?php
				endforeach;
			}
			if (isset($jobs["target"]) && count($jobs["target"]) > 0) {
				foreach ($jobs["target"] as $jobCateId => $detail) :
					$class = JobCategory::createClass($detail["status"], $date);
				?>
					<div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-10">
						<div class="col-12 each-date <?= $class['class'] ?>">
							<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $detail["jobId"]]) ?>" class="no-underline" style="z-index: 10;color:white;">

								<?php
								if ($class["over"] == 1) {
								?>
									<i class="fa fa-circle final-due-circle" aria-hidden="true"></i>
								<?php
								}
								?>
								<?= $detail["clientName"] ?> <br> <?= $detail["jobName"] ?>

							</a>
						</div>
					</div>
			<?php

				endforeach;
			}
			?>
		</div>
	</div>
<?php
}
?>