<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;

$this->title = 'Right setting';
?>
<div class="body-content pt-20">
	<div class="col-12 pt-20 filter-row  mb-10">
		<?= $this->render('filter_right', [
			"branch" => $branch,
			"userType" => $userType
		]) ?>
	</div>

	<div class="col-12">
		<table class="table table-hover">
			<tr class="text-left table-head">
				<td>No.</td>
				<td>Name</td>
				<td>Nickname</td>
				<td>Branch</td>
				<?php
				if (isset($userType) && count($userType) > 1) {
					foreach ($userType as $type) : ?>
						<td class="text-center"><?= $type["typeName"] ?></td>
				<?php
					endforeach;
				}
				?>
			</tr>
			<tbody id="employee-right-result">
				<?php
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
			</tbody>
		</table>
	</div>
</div>