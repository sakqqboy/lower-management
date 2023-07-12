<?php

use frontend\models\lower_management\Branch;
use yii\bootstrap4\ActiveForm;

$this->title = 'Field';
?>
<div class="body-content pt-20 container">
	<div class="row">
		<div class="col-12">
			Ex. Accounting&Tax, Legal, Other, ..
		</div>
	</div>
	<table class="table table-hover">
		<tr class="text-center table-head">
			<td>No.</td>
			<td>Branch</td>
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
			<td>
				<?php
				if (isset($branch) && count($branch) > 0) { ?>
					<select name="branch" class="form-control">
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
			<td class="border-right">
				<input type="text" name="fieldName" class="form-control" placeholder="Field Name" required>

			</td>
			<td class="text-center">
				<button class="button-blue button-md" tyle="submit">Create</button>
			</td>
		</tr>
		<?php ActiveForm::end(); ?>
		<tr class="tr-filter"><?= $this->render('filter_field', ["branch" => $branch]) ?></tr>
		<tbody id="field-search">
			<?php
			if (isset($fields) && count($fields) > 0) {
				$i = 1;
				foreach ($fields as $field) :
			?>
					<tr id="field<?= $field["fieldId"] ?>">
						<td><?= $i ?></td>
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
						<td>
							<select name="branch" class="form-control" id="branchInput<?= $field['fieldId'] ?>">
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
		</tbody>
	</table>
</div>