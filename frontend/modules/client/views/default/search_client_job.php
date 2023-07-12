<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Currency;
use frontend\models\lower_management\Job;
use yii\bootstrap4\ActiveForm;

$this->title = 'Client';
?>
<div class="body-content pt-20">
	<div class="row">
		<div class="col-3 client-list" id="client-list">
			<div class="col-12 font-size16 text-center">
				<b>Client name</b>
			</div>
			<div class="col-12 mt-10 mb-10">
				<div class="row">
					<div class="col-6">
						<select class="form-control font-size12" id="branch-client" onchange="javascript:searchFilterJob()">


							<?php
							if (isset($branchId) && $branchId != '') { ?>
								<option value="<?= $branchId ?>"><?= Branch::branchName($branchId) ?></option>
							<?php
							} else { ?>
								<option value="">Branch</option>
								<?php
							}
							if (isset($branch) && count($branch) > 0) {
								foreach ($branch as $b) : ?>
									<option value="<?= $b["branchId"] ?>"><?= $b["branchName"] ?></option>
							<?php

								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-6">
						<select class="form-control font-size12" id="sort-client" onchange="javascript:searchFilterJob()">
							<?php
							if ($sort == 1) { ?>
								<option value="1">A - Z</option>
								<option value="2">Z - A</option>
							<?php
							}
							if ($sort == 2) { ?>
								<option value="2">Z - A</option>
								<option value="1">A - Z</option>
							<?php
							}
							?>


						</select>
					</div>

				</div>
			</div>
			<div class="client-list-zone pl-1" id="client-search">
				<input type="hidden" id="total-client" value="<?= count($clients) ?>">
				<?php
				if (isset($clients) && count($clients) > 0) {
					$i = 1;
					foreach ($clients as $cli) :
						if ($cli['clientId'] == $firstClientId) { ?>
							<div class="client-list-item col-12" onclick="javascript:clientJob(<?= $cli['clientId'] ?>)" id="list-client-<?= $i ?>" style="background-color: rgb(235, 235, 235)">
								<?= $cli["clientName"] ?>
							</div>
							<?php
						} else {
							if ($firstClientId == '') {
							?>
								<div class="client-list-item col-12" onclick="javascript:clientJob(<?= $cli['clientId'] ?>)" id="list-client-<?= $i ?>" style="background-color:<?= $i == 1 ? 'rgb(235, 235, 235)' : '' ?>">
									<?= $cli["clientName"] ?>
								</div>
							<?php
							} else { ?>
								<div class="client-list-item col-12" onclick="javascript:clientJob(<?= $cli['clientId'] ?>)" id="list-client-<?= $i ?>">
									<?= $cli["clientName"] ?>
								</div>
				<?php
							}
						}
						$i++;
					endforeach;
				} ?>

			</div>
		</div>
		<div class="col-9" id="client-job">
			<?php
			if (isset($firstClient) && !empty($firstClient)) {
			?>
				<div class="row">
					<div class="col-12 font-size18 amount-client-job">
						<b><?= $firstClient["clientName"] ?></b>
						<a href="javascript:showRemark(<?= $firstClient['clientId'] ?>)" class="button-red button-md no-underline" style="font-size:14px;padding:4px;">+ Remark</a>
						<input type="hidden" id="clientId" value="<?= $firstClient["clientId"] ?>">
					</div>
					<div class="col-12 font-size14">
						<i class="fa fa-tag mr-10" aria-hidden="true" style="transform: rotate(135deg);color:red;"></i>
						<span id="show-remark-client"><?= $firstClient["remark"] ?></span>
					</div>
					<div class="col-6 font-size16 mt-20">
						<b>Branch : :&nbsp;&nbsp;&nbsp; <?= Branch::branchName($firstClient["branchId"]) ?></b>
					</div>
					<div class="col-6 font-size16 mt-20">
						<div class="row">
							<div class="offset-6 col-6">
								<select class="form-control" id="client-select-year" onchange="javascript:selectYearJob()">
									<?php
									if (isset($yearOnProcess) && $yearOnProcess != "") { ?>
										<option value="<?= $yearOnProcess ?>"><?= $yearOnProcess ?></option>
									<?php
									} else { ?>
										<option value="">Select year</option>
									<?php
									}
									?>
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
						<div class="badge badge-primary pt-10 pb-10 font-size16"><b>All jobs</b></div>
					</div>
					<div class="col-6 font-size16 text-right mt-20">
						<b>Yearly total fee :&nbsp;&nbsp;&nbsp;
							<span class="amount-client-job" id="client-amount">
								<?= number_format(Job::clientFee($firstClient["clientId"]), 2) ?>
							</span>
							<?= isset($firstClient["currencyId"]) && $firstClient["currencyId"] != '' ? Currency::currencyCode($firstClient["currencyId"]) : '-' ?>
						</b>
					</div>
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
								if (isset($jobProcess) && count($jobProcess) > 0) {
									$i = 1;
									foreach ($jobProcess as $job) :

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
								<select class="form-control" id="client-select-year-complete" onchange="javascript:selectYearJob()">
									<?php
									if (isset($yearComplete) && $yearComplete != "") { ?>
										<option value="<?= $yearComplete ?>"><?= $yearComplete ?></option>
									<?php
									} else { ?>
										<option value="">Select year</option>
									<?php
									}
									?>
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
							<span class="amount-client-job" id="client-amount-complete">
								<?= number_format(Job::clientFeeComplete($firstClient["clientId"]), 2) ?>
							</span><?= isset($firstClient["currencyId"]) && $firstClient["currencyId"] != '' ? Currency::currencyCode($firstClient["currencyId"]) : '-' ?></b>
					</div>
					<div class="col-12 mt-10" id="job-complete">
						<table class="table">
							<tr class="table-head text-center">
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
								$i = 1;
								if (isset($jobComplete) && count($jobComplete) > 0) {

									foreach ($jobComplete as $job) :

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

					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div><?= $this->render('modal_remark') ?>