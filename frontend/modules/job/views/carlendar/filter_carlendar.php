<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;

?>
<div class="row">
	<div class="col-2">
		<select class="form-control" id="branch-search-carlendar">
			<option value="<?= $branchId ?>"><?= $branchId != "" ? Branch::branchName($branchId) : 'Branch' ?></option>
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
		<input type=hidden id="branch-search-job" value="">
	</div>
	<div class="col-2">
		<select class="form-control" id="field-job" onchange="javascript:filterJobCarlendar()">
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
	<div class="col-2">
		<select class="form-control" id="user-type-search-employee" onchange="javascript:filterJobCarlendar()">
			<option value="">Person</option>
			<?php
			if (isset($persons) && count($persons) > 0) { ?>
				<option value="">Person</option>
				<?php


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
			<option value="">Status</option>
			<option value="<?= Job::STATUS_INPROCESS ?>">On process</option>
			<option value="<?= Job::STATUS_COMPLETE ?>">Complete</option>
			<option value="9">Nearly due date</option>
			<option value="10">Need to update</option>
		</select>
	</div>
</div>