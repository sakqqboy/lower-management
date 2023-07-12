<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
?>
<input type="hidden" id="totalEmployee" value="<?= count($employee) ?>">
<?php
if (isset($employee) && count($employee) > 0) {
	$i = 1;
	foreach ($employee as $emp) :
?>
		<tr id="employee<?= $emp["employeeId"] ?>">
			<td>
				<input type="checkbox" id="deleteEmployee<?= $i ?>" class="checkbox-sm mt-1" value="<?= $emp["employeeId"] ?>">
			</td>
			<td><?= $i ?></td>
			<td><?= $emp["employeeFirstName"] ?>&nbsp;&nbsp;&nbsp;<?= $emp["employeeLastName"] ?></td>
			<td><?= $emp["email"] ?></td>
			<td><?= Branch::branchName($emp["branchId"]) ?></td>
			<td><?= Section::sectionName($emp["sectionId"]) ?></td>
			<td><?= Position::positionName($emp["positionId"]) ?></td>
			<td><?= Team::teamName($emp["teamId"]) ?> (<?= TeamPosition::positionName($emp["teamPositionId"]) ?>) </td>
			<td class="text-center">
				<a href="<?= Yii::$app->homeUrl ?>setting/employee/employee-detail/<?= ModelMaster::encodeParams(["employeeId" => $emp["employeeId"]]) ?>" class="btn button-turqouise button-xs">
					<i class="fa fa-info" aria-hidden="true"></i>
				</a>
				<a href="<?= Yii::$app->homeUrl ?>setting/employee/update-employee/<?= ModelMaster::encodeParams(["employeeId" => $emp["employeeId"]]) ?>" class="btn button-yellow button-xs">
					<i class="fa fa-edit" aria-hidden="true"></i>
				</a>
				<a class="btn button-red button-xs" onclick='javascript:disableEmployee(<?= $emp["employeeId"] ?>)'>
					<i class="fa fa-times" aria-hidden="true"></i>
				</a>
			</td>
		</tr>
	<?php
		$i++;
	endforeach;
} else { ?>
	<tr class="tr-no-data">
		<td colspan="7">Not set</td>
	</tr>
<?php
}
?>