<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\SubFieldGroup;

if (isset($fields) && count($fields) > 0) {
	$i = 1;
	foreach ($fields as $field) :
?>
		<tr id="field<?= $field["fieldId"] ?>">
			<td><?= $i ?></td>
			<td><?= SubFieldGroup::subFieldGroupName($field["subFieldGroupId"]) ?></td>
			<td><?= Branch::branchName($field["branchId"]); ?></td>
			<td id="fieldName<?= $field["fieldId"] ?>"><?= $field["fieldName"] ?></td>
			<td class="text-center">
				<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $field["fieldId"] ?>)'>
					<i class="fa fa-edit" aria-hidden="true"></i>
				</button>
				<button class="button-red button-xs" onclick='javascript:disableField(<?= $field["fieldId"] ?>)'>
					<i class="fa fa-times" aria-hidden="true"></i>
				</button>
			</td>
		</tr>
		<tr id="tr-edit<?= $field["fieldId"] ?>" style="display:none;">
			<td>
				<div class="mt-10">
					<i class="fa fa-edit" aria-hidden="true"></i>
				</div>
			</td>
			<td><?php
				if (isset($groupFields) && count($groupFields) > 0) { ?>
					<select class="form-control" name="subFieldGroup" id="subFieldGroup-<?= $field["fieldId"] ?>" required>
						<?php
						if (isset($field["subFieldGroupId"]) && $field["subFieldGroupId"] != null) { ?>
							<option value="<?= $field["subFieldGroupId"] ?>">
								<?= SubFieldGroup::subFieldGroupName($field["subFieldGroupId"]) ?>
							</option>
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
			</td>
			<td>
				<select name="branch" class="form-control" id="branch-<?= $field['fieldId'] ?>">
					<?php
					if (isset($branch) && count($branch) > 0) { ?>

						<option value="<?= $field['branchId'] ?>"><?= Branch::branchName($field['branchId']) ?></option>
						<?php

						foreach ($branch as $b) : ?>
							<option value="<?= $b['branchId'] ?>"><?= Branch::branchName($b["branchId"]); ?></option>
						<?php
						endforeach;

						?>

					<?php
					}

					?>
				</select>
			</td>
			<td>
				<input type="text" id="fieldNameInput<?= $field["fieldId"] ?>" class="form-control" placeholder="Field Name" value="<?= $field["fieldName"] ?>">
			</td>

			<td class="text-center">
				<button class="button-green button-xs mt-10" onclick='javascript:updateField(<?= $field["fieldId"] ?>)'>
					<i class="fa fa-check" aria-hidden="true"></i>
				</button>
				<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $field["fieldId"] ?>)'>
					<i class="fa fa-minus" aria-hidden="true"></i>
				</button>
			</td>
		</tr>
	<?php
		$i++;
	endforeach;
} else { ?>
	<tr class="tr-no-data">
		<td colspan="3">Not set</td>
	</tr>
<?php
}
?>