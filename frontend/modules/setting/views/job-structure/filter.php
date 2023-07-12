<td colspan="2">
	<?php
	if (isset($branch) && count($branch) > 0) {
		if (count($branch) == 1) {
			foreach ($branch as $b) : ?>
				<div class="col-12 font-size18 mt-10 text-center"><b><?= $b["branchName"]; ?></b></div>
				<input type="hidden" id="branchSearchStep" value="<?= $b["branchId"] ?>">
			<?php
			endforeach;
		} else {
			?>
			<select class="form-control" onchange="javascript:filterStep()" id="branchSearchStep">
				<?php
				if (isset($employeeBranch) && !empty($employeeBranch)) { ?>
					<option value="<?= $employeeBranch["branchId"] ?>"><?= $employeeBranch["branchName"] ?></option>
				<?php
				}
				?>
				<!-- <option value="">Branch</option> -->
				<?php

				foreach ($branch as $b) : ?>
					<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
				<?php
				endforeach;

				?>
			</select>
		<?php
		}
	} else {
		$disable = 'disabled';
		?>
		<div class="no-underline font-size16 mt-10"><a href="<?= Yii::$app->homeUrl ?>setting/structure/branch"> Create Branch</a></div>
	<?php }

	?>
</td>
<td>
	<select class="form-control" onchange="javascript:filterStep()" id="jobTypeSearchStep">
		<?php
		if (isset($firstJobType) && !empty($firstJobType)) { ?>
			<option value="<?= $firstJobType["jobTypeId"] ?>"><?= $firstJobType["jobTypeName"] ?></option>
		<?php
		}
		?>
		<!-- <option value="">Job Type</option> -->
		<?php
		if (isset($jobType) && count($jobType) > 0) {
			foreach ($jobType as $jt) : ?>
				<option value="<?= $jt['jobTypeId'] ?>"><?= $jt['jobTypeName'] ?></option>
		<?php
			endforeach;
		}
		?>
	</select>

</td>
<td colspan="3"></td>