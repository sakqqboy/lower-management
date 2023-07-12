<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kpi;

$this->title = '';
?>
<div class="col-12 mt-40">
	<div class="row">
		<div class="col-12 pt-40  mt-40">
			<div class="col-12 text-center font-size24 font-weight-bold">
				KPI Progress
			</div>
		</div>
		<div class="col-lg-2 col-md-3 col-3 mt-20">
			<select id="year-kpi" class="form-control">
				<option value="<?= $year ?>"><?= $year ?></option>
				<?php
				$i = 1;
				while ($i < 10) {
					$y = $currentYear - $i; ?>
					<option><?= $y ?></option>
				<?php
					$i++;
				}
				?>
			</select>
		</div>
		<div class="col-3 mt-20">
			<select id="branch-kpi" class="form-control" onchange="javascript:branchKpi2()">

				<option value="">Branch</option>

				<?php
				if (isset($branch) && count($branch) > 0)
					$i = 1;
				foreach ($branch as $b) : ?>
					<option value="<?= $b["branchId"] ?>"><?= $b["branchName"] ?></option>
				<?php
				endforeach;
				?>
			</select>
		</div>
		<div class="col-3 mt-20">
			<select id="team-kpi" class="form-control">
				<option value="">Team</option>
			</select>
		</div>
	</div>
	<div class="row mt-20 ">
		<?= $this->render('month_calendar', ["year" => $year]) ?>
	</div>
	<input type="hidden" id="kpi-id" value="<?= $kpiId ?>">
</div>