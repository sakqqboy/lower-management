<?php

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
	</tr>
	<tr style="height: 30px;" class="font-size14 text-center">
		<td><?= $chartName ?></td>
		<?php
		if (count($complete) > 0) {
			foreach ($complete as $stepId => $data) : ?>
				<td class="text-center">
					<?php
					if ($data > 0) { ?>
						<a href="<?= Yii::$app->homeUrl ?>mms/analysis/detail-job-type-step/<?= ModelMaster::encodeParams([
																	"jobTypeId" => $jobTypeId,
																	"stepId" => $stepId,
																	"teamId" => $teamId,
																	"personId" => $personId,
																	"branchId" => $branchId,
																	"selectYear" => $selectYear,
																	"selectMonth" => $selectMonth
																]) ?>" class="no-underline-black">
							<?= $data ?>
						</a>
					<?php
					} else {
						echo 0;
					}
					?>
				</td>
		<?php

			endforeach;
		}
		?>
	</tr>
</table>
<div class="row mt-20">
	<div class="col-lg-2"></div>
	<div class="col-lg-10 font-size14">
		<?php
		if (count($stepName) > 0) {
			$i = 1;
			foreach ($stepName as $stepId => $name) : ?>
				<div class="col-12 mt-10"><?= $i ?>. <?= $name ?></div>
		<?php
				$i++;
			endforeach;
		}
		?>
	</div>
</div>