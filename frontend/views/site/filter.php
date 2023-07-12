<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\FieldGroup;
use yii\bootstrap4\ActiveForm;

$form = ActiveForm::begin([
	'id' => 'filter-job-type',
	'method' => 'post',
	'options' => [
		'enctype' => 'multipart/form-data',
	],
]);
?>
<div class="row">
	<div class="col-2">
		<select class="form-control" name="year" onchange="javascript:submitFilter()">
			<option value="<?= $currentYear ?>"><?= $currentYear ?></option>
			<?php
			$startYear = 2020;
			$i = 0;
			while ($i < 10) { ?>
				<option value="<?= $startYear ?>"><?= $startYear ?></option>
			<?php
				$startYear++;
				$i++;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" name="month" onchange="javascript:submitFilter()">
			<option value="<?= $currentMonth['value'] ?>"><?= $currentMonth["name"] ?></option>
			<?php
			$month = ModelMaster::month();
			if (isset($month) && count($month) > 0) {
				foreach ($month as $index => $m) : ?>
					<option value="<?= $index ?>"><?= $m ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" name="branch">
			<?php
			if (isset($branchId) && $branchId != null) { ?>
				<option value="<?= $branchId ?>"><?= Branch::branchName($branchId) ?></option>
				<option value="">Branch</option>
			<?php } else {
			?>
				<option value="">Branch</option>
				<?php
			}
			if (isset($branch) && count($branch) > 0 && $isManager == 1) {
				foreach ($branch as $b) : ?>
					<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>

	<div class="col-2">
		<select class="form-control" name="group" onchange="javascript:submitFilter()">
			<?php
			if (isset($groupFieldId) && $groupFieldId != null) { ?>
				<option value="<?= $groupFieldId ?>"><?= FieldGroup::fieldGroupName($groupFieldId) ?></option>
				<option value="">Group Fields</option>
			<?php } else {
			?>
				<option value="">Group Fields</option>
				<?php
			}
			if (isset($groups) && count($groups) > 0) {
				foreach ($groups as $group) : ?>
					<option value="<?= $group["fieldGroupId"] ?>"><?= $group["fieldGroupName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" name="field" onchange="javascript:submitFilter()">
			<?php
			if (isset($fieldId) && $fieldId != null) { ?>
				<option value="<?= $fieldId ?>"><?= Field::fieldNameFilter($fieldId) ?></option>
				<option value="">Field</option>
			<?php } else {
			?>
				<option value="">Field</option>
				<?php
			}
			if (isset($fields) && count($fields) > 0) {
				foreach ($fields as $field) : ?>
					<option value="<?= $field["fieldId"] ?>"><?= $field["fieldName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>

</div>
<?php ActiveForm::end(); ?>