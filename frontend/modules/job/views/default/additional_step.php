<?php

use kartik\date\DatePicker;

?>
<div id="add-<?= $stepId ?>-<?= $id ?>" class="col-12">
	<div class="row">
		<div class="col-2 mb-10 text-right">
		</div>
		<div class="col-4 mb-10">
			<input type="text" name="subStepName[<?= $stepId ?>][<?= $sort ?>]" class="form-control">
		</div>
		<div class="col-4 mb-10">
			<?= DatePicker::widget([
				'name' => 'subStepDueDate[' . $stepId . '][' . $sort . ']',
				'id' => $id,
				'type' => DatePicker::TYPE_INPUT,
				'options' => ['placeholder' => 'Select Due Date'],
				'pluginOptions' => [
					'autoclose' => true,
					'format' => 'yyyy-mm-dd'
				]
			]);
			?>
		</div>
		<div class="col-2 text-left">
			<img src="<?= Yii::$app->homeUrl ?>images/icon/crossed.png" class="add-image-step" onclick="javascript:deleteAdditionalStep(<?= $stepId ?>,'<?= $id ?>')">
		</div>
	</div>
</div>