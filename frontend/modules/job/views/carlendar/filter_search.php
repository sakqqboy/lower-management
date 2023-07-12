<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Field;

?>
<div class="row">
	<div class="col-2">
		<select class="form-control" id="branch-search-carlendar">
			<option value="<?= $branchId ?>"><?= Branch::branchNameFilter($branchId); ?></option>
			<option value="">Branch</option>
			<?php

			use frontend\models\lower_management\Job;
			use frontend\models\lower_management\Team;

			if (isset($branch) && count($branch) > 0) {
				foreach ($branch as $b) : ?>
					<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
		<input type=hidden id="branch-search-job" value="<?= $branchId ?>">
	</div>
	<div class="col-2">
		<select class="form-control" id="field-job" onchange="javascript:filterJobCarlendar()">
			<option value="<?= $fieldId ?>"><?= Field::fieldNameFilter($fieldId); ?></option>
			<option value="">Field</option>
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
		<select class="form-control" id="category-search-job" onchange="javascript:filterJobCarlendar()">
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
		<select class="form-control" id="team-job" onchange="javascript:filterJobCarlendar()">
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
	<div class="col-2">
		<select class="form-control" id="user-type-search-employee" onchange="javascript:filterJobCarlendar()">
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
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="status-search" onchange="javascript:filterJobCarlendar()">
			<?php
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
			}
			?>
			<option value="<?= $postStatus ?>"><?= $text ?></option>
			<option value="">Status</option>
			<option value="<?= Job::STATUS_INPROCESS ?>">On process</option>
			<option value="<?= Job::STATUS_COMPLETE ?>">Complete</option>
			<option value="9">Nearly due date</option>
			<option value="10">Need to update</option>
		</select>
	</div>
</div>