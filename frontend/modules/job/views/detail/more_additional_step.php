<?php

use kartik\date\DatePicker;

?>

<div class="col-12" id="add-<?= $stepId ?>-<?= $id ?>">
	<div class="row">
		<div class="col-3 pl-40 mt-10">
			<input type="text" value="" name="moreStep[<?= $stepId ?>][<?= $sort ?>]" class="form-control" required>
		</div>
		<div class="col-2  mt-10">
			<?= DatePicker::widget([
				'name' => 'firstsubStepDueDate[' . $stepId . '][' . $sort . ']',
				//'id' => $id,
				'disabled' => true,
				'type' => DatePicker::TYPE_INPUT,
				'options' => ['placeholder' => 'Select Due Date'],
				'pluginOptions' => [
					'autoclose' => true,
					'format' => 'yyyy-mm-dd'
				],
				'options' => ['class' => 'form-control text-center']
			]);
			?>
		</div>
		<div class="col-2  mt-10">
			<?= DatePicker::widget([
				'name' => 'subStepDueDate[' . $stepId . '][' . $sort . ']',
				'id' => $id,
				'type' => DatePicker::TYPE_INPUT,
				'options' => ['placeholder' => 'Select Due Date'],
				'pluginOptions' => [
					'autoclose' => true,
					'format' => 'yyyy-mm-dd'
				],
				'options' => ['class' => 'form-control text-center']
			]);
			?>
		</div>
		<div class="col-1 text-center  mt-10">
			<img src="<?= Yii::$app->homeUrl ?>images/icon/crossed.png" class="add-image-step" onclick="javascript:deleteAdditionalStep(<?= $stepId ?>,'<?= $id ?>')">
		</div>
		<div class="col-4">

		</div>
	</div>
</div>