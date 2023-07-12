<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Team;

$this->title = 'Job TypeAnalysis';
?>
<div class="body-content pt-20 mb-50">
	<div class="col-12">
		<div class="row">
			<div class="col-lg-3">
				<div class="col-12 p-0">
					<select id="analysis-type" class="form-control text-left font-size16 font-weight-bold">
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/job-type">
							<b>Job Type Analysis </b>
						</option>
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/yearly">
							<b>Yearly Job Analysis </b>
						</option>
						<option value="<?= Yii::$app->homeUrl ?>mms/analysis/index">
							<b>Monthly Job Analysis </b>
						</option>
					</select>
				</div>
			</div>
			<div class="col-lg-9 text-left">
				<div class="row">
					<div class="col-lg-3">
						<select class="form-control" id="jobTypeAnalysis" onchange="javascript:filterJobTypeAnalysis()">
							<option value="<?= $jobTypeId ?>"><?= $chartName ?></option>
							<?php
							if (count($jobType) > 0) {
								foreach ($jobType as $jt) : ?>
									<option value="<?= $jt['jobTypeId'] ?>"><?= $jt['jobTypeName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" id="branchJobTypeyAnalysis" onchange="javascript:filterJobTypeAnalysis()">
							<option value="<?= $employeeBranch["branchId"] ?>"><?= $employeeBranch["branchName"] ?></option>
							<?php
							if (count($branch) > 0) {
								foreach ($branch as $b) : ?>
									<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" id="teamJobTypeAnalysis" onchange="javascript:filterJobTypeAnalysis()">
							<?php
							if (isset($teamId) && $teamId != null) { ?>
								<option value="<?= $teamId ?>"><?= Team::teamName($teamId) ?></option>

							<?php
							}
							?>
							<option value="">Team</option>
							<?php
							if (isset($teams) && count($teams) > 0) {
								foreach ($teams as $team) : ?>
									<option value="<?= $team['teamId'] ?>"><?= $team['teamName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" id="personJobTypAnalysis" onchange="javascript:filterJobTypeAnalysis()">
							<?php
							if (isset($personId) && $personId != null) { ?>
								<option value=""><?= Employee::employeeName($personId) ?></option>
							<?php
							}
							?>
							<option value="">Person</option>
							<?php
							if (isset($person) && count($person) > 0) {
								foreach ($person as $p) : ?>
									<option value="<?= $p['employeeId'] ?>"><?= $p['employeeNickName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-6"></div>
					<div class="col-lg-3 mt-10">
						<select class="form-control" id="yearJobTypAnalysis" onchange="javascript:filterJobTypeAnalysis()">
							<?php
							if (isset($selectYear) && $selectYear != null) { ?>
								<option value="<?= $selectYear ?>"><?= $selectYear ?></option>
							<?php
							}
							?>
							<?php

							if (isset($fiscalYear) && count($fiscalYear) > 0) {
								foreach ($fiscalYear as $year) : ?>
									<option value="<?= $year['fiscalYear'] ?>"><?= $year['fiscalYear'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3 mt-10">
						<select class="form-control" id="monthJobTypAnalysis" onchange="javascript:filterJobTypeAnalysis()">
							<?php
							if (isset($selectMonth) && $selectMonth != null) { ?>
								<option value="<?= $selectMonth ?>"><?= ModelMaster::shotMonthText($selectMonth) ?></option>
							<?php
							}
							?>
							<option value="">Month</option>
							<?php
							if (isset($months) && count($months) > 0) {
								foreach ($months as $monthIndex => $text) : ?>
									<option value="<?= $monthIndex ?>"><?= $text ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>

				</div>
			</div>
			<?php
			if ($completeAll == 1) { ?>
				<div class="col-lg-12 mt-20 font-size20 font-weight-bold">
					<div class="row">
						<div class="col-1 text-right  pr-0"><img src="<?= Yii::$app->homeUrl ?>images/icon/check.png" style="width:50px;height:50px;" class="mt-20 mr-40"></div>
						<div class="col-11 pt-30  pl-0">Every jobs was completed</div>
					</div>




				</div>

			<?php
			}
			?>
			<div class="col-lg-12 <?= $completeAll == 1 ? '' : 'mt-20' ?>">
				<?= $this->render('job_type_chart', [
					"xData" => $xData,
					"values" => $values,
					"chartName" => $chartName,
					"colors" => $colors,
				]) ?>
			</div>
			<div class="col-lg-12 mt-20">
				<?= $this->render('job_type_detail', [
					"complete" => $complete,
					"xData" => $xData,
					"chartName" => $chartName,
					"stepName" => $stepName,
					"jobTypeId" => $jobTypeId,
					"teamId" => $teamId,
					"personId" => $personId,
					"branchId" => $employeeBranch["branchId"],
					"selectYear" => $selectYear,
					"selectMonth" => $selectMonth
				]) ?>
			</div>

		</div>
	</div>
</div>