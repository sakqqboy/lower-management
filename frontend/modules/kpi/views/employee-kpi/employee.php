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
			"userType" => $userType,
			"teamPosition" => $teamPosition
		]) ?>
	</div>
	<div class="col-12">
		<table class="table table-hover">
			<tr class="tex-left table-head">

				<td>No.</td>
				<td>Name</td>
				<td>Email</td>
				<td>Branch</td>
				<td>Section</td>
				<td>Position</td>
				<td>Team</td>
				<td>Action</td>
			</tr>
			<tbody id="employee-result" class="font-size14">
				<input type="hidden" id="totalEmployee" value="<?= count($employee) ?>">
				<?php
				if (isset($employee) && count($employee) > 0) {
					$i = 1;
					foreach ($employee as $emp) :
				?>
						<tr id="employee<?= $emp["employeeId"] ?>">

							<td><?= $i ?></td>
							<td><?= $emp["employeeFirstName"] ?>&nbsp;&nbsp;&nbsp;<?= $emp["employeeLastName"] ?></td>
							<td><?= $emp["email"] ?></td>
							<td><?= Branch::branchName($emp["branchId"]) ?></td>
							<td><?= Section::sectionName($emp["sectionId"]) ?></td>
							<td><?= Position::positionName($emp["positionId"]) ?></td>
							<td><?= Team::teamName($emp["teamId"]) ?> (<?= TeamPosition::positionName($emp["teamPositionId"]) ?>) </td>
							<td class="text-left">
								<a href="<?= Yii::$app->homeUrl ?>kpi/employee-kpi/employee-kpi/<?= ModelMaster::encodeParams(["employeeId" => $emp["employeeId"]]) ?>" class="btn button-turqouise button-xs pt-1 pb-1">
									<i class="fa fa-info mr-1" aria-hidden="true"></i> KPI
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