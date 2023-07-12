<?php

use kartik\date\DatePicker;
?>
<div class="row" style="z-index:1000;">
	<?php
	$ex = '';
	if (isset($category)) {
		$round = $category["totalRound"];
		$i = 1;
		if ($round != 0) {
			$message = "";
			if ($category["categoryName"] != "Spot") {
				if ($category["categoryName"] == "Half year") {
					$message = "Target Month";
					$ex = 'Ex. Fs of Feb choose Feb, Tax of Feb, choose Feb';
				}
				if ($category["categoryName"] == "Yearly") {
					$message = "End of Month of accounting term";
					$ex = 'Ex. Fiscal Year Jan to DEC choose DEC.';
				}
				if ($category["categoryName"] == "Monthly") {
					$message = "Target Month ";
					$ex = 'Ex. Fs or Feb choose Feb, Tax of Feb, choose Feb';
				}
				if ($category["categoryName"] == "Quaterly") {
					$message = "Target Month ";
					$ex = 'Ex. Fs or Feb choose Feb, Tax of Feb, choose Feb';
				}

	?>

				<div class="col-12 font-size14" style="margin-top: 3px;">
					<label class="label-input"><?= $message ?></label>
					<?php
					if ($ex != '') { ?>
						<label class="label-input mr-10 text-danger">( <?= $ex ?> )</label>
					<?php
					}
					?>
				</div>
			<?php
			} else { ?>
				<div class="col-12 font-size14" style="margin-top: 10px;">
					<label class="label-input"></label>
				</div>
				<?php
			}
			while ($i <= $round) {
				if ($category["categoryName"] != "Spot") {
				?>

					<div class="col-6 mb-10">
						<input type="text" name="startMonth[]" id="startMonth<?= $i; ?>" placeholder="<?= $message ?>" class="form-control" readonly onclick="javascript:showMonthCalendar(<?= $i ?>)" required>
						<div class="col-12 month-calendar-box" id="month-calendar<?= $i ?>">
							<?= $this->render('month_calendar', ["i" => $i]) ?>
						</div>
					</div>
				<?php
				}
				?>
				<div class="col-6 mb-10">
					<?= DatePicker::widget([
						'name' => 'targetDate[]',
						'id' => 'targetDate' . $i,
						'type' => DatePicker::TYPE_INPUT,
						'options' => ['placeholder' => 'Select Target Date'],
						'pluginOptions' => [
							'autoclose' => true,
							'format' => 'yyyy-mm-dd'
						]
					]);
					?>
				</div>
			<?php
				$i++;
			}
			?>
			<div class="col-6 mt-10">
				<input type="text" class="form-control" name="fiscalYear" placeholder="Fiscal year" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
			</div>
	<?php
		}
	}
	?>

</div>