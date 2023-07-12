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
	if (count($datePeriod) > 0) {
		$i = 0;
		//throw new exception(print_r($over, true));
		foreach ($datePeriod as $year => $dataYear) : ?>
			<tr>
				<td class="text-center">
					<i class="fa fa-circle mr-1 ml-2" aria-hidden="true" style="color:<?= $color[$i] ?>"></i>
					<?= $date[$year] ?>
				</td>

				<?php

				foreach ($dataYear as $period => $data) :

				?>
					<td class="text-center">
						<?php
						if ($data > 0) {
						?>
							<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail-yearly-day/<?= ModelMaster::encodeParams([
																		"year" => $date[$year],
																		"jobTypeId" => $jobTypeId,
																		"branchId" => $branchId,
																		"period" => $period,
																		"selectMonth" => $selectMonth[$year]["value"],
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
				$i++;
				?>
			</tr>
	<?php
		endforeach;
	}
	?>
</table>
<?php

if (count($dataOnprocess) > 0) {
?>
	<table class="table-bordered font-size14 mt-20" style="width:20%;">
		<tr>
			<th colspan="2">On process</th>
		</tr>
		<?php
		$i = 0;
		foreach ($dataOnprocess as $year => $onprocess) : ?>
			<tr>
				<td><i class="fa fa-circle mr-1 ml-2" aria-hidden="true" style="color:<?= $color[$i] ?>"></i><?= $date[$year] ?></td>
				<td class="text-center">
					<?php
					if ($onprocess > 0) {
					?>
						<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail-yearly-onprocess/<?= ModelMaster::encodeParams([
																		"year" => $date[$year],
																		"jobTypeId" => $jobTypeId,
																		"branchId" => $branchId,
																		"period" => $period,
																		"month" => $selectMonth[$year]["value"],
																		"teamId" => $teamId,
																		"personId" => $personId,
																		"stepId" => $stepId

																	]) ?>" class="no-underline-black">

							<?= $onprocess ?>
						</a>
					<?php
					} else {
						echo 0;
					}
					?>
				</td>
			</tr>
		<?php
			$i++;
		endforeach;
		?>
	</table>
<?php
}
?>