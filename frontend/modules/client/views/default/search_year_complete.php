<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Job;
?>
<table class="table">
	<tr class="table-head text-left">
		<td>#</td>
		<td>Job name</td>
		<td>Field</td>
		<td>Category</td>
		<td>Team</td>
		<td>Unit Fee</td>
		<!-- <td>Fee</td> -->
	</tr>
	<tbody>
		<?php
		if (isset($jobs) && count($jobs) > 0) {
			$i = 1;
			foreach ($jobs as $job) :
				if ($job["status"] == Job::STATUS_COMPLETE) {
		?>
					<tr>
						<td><?= $i ?></td>
						<td>
							<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $job["jobId"]]) ?>" class="no-underline job-name-link">
								<?= $job["jobName"] ?>
							</a>
						</td>
						<td><?= $job["fieldName"] ?></td>
						<td><?= $job["categoryName"] ?></td>
						<td><?= $job["teamName"] ?></td>
						<td class="text-right"><?= number_format($job["fee"], 2) ?></td>
						<!-- <td class="text-right"><?php // number_format(Category::muliplyfee($job["categoryId"]) * $job["fee"], 2) 
										?></td> -->
					</tr>
					<?php
					$i++;
				}
				if ($job["status"] == Job::STATUS_INPROCESS) {
					$isHaveComplete = Job::IsHaveComplete($job["jobId"]);
					if ($isHaveComplete == 1) { ?>
						<tr>
							<td><?= $i ?></td>
							<td>
								<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $job["jobId"]]) ?>" class="no-underline job-name-link">
									<?= $job["jobName"] ?>
								</a>
							</td>
							<td><?= $job["fieldName"] ?></td>
							<td><?= $job["categoryName"] ?></td>
							<td><?= $job["teamName"] ?></td>
							<td class="text-right"><?= number_format($job["fee"], 2) ?></td>
							<!-- <td class="text-right"><?php // number_format(Category::muliplyfee($job["categoryId"]) * $job["fee"], 2) 
											?></td> -->
						</tr>
			<?php
						$i++;
					}
				}
			endforeach;
		} else { ?>
			<tr>
				<td colspan="6" class="text-center">No data</td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>