<td class="font-size16 font-weight-bold">Filter ::</td>
<td>
	<?php

	use frontend\models\lower_management\FieldGroup;

	if (isset($groupFields) && count($groupFields) > 0) { ?>
		<select class="form-control" id="groupSearchField" onchange="javascript:filterField()">
			<option value="">Field Group</option>
			<?php
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
</td>

<td>
	<?php
	if (isset($branches) && count($branches) > 0) { ?>
		<select class="form-control" onchange="javascript:filterField()" id="branchSearchField">
			<option value="">Branch</option>
			<?php

			foreach ($branches as $b) : ?>
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
<td colspan="2"></td>