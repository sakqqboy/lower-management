<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;

if (isset($clients) && count($clients) > 0) {
	$i = 1;
	foreach ($clients as $client) :
?>
		<tr id="client<?= $client["clientId"] ?>">
			<td><?= $i ?></td>
			<td><?= $client["clientName"] ?></td>
			<td><?= Branch::branchName($client["branchId"]) ?></td>
			<td><?= $client["email"] ?></td>
			<td><?= $client["clientTel1"] ?></td>
			<td><?= $client["remark"] ?></td>
			<td>
				<a href="<?= Yii::$app->homeUrl ?>client/default/client-detail/<?= ModelMaster::encodeParams(["clientId" => $client["clientId"]]) ?>" class="btn button-turqouise button-xs">
					<i class="fa fa-info" aria-hidden="true"></i>
				</a>
				<a href="<?= Yii::$app->homeUrl ?>client/default/update-client/<?= ModelMaster::encodeParams(["clientId" => $client["clientId"]]) ?>" class="btn button-yellow button-xs">
					<i class="fa fa-edit" aria-hidden="true"></i>
				</a>
				<a class="btn button-red button-xs" onclick=' javascript:disableClient(<?= $client["clientId"] ?>)'>
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>
			</td>
		</tr>
	<?php
		$i++;
	endforeach;
} else { ?>
	<tr class="tr-no-data">
		<td colspan="7">Not Found</td>
	</tr>
<?php
}
?>