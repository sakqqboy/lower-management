<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobResponsibility;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Team;
?>
<table>
	<tr>
		<td colspan="10"> </td>
	</tr>
	<tr>
		<th style="font-size:12px;text-align:center;"><b>No.</b></th>
		<th style="font-size:12px;text-align:center;"><b>Client</b></th>
		<th style="font-size:12px;text-align:center;"><b>Job</b></th>
		<th style="font-size:12px;text-align:center;"><b>Job Type</b></th>
		<th style="font-size:12px;text-align:center;"><b>Currrent Step</b></th>
		<th style="font-size:12px;text-align:center;"><b>First Due Date</b></th>
		<th style="font-size:12px;text-align:center;"><b>Update Due Date</b></th>
		<th style="font-size:12px;text-align:center;"><b>Final Due</b></th>
		<th style="font-size:12px;text-align:center;"><b>Team</b></th>
		<th style="font-size:12px;text-align:center;"><b>PIC1</b></th>
		<th style="font-size:12px;text-align:center;"><b>PIC2</b></th>
	</tr>
	<?php
	if (isset($query) && count($query) > 0) {
		$i = 1;
		foreach ($query as $job) : ?>
			<tr>
				<td style="font-size:10px;text-align:center;"><?= $i ?></td>
				<td style="font-size:10px;text-align:left;"><?= Client::clientNameExcel($job["clientId"]) ?></td>
				<td style="font-size:10px;text-align:left;"><?= $job["jobName"] ?></td>
				<td style="font-size:10px;text-align:left;"><?= JobType::jobTypeName($job["jobTypeId"]) ?></td>
				<td style="font-size:10px;text-align:left;"><?= JobStep::CurrentStepExport($job["jobId"], 1) ?></td>
				<td style="font-size:10px;text-align:center;"><?= JobStep::CurrentStepExport($job["jobId"], 2) ?></td>
				<td style="font-size:10px;text-align:center;"><?= JobStep::CurrentStepExport($job["jobId"], 3) ?></td>
				<td style="font-size:10px;text-align:center;"><?= ModelMaster::dateExcel($job["jcTargetDate"]) ?></td>
				<td style="font-size:10px;text-align:left;"><?= Team::teamName($job["teamId"]) ?></td>
				<td style="font-size:10px;text-align:left;">
					<?= Job::jobResponsibility($job["jobId"], JobResponsibility::PIC1) ?>
				</td>
				<td style="font-size:10px;text-align:left;">
					<?= Job::jobResponsibility($job["jobId"], JobResponsibility::PIC2) ?>
				</td>
			</tr>
	<?php
			$i++;
		endforeach;
	}
	?>
</table>