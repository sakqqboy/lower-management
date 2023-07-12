<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;

$this->title = 'Employee';
?>
<div class="body-content pt-20">
	<div class="col-12 pt-20 filter-row">
		<?= $this->render('filter', [
			"branch" => $branch,
			"userType" => $userType
		]) ?>
	</div>
	<div class="col-12 mt-10 text-right mb-10">
		<a href="<?= Yii::$app->homeUrl ?>setting/employee/import-employee" class="btn button-blue mr-10">
			<i class="fa fa-upload mr-1" aria-hidden="true"></i> Uplode employee file
		</a>
		<a href="<?= Yii::$app->homeUrl ?>setting/employee/create-employee" class="btn button-green">
			<i class="fa fa-plus-square mr-1" aria-hidden="true"></i> Create new employee
		</a>

	</div>
	<div class="col-12">
		<table class="table table-hover">
			<tr class="text-center table-head">
				<td>
					<a class="btn button-red button-xs" onclick='javascript:disableSomeEmployee()'>
						<i class="fa fa-times" aria-hidden="true"></i>
					</a>
				</td>
				<td>No.</td>
				<td>Name</td>
				<td>Email</td>
				<td>Branch</td>
				<td>Section</td>
				<td>Position</td>
				<td>Team</td>
				<td>Action</td>
			</tr>
			<tbody id="employee-result">
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
			</tbody>
		</table>
	</div>
</div>