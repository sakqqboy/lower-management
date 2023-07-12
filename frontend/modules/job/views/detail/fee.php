<?php

use kartik\date\DatePicker;

?>
<div class="row">
	<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
		<label class="label-input">Fee</label>
		<input type="text" name="fee" class="form-control text-right" value="<?= $job["fee"] ?>" onKeyUp="if(isNaN(this.value)){this.value='';}" required>
	</div>
	<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
		<label class="label-input">Currency</label>
		<select class="form-control" name="currency" required>
			<option value="<?= $job["currencyId"] ?>">
				<?php
				if ($job["currencyId"] != '') {
				?>
					<?= $job["currencyName"] ?>&nbsp;&nbsp;(<?= $job["code"] ?>&nbsp;&nbsp;<?= $job["symbol"] ?>)
				<?php
				} else {
					echo 'Please set currency';
				}
				?>
			</option>
			<?php
			if (isset($currency) && count($currency) > 0) {
				foreach ($currency as $c) : ?>
					<option value="<?= $c['currencyId'] ?>">
						<?= $c["name"] ?>&nbsp;&nbsp;(<?= $c["code"] ?>&nbsp;&nbsp;<?= $c["symbol"] ?>)
					</option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
		<label class="label-input">Charge Date</label>
		<?php
		$day = '';
		if (isset($job["feeChargeDate"]) &&  $job["feeChargeDate"] != null) {
			$date = explode(" ", $job["feeChargeDate"]);
			$day = $date[0];
		}
		?>
		<?=
		DatePicker::widget([
			'name' => 'feeChargeDate',
			'type' => DatePicker::TYPE_INPUT,
			'value' => $day,
			'pluginOptions' => [
				'autoclose' => true,
				'format' => 'yyyy-mm-dd'
			]
		]);
		?>
	</div>
	<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
		<label class="label-input">Advance receivable</label>
		<input type="text" name="advanceRec" class="form-control text-right" value="<?= $job["advanceReceivable"] ?>" onKeyUp="if(isNaN(this.value)){this.value='';}">
	</div>
	<div class="col-lg-4 col-md-4 col-12 text-left mt-10">
		<label class="label-input">Charge Date</label>
		<?php
		$day = '';
		if (isset($job["advancedChargDate"]) &&  $job["advancedChargDate"] != null) {
			$date = explode(" ", $job["advancedChargDate"]);
			$day = $date[0];
		}
		?>
		<?=
		DatePicker::widget([
			'name' => 'advancedChargeDate',
			'type' => DatePicker::TYPE_INPUT,
			'value' => $day,
			'pluginOptions' => [
				'autoclose' => true,
				'format' => 'yyyy-mm-dd'
			]
		]);
		?>
	</div>
	<div class="col-lg-4 text-left mt-10">
		<label class="label-input">outsourcing Fee</label>
		<input type="text" name="outsourcingFee" class="form-control text-right" value="<?= number_format($job['outSourcingFee']) ?>" onKeyUp="if(isNaN(this.value)){this.value='';}">
	</div>
	<div class="col-lg-4 text-left mt-10">
		<label class="label-input">Estimate total working time (hrs.)</label>
		<input type="text" name="estimate" class="form-control text-right" onKeyUp="if(isNaN(this.value)){this.value='';}" value="<?= $job["estimateTime"] ?>">
	</div>
	<div class="col-lg-12 text-left mt-10">
		<label class="label-input">Carefully points</label>
		<textarea class="form-control" name="memo"><?= $job['memo'] ?></textarea>
	</div>
</div>