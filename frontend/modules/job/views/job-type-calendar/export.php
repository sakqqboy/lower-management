<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Step;
?>
<table>
	<tr>
		<td colspan="<?= (count($steps) * (2 + $compare)) + 5 ?>" style="text-align:left;font-size: 16px;">
			<b><?= $branchName ?> </b>&nbsp;&nbsp;: :&nbsp;&nbsp;<?= $teamName ?><?= $teamName != '' ? ' : :&nbsp;&nbsp;' : '' ?>&nbsp;&nbsp;
			<b><?= $jobTypeName ?></b>
		</td>
	</tr>
	<tr>
		<td colspan="5" style="text-align:center;font-size: 14px;background-color:#FFEBCD;"><b>Steps</b></td>
		<?php
		if (isset($steps) && count($steps) > 0) {
			$i = 1;
			foreach ($steps as $step) : ?>
				<td colspan="<?= (2 + $compare) ?>" style="text-align:center;font-size: 12px;">&nbsp;&nbsp;&nbsp;<?= $i ?>.<?= str_replace("&", "-", Step::stepName($step["stepId"])) ?></td>
		<?php
				$i++;
			endforeach;
		}
		?>

	</tr>
	<tr>
		<td style="text-align:center;font-size: 10px;background-color:#FFEBCD;">
			<b>Client Name</b>
		</td>
		<td style="text-align:center;font-size: 10px;background-color:#FFFFCC;">
			<b>Team</b>
		</td>
		<td style="text-align:center;font-size: 10px;background-color: #FFFFCC;"><b>PIC 1</b></td>
		<td style="text-align:center;font-size: 10px;background-color: #FFFFCC;"><b>PIC 2</b></td>
		<td style="text-align:center;font-size: 10px;background-color: #F0FFF0;"><b>Month</b></td>
		<?php

		if (isset($steps) && count($steps) > 0) {
			$i = 1;
			foreach ($steps as $step) :
				if ($compare == 2) {
		?>
					<td style="background-color: #E6E6FA;text-align:center;">Before</td>
				<?php
				}
				if ($compare == 2 || $compare == 1) {
				?>
					<td style="background-color: #E6E6FA;text-align:center;">Last</td>
				<?php
				}
				?>
				<td style="background-color: #E6E6FA;text-align:center;">Target</td>
				<td style="background-color: #E6E6FA;text-align:center;">Completed</td>
		<?php
				$i++;
			endforeach;
		}
		?>
	</tr>
	<tbody class="font-size12">
		<?php
		if (isset($data) && count($data) > 0) {
			$i = 1;
			foreach ($data as $clientId => $dataSteps) : ?>
				<tr>

					<td style="font-size:10px;"><?= $i ?>.<?= str_replace("&", "and", Client::clientName($clientId))  ?></td>
					<td style="font-size:10px;text-align:center;"><?= $team[$clientId] ?></td>
					<td style="text-align:center;font-size:10px;"><?= isset($pic[$clientId]) ? $pic[$clientId]["pIc1"] : '' ?></td>
					<td style="text-align:center;font-size:10px;"><?= isset($pic[$clientId]) ? $pic[$clientId]["pIc2"] : '' ?></td>
					<td style="text-align:center;font-size:10px;"><?= isset($month[$clientId]) ? $month[$clientId] : '' ?></td>
					<?php
					if (isset($dataSteps) && count($dataSteps) > 0) {
						foreach ($dataSteps as $s) :
							$color = 'black';
							if ($s['classText'] == 'text-danger') {
								$color = '#FFCCCC';
							}
							if ($s['classText'] == 'text-success') {
								$color = '#DCDCDC';
							}
							if ($s['classText'] == 'text-primary') {
								$color = '#99CCFF';
							}
							if ($s['classText'] == 'text-warning') {
								$color = '#FFCC66';
							}
							if ($s['classText'] == 'text-default') {
								$color = 'white';
							}
							if ($compare == 2) {
					?>
								<td style="text-align:center;font-size:8px;background-color: <?= $color ?>;border:gray solid thin;"><?= $s["beforeLastCompleteDate"] ?></td>
							<?php
							}
							if ($compare == 1 || $compare == 2) {
							?>
								<td style="text-align:center;font-size:8px;background-color:<?= $color ?>;border:gray solid thin;"><?= $s["lastCompleteDate"] ?></td>
							<?php
							}
							?>
							<td style="text-align:center;font-size:8px;background-color:<?= $color ?>"><?= $s["dueDate"] != '' ? ModelMaster::dateNumber($s["dueDate"]) : 'Not set' ?></td>
							<td style="text-align:center;font-size:8px;background-color:<?= $color ?>"><?= $s["completeDate"] != '' ? ModelMaster::dateNumber($s["completeDate"]) : '' ?></td>
					<?php

						endforeach;
					}
					?>
				</tr>
		<?php
				$i++;
			endforeach;
		}
		?>

	</tbody>
</table>