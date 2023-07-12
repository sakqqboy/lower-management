<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
use yii\bootstrap4\ActiveForm;

$this->title = 'Employee Detail';
?>
<div class="body-content pt-20 container">
	<div class="col-12 create-empolyee-box">
		<div class="row">
			<div class="col-12 pt-20">
				<span class="font-size26  mr-20"><?= $employee["employeeFirstName"] ?></span>
				<span class="font-size26 mr-20"><?= $employee["employeeLastName"] ?></span>
			</div>
			<div class="col-lg-3  pt-20 text-center">
				<?php
				if ($employee['picture'] == null) { ?>
					<img src="<?= Yii::$app->homeUrl ?>images/employee/nopic.jpg" class="profile-image">
				<?php
				} else { ?>
					<img src="<?= Yii::$app->homeUrl ?>images/<?= $employee['picture'] ?>" class="profile-image">
				<?php
				}
				?>

			</div>
			<div class="col-lg-9  pt-20 text-left employee-detail">
				<div class="col-12">
					Nickname :<span class="ml-20"><?= $employee['employeeNickName'] ?></span>
				</div>
				<div class="col-12 mt-20">
					Email :<span class="ml-20"><?= $employee['email'] ?></span>
				</div>
				<div class="col-12 mt-20">
					Tel :<span class="ml-20"><?= $employee['telephoneNumber'] ?></span>
				</div>
				<div class="col-12 mt-20">
					Birth Date :<span class="ml-20">
						<?php
						if ($employee['birthDate'] != null) {
							echo ModelMaster::engDate($employee['birthDate'], 1);
						}
						?>
					</span>
				</div>
				<div class="col-12 mt-20">
					Age :<span class="ml-20 mr-20"><?= Employee::employeeAge($employee['birthDate']) ?></span>
					Gender :<span class="ml-20"><?= $employee['gender'] == 1 ? 'Male' : 'Female' ?></span>
				</div>
				<div class="col-12 mt-20">
					Branch :<span class="ml-20 mr-20"><?= Branch::branchName($employee['branchId']) ?></span>
					Country :<span class="ml-20"><?= Branch::countryName($employee['branchId']) ?></span>
				</div>
				<div class="col-12 mt-20">
					Section :<span class="ml-20"><?= Section::sectionName($employee['sectionId']) ?></span>
				</div>
				<div class="col-12 mt-20">
					Position :<span class="ml-20"><?= Position::positionName($employee['positionId']) ?></span>
				</div>
				<div class="col-12 mt-20 mb-20">
					Dream Team :<span class="ml-20"><?= Team::teamName($employee['teamId']) ?></span>
				</div>
			</div>

		</div>
	</div>
</div>