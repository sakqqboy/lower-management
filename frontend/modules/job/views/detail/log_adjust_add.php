<div class="font-size16 mb-10">Log Adjust Complete Date</div>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>Date</th>
			<th>Lms Date</th>
			<th>Change to</th>
			<th>User</th>
		</tr>
	</thead>
	<tbody>
		<?php

		use common\models\ModelMaster;
		use frontend\models\lower_management\Employee;

		if (isset($log) && count($log) > 0) {
			$i = 1;
			foreach ($log as $l) : ?>
				<tr>
					<td><?= $i ?></td>
					<td><?= ModelMaster::engDate($l["createDateTime"], 2) ?></td>
					<td><?= $l["lmsDate"] != null ? ModelMaster::engDate($l["lmsDate"], 2) : "-" ?></td>
					<td><?= $l["newDate"] != null ? ModelMaster::engDate($l["newDate"], 2) : '-' ?></td>
					<td><?= Employee::employeeName($l["employeeId"]) ?></td>
				</tr>

		<?php
				$i++;
			endforeach;
		}
		?>
	</tbody>
</table>