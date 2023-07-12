<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\master\CategoryMaster;
use frontend\models\lower_management\SubFieldGroup;
use frontend\models\lower_management\Team;

?>
<div class="row pb-10">
	<div class="col-2">
		<select class="form-control" id="branch-search-job">
			<option value="<?= $branchId ?>"><?= Branch::branchNameFilter($branchId); ?></option>
			<option value="">Branch</option>
			<?php


			if (isset($branch) && count($branch) > 0) {
				foreach ($branch as $b) : ?>
					<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
		<input type=hidden id="branch-search-carlendar" value="<?= $branchId ?>">
	</div>
	<div class="col-2">
		<select class="form-control" id="client-search-job">
			<?php
			if (isset($clientId) && $clientId != "") { ?>
				<option value="<?= $clientId ?>"><?= Client::clientName($clientId) ?></option>
				<option value="">Client</option>
			<?php
			} else { ?>
				<option value="">Client</option>
			<?php
			}
			?>

			<?php


			if (isset($client) && count($client) > 0) {
				foreach ($client as $c) : ?>
					<option value="<?= $c['clientId'] ?>"><?= $c['clientName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">

		<?php
		if (isset($groupFields) && count($groupFields) > 0) { ?>
			<select class="form-control" name="subFieldGroup" id="group-field-search-job">
				<?php
				if (isset($groupFieldId) && $groupFieldId != null) { ?>
					<option value="<?= $groupFieldId ?>">
						<?= SubFieldGroup::subFieldGroupName($groupFieldId) ?>
					</option>
					<option value="">Field Group</option>
				<?php
				} else { ?>
					<option value="">Field Group</option>
				<?php
				}
				$groupIndex = 1;
				foreach ($groupFields as $groupId => $sub) : ?>
					<option value="" disabled style="background-color: lightgray;font-weight:bolder;">
						<?= $groupIndex ?>.&nbsp;&nbsp;<?= FieldGroup::fieldGroupName($groupId) ?>
					</option>
					<?php
					if (count($sub) > 0) {
						$subIndex = 1;
						foreach ($sub as $subGroupId => $subGroup) : ?>
							<option value="<?= $subGroupId ?>">&nbsp;&nbsp;&nbsp;
								<?= $groupIndex . '.' . $subIndex ?>&nbsp;&nbsp;<?= $subGroup["name"] ?>
							</option>
				<?php
							$subIndex++;
						endforeach;
					}
					$groupIndex++;
				endforeach;

				?>
			</select>
		<?php
		} ?>
	</div>
	<div class="col-2">
		<select class="form-control" id="field-job">
			<?php
			if ($fieldId != "") {

			?>
				<option value="<?= $fieldId ?>"><?= Field::fieldNameFilter($fieldId); ?></option>
				<option value="">Field</option>
			<?php
			} else { ?>
				<option value="">Field</option>
			<?php
			}
			?>

			<?php
			if (isset($fields) && count($fields) > 0) {
				foreach ($fields as $field) : ?>
					<option value="<?= $field['fieldId'] ?>"><?= $field['fieldName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="category-search-job">
			<option value="<?= $categoryId ?>"><?= Category::categoryNameNameFilter($categoryId); ?></option>
			<option value="">Category</option>
			<?php
			if (isset($category) && count($category) > 0) {
				foreach ($category as $c) : ?>
					<option value="<?= $c['categoryId'] ?>"><?= $c['categoryName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="team-job">
			<option value="<?= $postTeamId ?>"><?= Team::teamNameFilter($postTeamId); ?></option>
			<option value="">Team</option>
			<?php
			if (isset($team) && count($team) > 0) {
				foreach ($team as $t) : ?>
					<option value="<?= $t["teamId"] ?>"><?= Team::teamName($t["teamId"]) ?></option>
			<?php
				endforeach;
			}
			?>

		</select>
	</div>
	<div class="col-2 mt-10">
		<select class="form-control" id="user-type-search-employee">
			<option value="<?= $personId ?>"><?= Employee::EmployeeNickNameFilter($personId) ?></option>
			<option value="">Person</option>
			<?php
			if (isset($persons) && count($persons) > 0) {
				foreach ($persons as $person) : ?>
					<option value="<?= $person["emId"] ?>"><?= $person["nickName"] ?></option>
			<?php
				endforeach;
			}
			?>

			<!--  -->
		</select>
	</div>
	<!-- <div class="col-2 mt-10">
		<select class="form-control" id="status-search" onchange="javascript:filterJob()"> -->
	<?php /*
			if (isset($postStatus) && $postStatus != '') {
				if ($postStatus == Job::STATUS_INPROCESS) {
					$value = Job::STATUS_INPROCESS;
					$text = "On process";
				}
				if ($postStatus == Job::STATUS_COMPLETE) {
					$value = Job::STATUS_COMPLETE;
					$text = "Complete";
				}
				if ($postStatus == 9) {
					$value = 9;
					$text = "Nearly due date";
				}
				if ($postStatus == 10) {
					$value = 10;
					$text = "Need to update";
				}
			} else {
				$value = '';
				$text = "Status";
			}*/
	?>
	<!-- <option value="<?php // $value 
				?>"><?php // $text 
					?></option>
			<option value="">Status</option>
			<option value="<?php // Job::STATUS_INPROCESS 
						?>">On process</option>
			<option value="<?php // Job::STATUS_COMPLETE 
						?>">Complete</option>
			<option value="9">Nearly due date</option>
			<option value="10">Need to update</option> -->
	<!-- </select>
	</div> -->
	<div class="col-2 mt-10">
		<select class="form-control" id="jobType-search">
			<?php
			if (isset($postJobTypeId) && $postJobTypeId != null) { ?>
				<option value="<?= $postJobTypeId ?>"><?= JobType::jobTypeName($postJobTypeId) ?></option>
				<option value="">Job Type</option>
			<?php
			} else { ?>
				<option value="">Job Type</option>
			<?php
			}
			?>

			<?php
			if (isset($jobType) && count($jobType) > 0) {
				foreach ($jobType as $j) : ?>
					<option value="<?= $j['jobTypeId'] ?>"><?= $j["jobTypeName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search0" value="<?= Job::STATUS_INPROCESS ?>" <?= Job::checkStatus($postStatus, 1) == 1 ? 'checked' : '' ?> class="checkbox-sm">
		On process
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search1" value="9" class="checkbox-sm" <?= Job::checkStatus($postStatus, 9) == 1 ? 'checked' : '' ?>> Nearly due date
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search2" value="10" class="checkbox-sm" <?= Job::checkStatus($postStatus, 10) == 1 ? 'checked' : '' ?>> Need to update
	</div>
	<div class="col-lg-1 col-md-2 col-sm-3 col-3 pr-0 pl-0 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search3" value="<?= Job::STATUS_COMPLETE ?>" <?= Job::checkStatus($postStatus, 4) == 1 ? 'checked' : '' ?> class="checkbox-sm"> Complete
	</div>
	<div class="col-lg-1 col-md-2 col-sm-3 col-3 mt-10  text-white text-right">
		<a href="javascript:filterJob()" class="btn button-blue pull-right"><i class="fa fa-search" aria-hidden="true"></i></a>
	</div>
</div>