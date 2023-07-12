<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\SubFieldGroup;
use yii\bootstrap4\ActiveForm;

$this->title = 'Field';
?>
<div class="body-content pt-20 container">
	<div class="row">
		<div class="col-6">
			Ex. Accounting&Tax, Legal, Other, ..
		</div>
		<?php
		//if ($isAdmin == 1) {
		?>
		<div class="col-6 text-right mb-10">
			<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/import-field" class="btn button-blue mr-10">
				<i class="fa fa-upload mr-1" aria-hidden="true"></i> Uplode Field file
			</a>
		</div>
		<?php
		//}
		?>
	</div>
	<table class="table table-hover">
		<tr class="text-center table-head">
			<td>No.</td>
			<td>Group</td>
			<td style="width: 18%">Branch</td>
			<td>Field</td>
			<td>Action</td>
		</tr>
		<?php $form = ActiveForm::begin([
			'id' => 'field-form',
			'action' => Yii::$app->homeUrl . 'setting/job-structure/create-field',
			'method' => 'post'
		]); ?>
		<tr>
			<td>
				<div class="text-success mt-10">
					<i class="fa fa-plus" aria-hidden="true"></i>
				</div>
			</td>
			<td><?php
				if (isset($groupFields) && count($groupFields) > 0) { ?>
					<select class="form-control" name="subFieldGroup" required>
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

			<td><?php
				if (isset($branches) && count($branches) > 0) { ?>
					<select class="form-control" name="branch" required>
						<option value="">Branch</option>
						<?php
						foreach ($branches as $b) : ?>
							<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
						<?php
						endforeach;

						?>
					</select>
				<?php
				} ?>
			</td>

			<td class="border-right">
				<input type="text" name="fieldName" class="form-control" placeholder="Field Name" required>

			</td>
			<td class="text-center">
				<button class="button-blue button-md" tyle="submit">Create</button>
			</td>
		</tr>
		<tr class="tr-filter"><?= $this->render('filter_field', ["branches" => $branches, "groupFields" => $groupFields]) ?></tr>
		<?php ActiveForm::end(); ?>
		<tbody id="field-search">
			<?php
			if (isset($fields) && count($fields) > 0) {
				$i = 1;
				foreach ($fields as $field) :
			?>
					<tr id="field<?= $field["fieldId"] ?>">
						<td><?= $i ?></td>
						<td><?= SubFieldGroup::subFieldGroupName($field["subFieldGroupId"]) ?></td>
						<td><?= Branch::branchName($field["branchId"]) ?></td>
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
						<td>
							<?php
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
						<td><?php
							if (isset($branches) && count($branches) > 0) { ?>
								<select class="form-control" name="branch" id="branch-<?= $field["fieldId"] ?>" required>
									<?php
									if ($field["branchId"] != null) { ?>
										<option value="<?= $field["branchId"] ?>">
											<?= Branch::branchName($field["branchId"]) ?>
										</option>
									<?php
									} else { ?>
										<option value="">Branch</option>
									<?php
									}
									foreach ($branches as $b) : ?>
										<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
									<?php
									endforeach;

									?>
								</select>
							<?php
							} ?>
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
		</tbody>
	</table>
</div>