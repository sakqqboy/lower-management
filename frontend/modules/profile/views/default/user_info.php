<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
?>
<div class="col-12 text-center pl-0 pr-0">
	<?php
	if ($employee["picture"] != null) { ?>
		<img src="<?= Yii::$app->homeUrl ?>images/<?= $employee['picture'] ?>" class="profile-image-right">
		<?php
	} else {
		if ($employee['gender'] == 1) { ?>
			<img src="<?= Yii::$app->homeUrl ?>images/employee/profile.png" class="profile-image-right">
		<?php
		} else { ?>
			<img src="<?= Yii::$app->homeUrl ?>images/employee/profile-female.png" class="profile-image-right">
	<?php
		}
	}
	?>

	<div class="row text-left font-size12 pl-0 pr-0">
		<div class="col-12 mt-10 font-size16 text-center">
			<?= $employee["employeeFirstName"] ?>&nbsp;&nbsp;&nbsp;<?= $employee["employeeLastName"] ?>
		</div>
		<div class="col-12 font-size14 text-center pl-0 pr-0">
			<?= $employee['email'] ?>
		</div>

		<div class="col-4 mt-20 pl-2">
			Nickname
		</div>
		<div class="col-8  mt-20 pl-2">
			<?= $employee['employeeNickName'] ?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Tel
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= $employee['telephoneNumber'] ?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Birth Date

		</div>
		<div class="col-8 mt-10 pl-2">

			<?php
			if ($employee['birthDate'] != null) {
				echo ModelMaster::engDate($employee['birthDate'], 1);
			}
			?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Age
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= Employee::employeeAge($employee['birthDate']) ?>&nbsp;&nbsp;years
		</div>
		<div class="col-4 mt-10 pl-2">
			Gender
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= $employee['gender'] == 1 ? 'Male' : 'Female' ?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Branch
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= Branch::branchName($employee['branchId']) ?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Country
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= Branch::countryName($employee['branchId']) ?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Section
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= Section::sectionName($employee['sectionId']) ?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Position
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= Position::positionName($employee['positionId']) ?>
		</div>
		<div class="col-4 mt-10 pl-2">
			Team
		</div>
		<div class="col-8 mt-10 pl-2">
			<?= Team::teamName($employee['teamId']) ?>
		</div>
		<div class="col-12 pl-2" style="margin-top:20px;">
			<i class="fa fa-key mr-10" aria-hidden="true"></i>
			<a href="<?= Yii::$app->homeUrl ?>profile/default/change-password" class="no-underline-black">
				Change password
			</a>
		</div>
	</div>
</div>