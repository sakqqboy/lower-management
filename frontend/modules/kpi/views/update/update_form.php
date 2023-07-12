<?php

use frontend\models\lower_management\PersonalKgi;
use frontend\models\lower_management\PersonalKpi;

$this->title = 'Update KPI';
?>
<div class="body-content pt-20 mb-20">
	<?= $this->render('filter', [
		"selectDate" => $selectDate,
		"yearKpi" => $yearKpi,
		"monthKpi" => $monthKpi,
	]) ?>
</div>
<div class="col-12">
	<div class="row text-center mb-10">
		<div class="title-day">Monday</div>
		<div class="title-day">Tuesday</div>
		<div class="title-day">Wednesday</div>
		<div class="title-day">Thursday</div>
		<div class="title-day">Friday</div>
		<div class="title-day">Saturday</div>
		<div class="title-day">Sunday</div>
	</div>
</div>
<div class="col-12" id="result-date">
	<?php
	if (isset($dateValue) && count($dateValue) > 0) {
		$totalCount = 0;
		$day = 1;
		$other = '';
		foreach ($dateValue as $index => $value) :
			$dateArr = explode('-', $value["date"]);
			$day = (int)$dateArr[2];
			$month = $dateArr[1];
			$year = $dateArr[0];
			if ((int)$month != (int)$selectMonth) {
				$other = "other-month";
			} else {
				$other = '';
			}
			if (($totalCount % 7) == 0) { ?>
				<div class="row">
				<?php
			}
			if ($value["date"] == date('Y-m-d 00:00:00')) {
				$box = 'sub-box-today';
			} else {
				$box = 'sub-box';
			}
				?>
				<div class="big-box-day" onclick="javasript:showAddSchedule(<?= $year . ',' . $month . ',' . $day ?>)">
					<div class="<?= $box ?> <?= $other ?>">
						<div class="date-number text-right"><?= $day ?></div>
						<?php
						$progress = PersonalKpi::updateProgress($year, $month, $day, $pkpi['personalKpiId']);
						if (count($progress) > 0) {
							foreach ($progress as $pkpiDetailId => $data) : ?>

								<div class="kpi-detail text-right" onclick="javascript:showProgressDetail(<?= $year . ',' . $month . ',' . $day . ',' . $pkpiDetailId ?>)">
									<?= $data["amount"] . $data["amountType"] ?>
								</div>
						<?php
							endforeach;
						}
						?>


					</div>
				</div>
				<?php
				$totalCount++;
				if (($totalCount % 7) == 0) { ?>
				</div>
	<?php
				}
			endforeach;
		}
	?>
</div>
<input type="hidden" value="<?= $kpi['kpiId'] ?>" id="kpi">
<input type="hidden" value="<?= $pkpi['personalKpiId'] ?>" id="pkpi">
<?= $this->render('add_progress', [
	"kpi" => $kpi,
	"pkpi" => $pkpi,
	"monthKpi" => $monthKpi,
	"yearKpi" => $yearKpi
]) ?>
<?= $this->render('progress_detail', [
	"kpi" => $kpi,
	"pkpi" => $pkpi,
	"monthKpi" => $monthKpi,
	"yearKpi" => $yearKpi
]) ?>