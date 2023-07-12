<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\EmployeeType;

if (isset($employee) && count($employee) > 0) {
	$i = 1;
	foreach ($employee as $emp) :
?>
		<tr id="employee<?= $emp["employeeId"] ?>">
			<td><?= $i ?></td>
			<td><?= $emp["employeeFirstName"] ?>&nbsp;&nbsp;&nbsp;<?= $emp["employeeLastName"] ?></td>
			<td><?= $emp["employeeNickName"] ?></td>
			<td><?= Branch::branchName($emp["branchId"]) ?></td>
			<?php
			if (isset($userType) && count($userType) > 1) {
				foreach ($userType as $type) :
					$hasType = EmployeeType::employeeHasType($type["typeId"], $emp["employeeId"])
			?>
					<td class="text-center">
						<input type="checkbox" class="checkbox-sm" onchange="javascript:checkRight(<?= $type['typeId'] ?>,<?= $emp['employeeId'] ?>)" <?= $hasType == 1 ? 'checked' : '' ?>>
					</td>
			<?php
				endforeach;
			}
			?>
		</tr>
	<?php
		$i++;
	endforeach;
} else { ?>
	<tr class="tr-no-data">
		<td colspan="9">Not set</td>
	</tr>
<?php
}
?>