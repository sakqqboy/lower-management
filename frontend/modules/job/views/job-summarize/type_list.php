<?php

use common\models\ModelMaster;

$this->title = $jobTypeName;
?>
<div class="body-content pt-30 mb-50">
	<div class="row">
		<div class="offset-1 col-11 text-left font-size20 font-weight-bold"><?= $jobTypeName ?> ( <?= $status ?> ) (<?= count($jobs) ?> jobs.)</div>
		<div class="col-12 mt-20 ">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>No.</th>
						<th>Job name</th>
						<th>Client.</th>
						<th>Current step</th>
						<th>Target Date</th>
						<th>Previous complete</th>
						<th>This Complete</th>
						<th class="text-center" style="width:7%;">Action</th>
					</tr>
				</thead>
				<tbody class="font-size14">
					<?php
					if (isset($jobs) && count($jobs) > 0) {
						$i = 1;
						foreach ($jobs as $jobId => $job) : ?>
							<tr>
								<td><?= $i ?></td>
								<td><?= $job["jobName"] ?></td>
								<td><?= $job["client"] ?></td>
								<td><?= $job["currentStep"] ?></td>
								<td><?= $job["targetDate"] ?></td>
								<td><?= $job["previous"] ?></td>
								<td><?= $job["current"] ?></td>
								<td>
									<a href="<?= Yii::$app->homeUrl ?>job/detail/complete-job/<?= ModelMaster::encodeParams(["jobId" => $jobId]) ?>" class="btn button-sky button-xs mr-10">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</a>
									<a href="<?= Yii::$app->homeUrl ?>job/detail/job-detail/<?= ModelMaster::encodeParams(["jobId" => $jobId]) ?>" class="btn button-yellow button-xs">
										<i class="fa fa-edit" aria-hidden="true"></i>
									</a>

								</td>
							</tr>
					<?php
							$i++;
						endforeach;
					}

					?>
				</tbody>
			</table>
		</div>
	</div>
</div>