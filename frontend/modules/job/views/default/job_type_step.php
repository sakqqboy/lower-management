<?php

use kartik\date\DatePicker;
?>
<div class="row" style="z-index:1000;">
	<div class="col-12 font-size16 mt-30">
		<a href="javascript:seeDocument(<?= $jobTypeId ?>)" class="btn button-sky" id="see-document"><i class="fa fa-book mr-10" aria-hidden="true"></i>Documents List</a>
	</div>
	<?php
	if (isset($step) && count($step) > 0) { ?>
		<div class="col-12 font-size14 mt-10">
			<b>Set due date</b>
		</div>

		<?php
		$i = 1;
		foreach ($step as $s) :
		?>
			<div class="col-2 mb-10 text-right">
			</div>
			<div class="col-4 mb-10">
				<?= $i ?> . <?= $s["stepName"] ?>
			</div>
			<div class="col-4 mb-10">
				<?= DatePicker::widget([
					'name' => 'stepDueDate[]',
					'id' => 'step' . $i,
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
				<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image-step" onclick="javascript:additionalStep(<?= $s['stepId'] ?>)">
				<input type="hidden" id="sort-<?= $s['stepId'] ?>" value="1">
			</div>
			<div class="col-12">
				<div class="row" id="sub-step-<?= $s["stepId"] ?>">

				</div>
			</div>
	<?php
			$i++;
		endforeach;
	}
	?>

</div>