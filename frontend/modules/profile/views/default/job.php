<?php

use common\models\ModelMaster;
?>
<div class="col-12 font-size24">In hand <b><?= $inHand ?></b> jobs.</div>
<div class="col-12 mt-20">
	<div class="row">
		<div class="col-lg-3  job-box">

			<div class="col-12 text-center need-box">Need to update <span class="job-number"><?= count($needs) ?></span></div>
			<?php
			if (isset($needs) && count($needs) > 0) {
			?>
				<?php
				foreach ($needs as $jobId => $need) : ?>
					<a href="<?= Yii::$app->homeUrl ?>job/detail/job-detail/<?= ModelMaster::encodeParams(["jobId" => $jobId]) ?>" class="no-underline-black ">
						<div class="col-12 each-job-box-need">
							<div class="col-12">
								<?= $need["jobName"] ?>
							</div>
							<div class="col-12 mt-1" style="color:gray;">
								<?= $need["clientName"] ?>
							</div>
							<div class="col-12 text-right" style="color:#2F4F4F;">
								<?= $need["dueDate"] ?>
							</div>
						</div>
					</a>
				<?php
				endforeach;
				?>



			<?php
			}
			?>
		</div>
		<div class="col-lg-3  job-box">
			<div class="col-12 text-center nearly-box">Nearly to update <span class="job-number"><?= count($nearlies) ?></span></div>
			<?php
			if (isset($nearlies) && count($nearlies) > 0) {
			?>

				<?php
				foreach ($nearlies as $jobId => $nearly) : ?>
					<a href="<?= Yii::$app->homeUrl ?>job/detail/job-detail/<?= ModelMaster::encodeParams(["jobId" => $jobId]) ?>" class="no-underline-black ">
						<div class="col-12 each-job-box-nearly">
							<div class="col-12">
								<?= $nearly["jobName"] ?>
							</div>
							<div class="col-12 mt-1" style="color:gray;">
								<?= $nearly["clientName"] ?>
							</div>
							<div class="col-12 text-right" style="color:#2F4F4F;">
								<?= $nearly["dueDate"] ?>
							</div>
						</div>
					</a>
				<?php
				endforeach;
				?>

			<?php
			}
			?>
		</div>
		<div class="col-lg-3  job-box">
			<div class="col-12 text-center process-box">In process <span class="job-number"><?= count($inprocess) ?></span></div>
			<?php
			if (isset($inprocess) && count($inprocess) > 0) {
			?>

				<?php
				foreach ($inprocess as $jobId => $inpro) : ?>
					<a href="<?= Yii::$app->homeUrl ?>job/detail/job-detail/<?= ModelMaster::encodeParams(["jobId" => $jobId]) ?>" class="no-underline-black ">
						<div class="col-12 each-job-box-process">
							<div class="col-12">
								<?= $inpro["jobName"] ?>
							</div>
							<div class="col-12 mt-1" style="color:gray;">
								<?= $inpro["clientName"] ?>
							</div>
							<div class="col-12 text-right" style="color:#2F4F4F;">
								<?= $inpro["dueDate"] ?>
							</div>
						</div>
					</a>
				<?php
				endforeach;
				?>

			<?php
			}
			?>
		</div>
		<div class="col-lg-3  job-box">
			<div class="col-12 text-center complete-box">Completed <span class="job-number"><?= count($completes) ?></span></div>
			<?php
			if (isset($completes) && count($completes) > 0) {
			?>
				<?php
				foreach ($completes as $jobId => $complete) : ?>
					<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $jobId]) ?>" class="no-underline-black ">
						<div class="col-12 each-job-box-complete">
							<div class="col-12">
								<?= $complete["jobName"] ?>
							</div>
							<div class="col-12 mt-1" style="color:gray;">
								<?= $complete["clientName"] ?>
							</div>

						</div>
					</a>
				<?php
				endforeach;
				?>

			<?php
			}
			?>
		</div>
	</div>
</div>