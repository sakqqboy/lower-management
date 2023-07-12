<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi;
use frontend\models\lower_management\TeamPosition;

$i = 1;
$symbol = '';
foreach ($kgi as $k) :
	if ($k["symbolCheck"] == 1) {
		$symbol = '>';
	}
	if ($k["symbolCheck"] == 2) {
		$symbol = '<';
	}
	if ($k["symbolCheck"] == 3) {
		$symbol = '=';
	}
?>
	<tr id="kgi-<?= $k['kgiId'] ?>">
		<td><?= $i ?></td>
		<td><?= Branch::branchName($k["branchId"]) ?></td>
		<td><?= $k["kgiName"] ?></td>
		<td><?= Kgi::unitName($k["unit"]) ?></td>
		<td><?= $symbol ?> <?= number_format($k["targetAmount"], 2) ?></td>
		<td><?= TeamPosition::positionName($k["teamPositionId"]); ?></td>
		<td class="font-size14 text-center">
			<a href="<?= Yii::$app->homeUrl ?>kpi/default/kgi-detail/<?= ModelMaster::encodeParams(["kgiId" => $k['kgiId']]) ?>" class="btn button-sky button-xs">
				<i class="fa fa-eye" aria-hidden="true"></i>
			</a>
			<a href="<?= Yii::$app->homeUrl ?>kpi/default/update-kgi/<?= ModelMaster::encodeParams(["kgiId" => $k['kgiId']]) ?>" class="btn button-yellow button-xs">
				<i class="fa fa-edit" aria-hidden="true"></i>
			</a>
			<a href="<?= Yii::$app->homeUrl ?>kpi/default/create-kpi/<?= ModelMaster::encodeParams(["kgiId" => $k['kgiId']]) ?>" class="btn button-blue button-xs">
				<i class="fa fa-plus-circle mr-1" aria-hidden="true"></i>
				KPI
			</a>
			<a href="javascript:disableKgi(<?= $k['kgiId'] ?>)" class="btn button-red button-xs font-size14">
				<i class="fa fa-trash" aria-hidden="true"></i>
			</a>
		</td>
	</tr>

<?php
	$i++;
endforeach;
?>