<div class="col-2 close-box">
	<img src="<?= Yii::$app->homeUrl ?>images/icon/multiply.png" class="admin-menu-close" id="close-admin-menu">
</div>
<?php

use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Type;

$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_GM;
$access = Type::checkType($right);
$branchId = Employee::employeeBranch();
if ($access == 1) {
?>
	<a href="<?= Yii::$app->homeUrl ?>setting/structure/branch" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Branch
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/structure/position" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Position
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/structure/section" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Section
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/structure/team" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Team
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/employee" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Employee
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/employee/employee-right" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Employee Right
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl
			?>kpi/default/index" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl
							?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					KPI Setting
				</div>
			</div>
		</div>
	</a>
	<?php
	if ($branchId == 1) {
	?>
		<a href="<?= Yii::$app->homeUrl
				?>job/component/reward" class="no-underline mt-10">
			<div class="col-lg-12 list-admin-menu">
				<div class="row">
					<div class="col-lg-2 col-2">
						<img src="<?= Yii::$app->homeUrl
								?>images/icon/angle-right.png" class="admin-menu-list-icon">
					</div>
					<div class="col-lg-10 col-10 admin-menu-text">
						Reward
					</div>
				</div>
			</div>
		</a>
	<?php
	}
	?>
<?php
}
$right = 'all';
//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
$access = Type::checkType($right);
if ($access == 1) {
?>
	<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/category" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Category
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/field" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Field
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/job-type" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Job Type
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/job-step" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Job Type Step
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>job/import/index" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Import Job
				</div>
			</div>
		</div>
	</a>
	<a href="<?= Yii::$app->homeUrl ?>client/default/client-list" class="no-underline mt-10">
		<div class="col-lg-12 list-admin-menu">
			<div class="row">
				<div class="col-lg-2 col-2">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/angle-right.png" class="admin-menu-list-icon">
				</div>
				<div class="col-lg-10 col-10 admin-menu-text">
					Client
				</div>
			</div>
		</div>
	</a>
<?php
}
?>