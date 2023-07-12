<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi;
use frontend\models\lower_management\Kpi;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;

$this->title = $kgi["kgiName"];
?>
<div class="body-content container">
	<div class="row" style="margin-top: -20px;">
		<div class="col-12">
			<div class="row">
				<div class="col-lg-10 font-weight-bold font-size26">KPI for <?= $kgi["kgiName"] ?></div>
				<div class="col-lg-2 text-right">
					<a href="<?= Yii::$app->homeUrl ?>kpi/default/index" class="btn button-blue">
						<i class="fa fa-chevron-left mr-2" aria-hidden="true"></i>
						Back
					</a>
				</div>
				<div class="col-lg-12 col-md-6 col-6 mt-20 font-size18">
					<div class="row">

						<div class="col-lg-3 col-6">
							<b>Team</b> : <?= Team::teamName($kgi["teamId"]) ?>
						</div>

						<div class="col-lg-3 col-6">
							<b>Team Position</b> : <?= TeamPosition::positionName($kgi["teamPositionId"]) ?>
						</div>

						<div class="col-lg-3 col-6">
							<b>Branch</b> : <?= Branch::branchName($kgi["branchId"]) ?>
						</div>
						<div class="col-lg-3 col-6">
							<b>Unit</b> : <?= Kgi::unitName($kgi["unit"]) ?>
						</div>
						<div class="col-lg-6 col-12 mt-10">
							<b>Target Amount</b> :&nbsp;&nbsp;&nbsp; <?= Kgi::amountFormat($kgi["kgiId"]) ?>
						</div>
						<div class="col-lg-12 border pt-10 pb-10 mt-10 font-size16" style="border-radius: 10px;min-height:60px;">
							<?= $kgi["kgiDetail"] ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 text-right mt-10">
			<a href="<?= Yii::$app->homeUrl ?>kpi/default/create-kpi/<?= ModelMaster::encodeParams(["kgiId" => $kgi["kgiId"]]) ?>" class="btn button-green">
				<i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>
				Create new KPI
			</a>
		</div>
		<div class="col-12  mt-10">
			<?php
			if (isset($kpi) && count($kpi) > 0) { ?>
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Kpi name</th>
							<th>Detail</th>
							<th>Unit</th>
							<th>Target Amount</th>
							<th class="text-center">Action</th>

						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$symbol = '';
						$percent = '';
						foreach ($kpi as $k) :
							if ($k["symbolCheck"] == 1) {
								$symbol = '>';
							}
							if ($k["symbolCheck"] == 2) {
								$symbol = '<';
							}
							if ($k["symbolCheck"] == 3) {
								$symbol = '=';
							}
							if ($k["amountType"] == 2) {
								$percent = '%';
							} else {
								$percent = '';
							}
						?>
							<tr id="kpi-<?= $k['kpiId'] ?>">
								<td><?= $i ?></td>
								<td><?= $k["kpiName"] ?></td>
								<td><?= $k["kpiDetail"] ?></td>
								<td><?= Kpi::unitName($k["unit"]) ?></td>
								<td><?= $symbol ?> <?= $k["targetAmount"] . $percent ?></td>
								<td class="font-size14 text-center">

									<a href="<?= Yii::$app->homeUrl ?>kpi/default/update-kpi/<?= ModelMaster::encodeParams(["kpiId" => $k['kpiId']]) ?>" class="btn button-yellow button-xs">
										<i class="fa fa-edit" aria-hidden="true"></i>
									</a>

									<a href="javascript:disableKpi(<?= $k['kpiId'] ?>)" class="btn button-red button-xs font-size14">
										<i class="fa fa-trash" aria-hidden="true"></i>
									</a>
								</td>
							</tr>

						<?php
							$i++;
						endforeach;
						?>
					</tbody>
				</table>
			<?php
			}
			?>
		</div>
	</div>