<?php

use common\models\ModelMaster;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;

$this->title = $date;
?>
<div class="body-content pt-20 mb-40">
	<div class="col-12 font-size24 mb-20 show-date-job">
		<b><?= $date ?></b>
	</div>
	<?php
	if (count($jobs) > 0) {
	?>
		<div class="col-12">
			<div class="row">
				<?php
				if (isset($jobs["due"]) && count($jobs["due"]) > 0) { ?>
					<div class="col-lg-9">
						<div class="row">
							<div class="col-12 mb-20 font-size24">
								<b>Step due</b>
							</div>
							<?php
							foreach ($jobs["due"] as $jobStepId => $detail) :
								$class = JobStep::createClass($detail["status"], $date);
							?>
								<div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-10">
									<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $detail["jobId"]]) ?>" class="no-underline">
										<div class="col-12 each-date <?= $class['class'] ?>">
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
										</div>
									</a>
								</div>
							<?php
							endforeach; ?>
						</div>
					</div>
				<?php
				}
				if (isset($jobs["target"]) && count($jobs["target"]) > 0) {
				?>
					<div class="col-lg-3">
						<div class="row">
							<div class="col-12 mb-20 font-size24">
								<b>Target date</b>
							</div>
							<?php
							foreach ($jobs["target"] as $jobCateId => $detail) :
								$class = JobCategory::createClass($detail["status"], $date);
							?>
								<div class="col-lg-12 col-md-12 col-sm-6 col-6 mb-10">
									<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $detail["jobId"]]) ?>" class="no-underline">
										<div class="col-12 each-date <?= $class['class'] ?>">
											<?php
											if ($class["over"] == 1) {
											?>
												<i class="fa fa-circle final-due-circle" aria-hidden="true"></i>
											<?php
											}
											?>
											<?= $detail["clientName"] ?> <br> <?= $detail["jobName"] ?>


										</div>
									</a>
								</div>
							<?php

							endforeach; ?>
						</div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	<?php
	}
	?>
</div>