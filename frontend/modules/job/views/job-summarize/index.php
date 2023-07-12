<?php

use common\models\ModelMaster;

$this->title = 'Summarize';
?>
<div class="body-content pt-20 mb-50">

	<div class="col-12">
		<div class="row">
			<div class="offset-lg-1 offset-md-1 col-lg-5 col-md-5 col-12 text-left all-job-summarize pl-50">
				Summarize of<span class="ml-20"><b><?= $year ?></b></span>
			</div>
			<div class="col-lg-6 col-md-6 col-12 text-right all-job-summarize">
				<span class="mr-20">Total Jobs : <b><i><?= number_format($allJob) ?></i></b>
				</span><span>Total Clients : <b><i><?= number_format($allClient) ?></i></b></span>
			</div>
		</div>
		<div class="row mt-40">
			<?php
			if (isset($data) && count($data) > 0) {
				foreach ($data as $branchId => $total) :
					if ($total["totalJob"] > 0) {
			?>
						<div class="col-lg-3 col-md-4 col-sm-6 col-12 mt-20">
							<a href="<?= Yii::$app->homeUrl ?>job/job-summarize/branch-summary/<?= ModelMaster::encodeParams([
																		"branchId" => $branchId
																	]) ?>" class="no-underline">
								<div class="col-12  summarize-box">
									<div class="row">
										<div class="col-9 font-size16"><b>
												<?= $total["branchName"] ?></b>
										</div>
										<div class="col-3">
											<img src="<?= Yii::$app->homeUrl ?>images/flag/<?= $total['flag'] ?>" class="sumarrize-flag">
										</div>
									</div>
									<div class="row mt-20">
										<div class="col-6 text-left pl-20 info-summarize">
											Clients<br>
											Jobs<br>
											Manuals<br>
											Checklists<br>
											Employee<br>
											Business Report
										</div>
										<div class="col-6 pl-20 border-left info-summarize summarize-number">
											<b><?= number_format($total["totalClient"]) ?></b><br>
											<b><?= number_format($total["totalJob"]) ?></b><br>
											<b><?= number_format($total["totalManuals"]) ?></b><br>
											<b><?= number_format($total["totalChecklists"]) ?></b><br>
											<b><?= number_format($total["totalEmployee"]) ?></b><br>
											<b><?= number_format($total["totalNeedReport"]) ?></b>
										</div>
									</div>
								</div>
							</a>
						</div>
			<?php
					}
				endforeach;
			}
			?>
		</div>
	</div>
</div>