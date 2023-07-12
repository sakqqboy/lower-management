<td class="font-size16 font-weight-bold">Filter ::</td>
<td>
	<?php
	if (isset($branch) && count($branch) > 0) { ?>
		<select class="form-control" onchange="javascript:filterSection()" id="branchSearchSection">
			<option value="">Branch</option>
			<?php

			foreach ($branch as $b) : ?>
				<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
			<?php
			endforeach;

			?>
		</select>
	<?php
	} else {
		$disable = 'disabled';
	?>
		<div class="no-underline font-size16 mt-10"><a href="<?= Yii::$app->homeUrl ?>setting/structure/branch"> Create Branch</a></div>
	<?php }

	?>
</td>
<td colspan="3"></td>