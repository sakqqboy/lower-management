<?php

use common\models\ModelMaster;
use frontend\models\lower_management\AddjustDuedateAdditional;
use frontend\models\lower_management\Currency;
use frontend\models\lower_management\JobResponsibility;
use frontend\models\lower_management\JobStep;
use kartik\widgets\DatePicker;

$this->title = "Complete job";
?>
<div class="body-content pt-20 container mb-50">
	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="col-6 job-name-detail">
					<?= $job["jobName"] ?>
				</div>
				<div class="col-6 text-right">
					<a href="<?= Yii::$app->homeUrl ?>job/clone/create/<?= ModelMaster::encodeParams(['jobId' => $job['jobId']]) ?>" class="btn button-sky pt-2 pb-2 mr-1">
						<i class="fa fa-clone" aria-hidden="true"></i> COPY
					</a>
					<a href="<?= Yii::$app->homeUrl ?>job/detail/job-detail/<?= ModelMaster::encodeParams(["jobId" => $job["jobId"]]) ?>" class="btn button-yellow pt-2 pb-2">
						<i class="fa fa-edit" aria-hidden="true"></i> UPDATE
					</a>
				</div>
				<div class="col-12 client-name-detail mt-20">
					Client<span class="ml-20"><?= $job["clientName"] ?></span>
				</div>
			</div>
		</div>
		<div class="col-12 text-right">
			<b>Start Date : <?= $job["startDate"] != null ? ModelMaster::engDate($job["startDate"] . " 00:00:00", 1) : 'not set' ?></b>
		</div>
		<div class="col-12 mt-10 job-info">
			<div class="row">
				<div class="col-12 text-left">
					<b><i class="fa fa-info mr-10" aria-hidden="true"></i><u>Job Information</u></b>
					<?php
					if ($job['url'] != '') { ?>
						&nbsp;&nbsp;|&nbsp;> > > <a href=" <?= $job['url'] ?>" target="_blank">Go to Teachme Biz </a>
						< < < |&nbsp; <?php
							}
								?> </div>
							<div class="col-lg-6 col-md-6 col-12 mt-10 border-right ">
								<div class="col-12">
									<div class="row">
										<div class="col-5 job-info-title">Branch</div>
										<div class="col-7 job-info-detail"><?= $job["branchName"] ?></div>
										<div class="col-5 job-info-title">Field</div>
										<div class="col-7 job-info-detail"><?= $job["fieldName"] ?></div>
										<div class="col-5 job-info-title">Job type</div>
										<div class="col-7 job-info-detail"><?= $job["jobTypeName"] ?></div>
										<div class="col-5 job-info-title">Team</div>
										<div class="col-7 job-info-detail"><?= $job["teamName"] ?></div>
										<div class="col-5 job-info-title">PIC 1</div>
										<div class="col-7 job-info-detail">
											<div class="row">
												<div class="col-9 border-right">
													<?= JobResponsibility::jobResponseTextDetail($job["jobId"], JobResponsibility::PIC1) ?>
												</div>
												<div class="col-3">
													<?= $job["p1Time"] != null ? $job["p1Time"] . ' hrs.' : '-' ?>
												</div>
											</div>
										</div>
										<div class="col-5 job-info-title">PIC 2</div>
										<div class="col-7 job-info-detail">
											<div class="row">
												<div class="col-9 border-right">
													<?= JobResponsibility::jobResponseTextDetail($job["jobId"], JobResponsibility::PIC2) ?>
												</div>
												<div class="col-3">
													<?= $job["p2Time"] != null ? $job["p2Time"] . ' hrs.' : '-' ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-12 mt-10">
								<div class="col-12">
									<div class="row">
										<div class="col-5 job-info-title">Fee</div>
										<div class="col-7 job-info-detail"><?= number_format($job["fee"], 2) ?>&nbsp;&nbsp;&nbsp;<?= Currency::currencyNameFull($job["currencyId"]) ?></div>
										<div class="col-5 job-info-title">charge Date</div>
										<div class="col-7 job-info-detail"><?= $job["feeChargeDate"] != null ? ModelMaster::engDate($job["feeChargeDate"], 2) : '-' ?></div>
										<div class="col-5 job-info-title">Advance Receivable</div>
										<div class="col-7 job-info-detail"><?= number_format($job["advanceReceivable"], 2) ?></div>
										<div class="col-5 job-info-title">charge Date</div>
										<div class="col-7 job-info-detail"><?= $job["advancedChargeDate"] != null ? ModelMaster::engDate($job["advancedChargeDate"], 2) : '-' ?></div>
										<div class="col-5 job-info-title">Outsourcing fee</div>
										<div class="col-7 job-info-detail"><?= number_format($job["outsourcingFee"], 2) ?></div>
										<div class="col-5 job-info-title">Estimate time</div>
										<div class="col-7 job-info-detail"><?= number_format($job["outsourcingFee"]) ?> hrs.</div>

									</div>
								</div>
							</div>

				</div>
			</div>
			<div class="col-12 mt-20 font-size18">
				<b>Category : : <?= $job["categoryName"] ?></b>
			</div>

			<div class="col-12">
				<?php
				if (isset($alljobs) && count($alljobs) > 0) { ?>
					<div class="row">
						<?php
						$i = 1;
						$m = 0;
						foreach ($alljobs as $jobCateId => $allStep) : ?>
							<div class="col-lg-6  col-12 mt-20 box-complete">
								<div class="row">
									<div class="col-12 text-right">
										<a href="javascript:showModalTarget(<?= $jobCateId ?>)" class="btn button-blue no-underline font-size12"> Change Target Date </a>
										<a href="javascript:showModalTargetMonth(<?= $jobCateId ?>)" class="btn btn-secondary no-underline font-size12"> Change Month </a>
										<a href="javascript:showModalFiscal(<?= $jobCateId ?>)" class="btn button-sky no-underline font-size12"> Change Fiscal Year </a>
										<a href="<?= Yii::$app->homeUrl ?>job/detail/export-issue?jc=<?= $jobCateId ?>" class="btn button-turqouise no-underline font-size12"> Export </a>
										<input type="hidden" id="targetDate<?= $jobCateId ?>" value="<?= $allStep['targetDate'] ?>">
										<input type="hidden" id="fiscalYear<?= $jobCateId ?>" value="<?= $allStep['fiscalYear'] ?>">
										<input type="hidden" id="targetMonth<?= $jobCateId ?>" value="<?= $allStep['startMonthInt'] ?>">
									</div>
								</div>
								<div class="col-12 border">
									<div class="row">

										<div class="col-6 head-target-date">
											<?= $i ?>.&nbsp;Target&nbsp; : <?= $allStep["targetDate"] ?>
										</div>
										<div class="col-1 head-target-date">
											<?= $allStep["startMonth"] ?>
											<input type="hidden" id="targetMonthText<?= $jobCateId ?>" value="<?= $allStep['startMonth'] ?>">
										</div>
										<div class="col-5 text-right head-target-date">
											Complete:<?= $allStep["tCompleteDate"] ?>
										</div>
										<div class="col-3 step-title">Step</div>
										<div class="col-3 step-title">Due Date</div>
										<div class="col-3 step-title">Complete</div>
										<div class="col-2 step-title">Status</div>
										<div class="col-1 step-title"><i class="fa fa-history" aria-hidden="true"></i></div>
										<?php
										$j = 1;
										if (isset($allStep) && count($allStep) > 0 && $jobCateId != "targetDate"  && $jobCateId != "tCompleteDate" && $jobCateId != "fiscalYear") {
											//throw new Exception(print_r($allStep, true));

											foreach ($allStep as $stepId => $step) :
												$class = "";

												if ($stepId != "targetDate" && $stepId != "tCompleteDate" && $stepId != "startMonth" && $stepId != "fiscalYear" && $stepId != "startMonthInt") {
													if ($step["status"] == 'Complete') {
														$class = "text-success";
													}
										?>
													<div class="col-3 detail-due-date"><?= $j . '. ' . $step["stepName"] ?></div>
													<div class="col-3 detail-due-date">F : <?= $step["firstDueDate"] ?><br>L : <?= $step["dueDate"] ?></div>
													<div class="col-3 detail-due-date">
														<span id="completeDate-<?= $stepId ?>-<?= $jobCateId ?>">
															<?= $step["completeDate"] ?>
														</span>
														<?php
														if ($step["completeDate"] != null) { ?>
															<a class="btn button-yellow button-xs" href="javascript:showAdjustDate(<?= $stepId ?>,<?= $jobCateId ?>)">
																<i class="fa fa-pencil" aria-hidden="true"></i>
															</a>
															<div class="col-12 mt-10" id="adjustDate-<?= $stepId ?>-<?= $jobCateId ?>" style="display:none;padding-left:0px;padding-right:0px;">

																<?=
																DatePicker::widget([
																	'name' => 'adjustDate',
																	'type' => DatePicker::TYPE_INPUT,
																	'pluginOptions' => [
																		'autoclose' => true,
																		'format' => 'yyyy-mm-dd',
																	],
																	'options' => [
																		'id' => 'adjustdate-' . $stepId . '-' . $jobCateId,
																		'onchange' => 'javascript:saveAdjust(' . $stepId . ',' . $jobCateId . ')',
																		'style' => [
																			'height' => '30px',
																			'width' => '100%',
																			'font-size' => '14px'
																		]
																	]
																]);
																?>
															</div>
															<?php
															if (isset($step["adjustComplete"]) && $step["adjustComplete"] == 1) { ?>
																<i class="fa fa-history pointer mb-10" aria-hidden="true" onclick="javascript:showAdjustHistory(<?= $step['jobStepId'] ?>,<?= $m ?>)"></i>
																<div class="adjust-duedate-history" id="history-adjust-<?= $step['jobStepId'] ?>-<?= $m ?>"></div>
														<?php
															}
														}
														?>
													</div>
													<div class="col-2 detail-due-date <?= $class ?>">
														<?= $step["status"] ?>
													</div>
													<div class="col-1 detail-due-date text-center">
														<?php
														if ($step["history"] == 1) {
														?>
															<i class="fa fa-history pointer mb-10" aria-hidden="true" onclick="javascript:showStepHistory(<?= $step['jobStepId'] ?>,<?= $m ?>)"></i>
															<div class="job-step-history" id="history-<?= $m ?>"></div>
														<?php
														}
														?>
														<br>
														<a href="javascript:showAddComment(<?= $step['jobStepId'] ?>)" class="text-primary">
															<i class="fa fa-plus pointer" aria-hidden="true"></i>
														</a>
														<?php
														if ($step["comment"] == 1) { ?>
															<br>
															<a href="javascript:showComment(<?= $step['jobStepId'] ?>)" class="text-primary">
																<i class="fa fa-eye pointer mt-10" aria-hidden="true"></i>
															</a>
														<?php
														}
														?>
													</div>
													<?php
													$a = 1;
													if (count($step["additionalStep"]) > 0) {
														foreach ($step["additionalStep"] as $additional) : ?>

															<div class="col-3 font-size12 pl-30 mt-10" style="word-wrap: break-word;">
																<?= $j . '.' . $a . '. ' . $additional["additionalStepName"] ?></div>
															<div class="col-3 font-size12 mt-10">
																<?= $additional["dueDate"] != null ? ModelMaster::engDate($additional["dueDate"], 2) : '' ?>
															</div>
															<div class="col-3 font-size12 mt-10">
																<span id="completeAdditionalDate-<?= $additional["additionalStepId"] ?>">
																	<?= $additional["completeDate"] != null ? ModelMaster::engDate($additional["completeDate"], 2) : '' ?>
																</span>
																<?php
																if ($additional["completeDate"] != null) { ?>
																	<a class="btn button-yellow button-xs" href="javascript:showAdjustAdditionalDate(<?= $additional["additionalStepId"] ?>)">
																		<i class="fa fa-pencil" aria-hidden="true"></i>
																	</a>
																	<div class="col-12 mt-10" id="adjustAddtionalDate-<?= $additional["additionalStepId"] ?>" style="display:none;padding-left:0px;padding-right:0px;">
																		<?=
																		DatePicker::widget([
																			'name' => 'adjustAdditionalDate',
																			'type' => DatePicker::TYPE_INPUT,
																			'pluginOptions' => [
																				'autoclose' => true,
																				'format' => 'yyyy-mm-dd',
																			],
																			'options' => [
																				'id' => 'adjustAdditionaldate-' . $additional["additionalStepId"],
																				'onchange' => 'javascript:saveAdjustAdditional(' . $additional["additionalStepId"] . ')',
																				'style' => [
																					'height' => '30px',
																					'width' => '100%',
																					'font-size' => '14px'
																				]
																			]
																		]);
																		?>
																	</div>
																	<?php
																	$hasAdjustAdd = AddjustDuedateAdditional::hasAdjust($additional["additionalStepId"]);
																	if ($hasAdjustAdd == 1) { ?>
																		<i class="fa fa-history pointer mb-10" aria-hidden="true" onclick="javascript:showAdjustAddHistory(<?= $additional['additionalStepId'] ?>)"></i>
																		<div class="adjust-duedate-history" id="history-adjust-add-<?= $additional['additionalStepId'] ?>"></div>
																<?php
																	}
																}
																?>
															</div>
															<div class="col-2 font-size12 <?= $class ?> mt-10">
																<?= JobStep::statusText($additional["status"]) ?>
															</div>
															<div class="col-1 font-size12">
															</div>
													<?php
															$a++;
														endforeach;
													}
													$j++;
													$m++;
													?>
													<div class="col-12">
														<hr>
													</div>
											<?php
												}
											endforeach; ?>

										<?php
										}
										?>
									</div>
								</div>
							</div>

						<?php
							$i++;

						endforeach;
						?>
						<input type="hidden" id="total-step" value="<?= $m ?>">
					</div>
				<?php

				}
				?>
			</div>
			<?php
			if (isset($complain) && count($complain) > 0) {
			?>
				<div class="col-12 complain-box">
					<div class="row">
						<div class="col-12 font-size18">
							<i class="fa fa-exclamation-triangle mr-10  text-warning" aria-hidden="true"></i><b>Complains</b>
						</div>
						<?php

						foreach ($complain as $c) : ?>
							<div class="col-12 complain-date">
								<?= ModelMaster::engDate($c["createDateTime"], 2) ?>
							</div>
							<div class="col-12 complain-text-detail">
								<?= $c["complain"] ?>
							</div>
						<?php

						endforeach;

						?>
					</div>

				</div>
			<?php
			}
			?>

		</div>
		<div class="col-12">
			<?= $this->render('change_target_date') ?>
		</div>
		<div class="col-12">
			<?= $this->render('change_fiscal_year') ?>
		</div>
		<div class="col-12">
			<?= $this->render('change_order_month') ?>
		</div>
		<div class="col-12">
			<?= $this->render('add_comment') ?>
		</div>
		<div class="col-12">
			<?= $this->render('show_comment') ?>
		</div>
	</div>
</div>