<td class="font-size16 font-weight-bold">Filter ::</td>
<td>
	<?php
	if (isset($branch) && count($branch) > 0) { ?>
		<?php

		if (count($branch) == 1) {
			foreach ($branch as $b) : ?>
				<div class="col-12 font-size18 mt-10 text-center"><b><?= $b["branchName"]; ?></b></div>
				<input type="hidden" id="branchSearchJobType" value="<?= $b["branchId"] ?>">
			<?php
			endforeach;
		} else {
			?>
			<select class="form-control" onchange="javascript:filterJobType()" id="branchSearchJobType">
				<?php
				if (isset($employeeBranch) && !empty($employeeBranch)) { ?>
					<option value="<?= $employeeBranch["branchId"] ?>"><?= $employeeBranch["branchName"] ?></option>
				<?php

				}
				?>
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

	<select class="form-control" onchange="javascript:filterJobType()" id="searchJobType">
		<option value="">Job Type</option>
		<?php
		//if (isset($branch) && count($branch) == 1) {
		if (isset($jobType) && count($jobType) > 0) {
			foreach ($jobType as $jt) : ?>
				<option value="<?= $jt['jobTypeId'] ?>"><?= $jt['jobTypeName'] ?></option>
		<?php
			endforeach;
		}
		//}
		?>


	</select>

</td>
<td colspan="3"></td>