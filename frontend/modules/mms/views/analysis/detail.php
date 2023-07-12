<?php
//throw new Exception(print_r($dataDay, true));

use common\models\ModelMaster;

?>
<table class="table-bordered font-size12" style="width:100%;">
	<tr>
		<td></td>
		<?php
		foreach ($xData as $day) : ?>
			<td class="text-center"><?= $day ?><div></div>
			</td>
		<?php

		endforeach;
		?>
		<td class="text-center">over</td>
	</tr>
	<?php
	if (count($dataDay) > 0) {
		$i = 0;
		//throw new exception(print_r($over, true));
		foreach ($dataDay as $year => $dataYear) :
			foreach ($dataYear as $monthIndex => $dataMonth) : ?>
				<tr>
					<td>
						<i class="fa fa-circle mr-1 ml-2" aria-hidden="true" style="color:<?= $color[$i] ?>"></i>
						<?= ModelMaster::shotMonthText($monthIndex) ?> <?= $date[$year]["year"] ?>
					</td>
					<?php
					foreach ($dataMonth as $index => $data) :

					?>
						<td class="text-center">
							<?php
							if ($data > 0) {
							?>
								<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail1-monthly-day/<?= ModelMaster::encodeParams([
																			"year" => $date[$year]["year"],
																			"month" => $monthIndex,
																			"day" => $index,
																			"jobTypeId" => $jobTypeId,
																			"branchId" => $branchId,
																			"teamId" => $teamId,
																			"personId" => $personId,
																			"stepId" => $stepId

																		]) ?>" class="no-underline-black">


									<?= $data ?>
								</a>
							<?php
							} else { ?>
								<?= $data ?>
							<?php
							}
							?>
						</td>
					<?php

					endforeach;
					if (isset($over[$year][$monthIndex])) { ?>
						<td class="text-center">
							<?php
							if ($over[$year][$monthIndex] > 0) {
							?>
								<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail1-monthly/<?= ModelMaster::encodeParams([
																		"year" => $date[$year]["year"],
																		"month" => $monthIndex,
																		//"day" => 99,
																		"period" => 35,
																		"jobTypeId" => $jobTypeId,
																		"branchId" => $branchId,
																		"teamId" => $teamId,
																		"personId" => $personId,
																		"stepId" => $stepId

																	]) ?>" class="no-underline-black">



									<?= $over[$year][$monthIndex] ?>
								</a>
							<?php
							} else { ?>
								<?= $over[$year][$monthIndex] ?>
							<?php
							}
							?>
						</td>
					<?php
					}
					?>
				</tr>

	<?php
				$i++;
			endforeach;
		endforeach;
	}
	?>
</table>
<?php

if (count($dataOnprocess) > 0) {
?>
	<table class="table-bordered font-size14 mt-20" style="width:20%;">
		<tr>
			<th colspan="2" class="text-center">On process</th>
		</tr>
		<?php
		$i = 0;
		foreach ($dataOnprocess as $year => $monthData) :
			ksort($monthData);
			foreach ($monthData as $month => $value) :
		?>
				<tr>
					<td>
						<i class="fa fa-circle mr-1 ml-2" aria-hidden="true" style="color:<?= $color[$i] ?>"></i><?= ModelMaster::shotMonthText($month) ?> <?= $date[$year]["year"] ?>
					</td>
					<td class="text-center">
						<?php
						if ($value > 0) {
						?>
							<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail-monthly-on-process/<?= ModelMaster::encodeParams([
																			"year" => $date[$year]["year"],
																			"month" => $month,
																			"day" => $index,
																			"jobTypeId" => $jobTypeId,
																			"branchId" => $branchId,
																			"teamId" => $teamId,
																			"personId" => $personId,
																			"stepId" => $stepId

																		]) ?>" class="no-underline-black">



								<?= $value ?>
							</a>
						<?php
						} else { ?>
							<?= $data ?>
						<?php
						}
						?>
					</td>
				</tr>
		<?php
				$i++;
			endforeach;

		endforeach;
		?>
	</table>
<?php
}
?>