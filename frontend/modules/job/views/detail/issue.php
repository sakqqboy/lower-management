<table>
	<tr>
		<td colspan="8" class="text-left"> </td>
		<td colspan="2" style="font-size: 16px;text-align:right;"><?= $today ?></td>
	</tr>
	<tr style="height:40px;">
		<td colspan="7" style="font-size: 20px;"><b><?= $category["clientName"] ?></b></td>
		<td colspan="3" style="font-size: 16px;text-align:right;">Tokyo consulting Firm</td>
	</tr>
	<tr>
		<td colspan="10"> </td>
	</tr>
	<tr>
		<td colspan="10"> </td>
	</tr>
	<tr>
		<td colspan="10"> </td>
	</tr>
	<tr>
		<td colspan="7" style="font-size: 18px;"><?= $category["jobName"] ?></td>
		<td class="text-left">PIC 1</td>
		<td colspan="2" class="text-left"><?= $PIC1 ?></td>
	</tr>
	<tr>
		<td colspan="7" class="text-left"></td>
		<td class="text-left">PIC 2</td>
		<td colspan="2" class="text-left"><?= $PIC2 ?></td>
	</tr>
	<tr>
		<td colspan="10"> </td>
	</tr>
	<tr>
		<td colspan="10"> </td>
	</tr>

	<tr style="background-color: #33CC99;">
		<th style="font-size:16px;text-align:center;"><b>No.</b></th>
		<th colspan="5" style="font-size:16px;text-align:center;"><b>Step</b></th>
		<th style="font-size:16px;text-align:center;"><b>Target date</b></th>
		<th style="font-size:16px;text-align:center;"><b>Completion date</b></th>
		<th style="font-size:16px;text-align:center;"><b>Status</b></th>
		<th style="font-size:16px;text-align:center;"><b>Comment</b></th>
	</tr>
	<tbody>
		<?php

		use common\models\ModelMaster;

		if (isset($steps) && count($steps) > 0) {
			$i = 1;
			foreach ($steps as $step) : ?>
				<tr>
					<td style="font-size:14px;text-align:center;display: inline-block;vertical-align: middle;"><?= $i ?></td>
					<td colspan="5" style="font-size:14px;display: inline-block;vertical-align: top;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $step["stepName"] ?></td>
					<td style="font-size:14px;text-align:center;display: inline-block;vertical-align: middle;"><?= $step["dueDate"] != null ? ModelMaster::engDate($step["dueDate"], 2) : '' ?></td>
					<td style="font-size:14px;text-align:center;display: inline-block;vertical-align: middle;"><?= $step["completeDate"] != null ? ModelMaster::engDate($step["completeDate"], 2) : '' ?></td>
					<td style="font-size:14px;text-align:center;display: inline-block;vertical-align: middle;"><?= $step["status"] == 1 ? 'Inprocess' : 'Complete' ?></td>
					<td style="font-size:13px;display: inline-block;vertical-align: top;"><?= $step["remark"] ?></td>
				</tr>
		<?php
				$i++;
			endforeach;
		}
		?>

	</tbody>
</table>