<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kpi;
?>
<div class="mt-40">
	<div class="row">

		<div class="col-12 pt-40 pb-40 mt-40">
			<div class="col-12 text-center font-size24 font-weight-bold">
				KPI
			</div>
			<div class="col-12 text-right">
				<a href="<?= Yii::$app->homeUrl ?>kpi/kpi/create-kpi" class="btn button-green">
					<i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>
					Create new KPI
				</a>
			</div>
			<?php
			if (isset($kpi) && count($kpi) > 0) { ?>
				<table class="table mt-10">
					<thead>
						<tr>
							<th>#</th>
							<th>Branch</th>
							<th>Kpi name</th>
							<th>Detail</th>
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
								<td><?= Branch::branchName($k["branchId"]) ?></td>
								<td><?= $k["kpiName"] ?></td>
								<td><?= $k["kpiDetail"] ?></td>
								<td><?= $symbol ?> <?= $k["targetAmount"] . $percent ?></td>
								<td class="font-size14 text-center">
									<a href="<?= Yii::$app->homeUrl ?>kpi/kpi/kpi-progress/<?= ModelMaster::encodeParams(["kpiId" => $k['kpiId']]) ?>" class="btn button-sky button-xs">
										<i class="fa fa-tasks" aria-hidden="true"></i> Progress
									</a>
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
</div>