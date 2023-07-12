<?php

use common\models\ModelMaster;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Step;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;

$this->title = 'Lower management';
?>
<div class="body-content pt-20 mb-50">

	<div class="col-12  pt-20 filter-row">
		<?= $this->render('filter', [
			"branch" => $branch,
			"isManager" => $isManager,
			"currentMonth" => $currentMonth,
			"currentYear" => $currentYear,
			"fields" => $fields,
			"filedId" => $fieldId,
			"branchId" => $branchId

		])
		?>
	</div>
</div>