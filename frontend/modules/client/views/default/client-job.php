<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Job;
?>

<div class="row">
	<div class="col-12 font-size18 amount-client-job"><b><?= $client["clientName"] ?></b></div>
	<input type="hidden" id="clientId" value="<?= $client["clientId"] ?>">
	<?php
	if (isset($firstJob) && !empty($firstJob)) {
	?>
		<div class="col-6 font-size16 mt-20">
			<b>Branch : :&nbsp;&nbsp;&nbsp; <?= Branch::branchName($firstJob["branchId"]) ?></b>
		</div>
		<div class="col-6 font-size16 mt-20">
			<div class="row">
				<div class="offset-6 col-6">
					<select class="form-control" id="client-select-year" onchange="javascript:selectYearJob()">
						<option value="">Select year</option>
						<option value="2020">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
						<option value="2026">2026</option>
						<option value="2027">2027</option>
						<option value="2028">2028</option>
						<option value="2029">2029</option>
						<option value="2030">2030</option>
						<option value="2031">2031</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-6 text-left mt-20">
			<div class="badge badge-primary pt-10 pb-10 font-size16"><b>On the procress job</b></div>
		</div>
		<div class="col-6 font-size16 text-right mt-20"><b>Yearly total fee :&nbsp;&nbsp;&nbsp;
				<span class="amount-client-job" id="client-amount"><?= number_format(Job::clientFee($firstJob["clientId"]), 2) ?></span> THB</b></div>
		<div class="col-12 mt-10" id="job-onprocess">
			<table class="table">
				<tr class="table-head text-left">
					<td>#</td>
					<td>Job name</td>
					<td>Field</td>
					<td>Category</td>
					<td>Team</td>
					<td>Unit Fee</td>
					<td>Total Fee</td>
				</tr>
				<tbody>
					<?php



					if (isset($jobs) && count($jobs) > 0) {
						$i = 1;
						foreach ($jobs as $job) :
							if ($job["status"] == Job::STATUS_INPROCESS) {
					?>
								<tr>
									<td><?= $i ?></td>
									<td>
										<a href="<?= Yii::$app->homeUrl ?>job/detail/job-detail/<?= ModelMaster::encodeParams(["jobId" => $job["jobId"]]) ?>" class="no-underline job-name-link">
											<?= $job["jobName"] ?>
										</a>
									</td>
									<td><?= $job["fieldName"] ?></td>
									<td><?= $job["categoryName"] ?></td>
									<td><?= $job["teamName"] ?></td>
									<td class="text-right"><?= number_format($job["fee"], 2) ?></td>
									<td class="text-right"><?= number_format(Category::muliplyfee($job["categoryId"]) * $job["fee"], 2) ?></td>
								</tr>
					<?php
								$i++;
							}
						endforeach;
					}
					?>
				</tbody>
			</table>
			<hr>
		</div>
		<div class="col-6 text-left mt-20">
			<div class="badge badge-secondary pt-10 pb-10 font-size16"><b>Complete job</b></div>
		</div>
		<div class="col-6 font-size16 mt-20">
			<div class="row">
				<div class="offset-6 col-6">
					<select class="form-control" id="client-select-year-complete" onchange="javascript:selectYearJobComplete()">
						<option value="">Select year</option>
						<option value="2020">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
						<option value="2026">2026</option>
						<option value="2027">2027</option>
						<option value="2028">2028</option>
						<option value="2029">2029</option>
						<option value="2030">2030</option>
						<option value="2031">2031</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-12 font-size16 text-right mt-20"><b>Yearly total fee :&nbsp;&nbsp;&nbsp;
				<span class="amount-client-job" id="client-amount-complete"><?= number_format(Job::clientFeeComplete($firstJob["clientId"]), 2) ?></span> THB</b>
		</div>
		<div class="col-12 mt-10" id="job-complete">
			<table class="table">
				<tr class="table-head  text-left">
					<td>#</td>
					<td>Job name</td>
					<td>Field</td>
					<td>Category</td>
					<td>Team</td>
					<td>Unit Fee</td>
					<!-- <td>Total Fee</td> -->
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
										<!-- <td class="text-right"><?php //number_format(Category::muliplyfee($job["categoryId"]) * $job["fee"], 2) 
														?></td> -->
									</tr>

						<?php
									$i++;
								}
							}
						endforeach;
					}
					if ($i == 1) { ?>
						<tr>
							<td colspan="6" class="text-center">No data</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		<?php
	} else { ?>
			<div class="col-12 text-left mt-20">
				No data
			</div>
		<?php

	}
		?>
		</div>
</div>