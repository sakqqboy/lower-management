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
				if (isset($searchBranch) && !empty($searchBranch)) { ?>
					<option value="<?= $searchBranch["branchId"] ?>"><?= $searchBranch["branchName"] ?></option>
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
		if (isset($searchJobType) && !empty($searchJobType)) { ?>
			<option value="<?= $searchJobType["jobTypeId"] ?>"><?= $searchJobType["jobTypeName"] ?></option>
		<?php
		}
		?>
		<!-- <option value="">Job Type</option> -->
		<?php
		if (isset($jobTypeFilter) && count($jobTypeFilter) > 0) {
			foreach ($jobTypeFilter as $jt) : ?>
				<option value="<?= $jt['jobTypeId'] ?>"><?= $jt['jobTypeName'] ?></option>
		<?php
			endforeach;
		}
		?>
	</select>

</td>
<td colspan="3"></td>