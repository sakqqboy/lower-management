<table class="table table-bordered">
	<thead class="font-size14">
		<tr>
			<td class="text-left" style="background-color:#FFEBCD;" colspan="<?= (count($steps) * ($compare + 2)) + 5 ?>">
				<a href="<?= Yii::$app->homeUrl ?>job/job-type-calendar/export?b=<?= $branchId ?>&&j=<?= $jobTypeId ?>&&t=<?= $teamId ?>&&c=<?= $compare ?>" class="btn button-turqouise mr-20">
					<i class="fa fa-download mr-10" aria-hidden="true"></i>Export to excel</a>
				<span class="font-size16"><b><?= $branchName ?> </b></span>
				<span class="font-size16"><b>&nbsp;&nbsp;<?= $teamName ?>&nbsp;&nbsp; : : <?= $jobTypeName ?></b></span>
			</td>

		</tr>
		<tr>
			<td colspan="5" class="text-center" style="background-color:#FFEBCD;"><b>Steps</b></td>
			<?php

			use common\models\ModelMaster;
			use frontend\models\lower_management\Client;

			if (isset($steps) && count($steps) > 0) {
				$i = 1;
				foreach ($steps as $step) : ?>
					<th style="background-color: #FFF0F5;border:gray solid thin;" class="text-center" colspan="<?= $compare + 2 ?>"><?= $i ?>. <?= $step["stepName"] ?></th>
			<?php
					$i++;
				endforeach;
			}
			?>

		</tr>
		<tr>
			<td class="text-center" style="background-color:#FFEBCD;"><b>Clients Name</b></td>
			<td class="text-center" style="background-color:#FFFFCC;"><b>Team</b></td>
			<td class="text-center" style="background-color:#FFFFCC;"><b>PIC 1</b></td>
			<td class="text-center" style="background-color:#FFFFCC;"><b>PIC 2</b></td>
			<td class="text-center" style="background-color:#F0FFF0;"><b>Month</b></td>
			<?php
			if (isset($steps) && count($steps) > 0) {
				foreach ($steps as $step) :
					if ($compare == 2) {
			?>
						<td class="text-center" style="background-color: #FFF0F5;">Before</td>
					<?php
					}
					if ($compare == 1 || $compare == 2) {
					?>
						<td class="text-center" style="background-color: #FFF0F5; border:gray solid thin;">Last</td>
					<?php
					}
					?>
					<td class="text-center" style="background-color: #FFF0F5;border:gray solid thin;">Target</td>
					<td class="text-center" style="background-color: #FFF0F5;border:gray solid thin;">Completed</td>
			<?php
				endforeach;
			}
			?>
		</tr>
	</thead>
	<tbody class="font-size12">
		<?php
		if (isset($data) && count($data) > 0) {
			$i = 1;
			foreach ($data as $clientId => $dataSteps) : ?>
				<tr>
					<td><?= Client::clientName($clientId) ?></td>
					<td class="text-center"><?= $team[$clientId] ?></td>
					<td class="text-center"><?= isset($pic[$clientId]) ? $pic[$clientId]["pIc1"] : '' ?></td>
					<td class="text-center"><?= isset($pic[$clientId]) ? $pic[$clientId]["pIc2"] : '' ?></td>
					<td class="text-center"><?= isset($month[$clientId]) ? $month[$clientId] : '' ?></td>
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
								<td class="text-center" style="background-color: <?= $color ?>;border:gray solid thin;"><?= $s["beforeLastCompleteDate"] ?></td>
							<?php
							}
							if ($compare == 1 || $compare == 2) {
							?>
								<td class="text-center" style="background-color:<?= $color ?>;border:gray solid thin;"><?= $s["lastCompleteDate"] ?></td>
							<?php
							}
							?>
							<td class="text-center" style="background-color: <?= $color ?>;border:gray solid thin;"><?= $s["dueDate"] != '' ? ModelMaster::dateNumberShort($s["dueDate"]) : 'Not set' ?></td>
							<td class="text-center" style="background-color:<?= $color ?>;border:gray solid thin;"><b><?= $s["completeDate"] != '' ? ModelMaster::dateNumberShort($s["completeDate"]) : '' ?></b></td>
					<?php
						endforeach;
					}
					?>
				</tr>
		<?php
			endforeach;
		}
		?>

	</tbody>
</table>