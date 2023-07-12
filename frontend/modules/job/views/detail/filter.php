<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\SubFieldGroup;
use frontend\models\lower_management\Team;

?>
<div class="row pb-10">
	<div class="col-lg-2 col-md-2 col-sm-3 col-3">
		<select class="form-control" id="branch-search-job">
			<?php
			if (count($branch) > 1) {
			?>
				<option value="<?= $branchId ?>"><?= $branchId != "" ? Branch::branchName($branchId) : 'Branch' ?></option>
			<?php
			}
			?>
			<?php
			if (isset($branch) && count($branch) > 0) {
				foreach ($branch as $b) : ?>
					<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
		<input type=hidden id="branch-search-carlendar" value="">
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3">
		<select class="form-control" id="client-search-job">
			<option value="">Client</option>
		</select>
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3">
		<?php
		if (isset($groupFields) && count($groupFields) > 0) { ?>
			<select class="form-control" name="subFieldGroup" id="group-field-search-job">
				<?php
				if (isset($subFieldGroupId) && $subFieldGroupId != null) { ?>
					<option value="<?= $subFieldGroupId ?>">
						<?= SubFieldGroup::subFieldGroupName($subFieldGroupId) ?>
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
	<div class="col-lg-2 col-md-2 col-sm-3 col-3">
		<select class="form-control" id="field-job">
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
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 ">
		<select class="form-control" id="category-search-job">
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
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 ">
		<select class="form-control" id="team-job">
			<?php
			if (isset($teams) && count($teams) > 0) { ?>
				<option value="<?= $teamId ?>"><?= Team::teamName($teamId) ?></option>
				<?php
				foreach ($teams as $team) : ?>
					<option value="<?= $team['teamId'] ?>"><?= Team::teamName($team['teamId']) ?></option>
				<?php
				endforeach;
			} else { ?>
				<option value="">Team</option>
			<?php }
			?>


		</select>
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10">
		<select class="form-control" id="user-type-search-employee">
			<option value="">Person</option>
			<?php
			if (isset($persons) && count($persons) > 0) { ?>
				<option value="">Preson</option>
				<?php


				foreach ($persons as $person) : ?>
					<option value="<?= $person["emId"] ?>"><?= $person["nickName"] ?></option>
			<?php
				endforeach;
			}
			?>
			<!--  -->
		</select>
	</div>

	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10">
		<select class="form-control" id="jobType-search">
			<option value="">Job Type</option>
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
	<!-- <div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10">
		<select class="form-control" id="status-search" onchange="javascript:filterJob()">
			<option value="">Status</option>
			<option value="<?php // Job::STATUS_INPROCESS 
						?>">On process</option>
			<option value="<?php // Job::STATUS_COMPLETE 
						?>">Complete</option>
			<option value="9">Nearly due date</option>
			<option value="10">Need to update</option>
		</select>
	</div> -->
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search0" value="<?= Job::STATUS_INPROCESS ?>" class="checkbox-sm"> On process
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search1" value="9" class="checkbox-sm"> Nearly due date
	</div>
	<div class="col-lg-2 col-md-2 col-sm-3 col-3 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search2" value="10" class="checkbox-sm"> Need to update
	</div>
	<div class="col-lg-1 col-md-2 col-sm-3 col-3 pr-0 pl-0 mt-10 pt-10 font-size16 text-white">
		<input type="checkbox" id="status-search3" value="<?= Job::STATUS_COMPLETE ?>" class="checkbox-sm"> Complete

	</div>
	<div class="col-lg-1 col-md-2 col-sm-3 col-3 mt-10  text-white text-right">
		<a href="javascript:filterJob()" class="btn button-blue pull-right"><i class="fa fa-search" aria-hidden="true"></i></a>
	</div>
</div>