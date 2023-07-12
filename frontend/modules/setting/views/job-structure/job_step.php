<?php

use frontend\models\lower_management\Branch;

$this->title = 'Job Step';
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 mb-20">
			<span class="font-size20 mr-10 font-weight-bold">Job type step</span>
		</div>
	</div>

	<div class="col-12 mb-30 create-box">
		<?= $this->render('create_job_type', [
			"branch" => $branch
		]) ?>

	</div>
	<table class="table table-hover table-responsive-sm ">
		<tr class="text-center table-head">
			<td>No.</td>
			<td>Branch</td>
			<td>Jop type</td>
			<td>Step</td>
			<td>Sort</td>
			<td>Action</td>
		</tr>
		<tr>
			<?= $this->render('filter', [
				"branch" => $branch,
				"jobType" => $jobType,
				"firstJobType" => $firstJobType,
				"employeeBranch" => $employeeBranch
			]) ?>
		</tr>
		<tbody id="step-search">
			<?php
			if (isset($steps) && count($steps) > 0) {
				$i = 1;
				foreach ($steps as $step) : ?>
					<tr id="step<?= $step["stepId"] ?>">
						<td><?= $i ?></td>
						<td id="branch<?= $step["stepId"] ?>"><?= Branch::branchNameFromJob($step["jobTypeId"]) ?></td>
						<td id="jobType<?= $step["stepId"] ?>">
							<?= $step["jobTypeName"] ?>
						</td>
						<td id="step<?= $step["stepId"] ?>"><?= $step["stepName"] ?></td>
						<td id="sort<?= $step["stepId"] ?>"><?= $step["sort"] ?></td>
						<td class="text-center">
							<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $step["stepId"] ?>)'>
								<i class="fa fa-edit" aria-hidden="true"></i>
							</button>
							<button class="button-red button-xs" onclick='javascript:disableStep(<?= $step["stepId"] ?>)'>
								<i class="fa fa-times" aria-hidden="true"></i>
							</button>
						</td>
					</tr>
					<tr id="tr-edit<?= $step["stepId"] ?>" class="tr-edit" style="display:none;">
						<td>
							<div class="mt-10">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</div>
						</td>
						<td>
							<?php
							if (isset($branch) && count($branch) > 0) { ?>
								<select class="form-control" id="branchSearchType<?= $step["stepId"] ?>" onchange="javascript:searchEachType(<?= $step['stepId'] ?>)">
									<option value="<?= Branch::branchIdFromJob($step["jobTypeId"]) ?>"><?= Branch::branchNameFromJob($step["jobTypeId"]) ?></option>
									<?php
									foreach ($branch as $b) : ?>
										<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
									<?php
									endforeach;

									?>
								</select>
							<?php
							} else {
								$disable = 'disabled';
							?>
								<div class="no-underline font-size16 mt-10"><a href="<?= Yii::$app->homeUrl ?>setting/structure/branch"> Create Branch</a></div>
							<?php }

							?>
						</td>
						<td>
							<select class="form-control" id="jobTypeInput<?= $step["stepId"] ?>">
								<option value="<?= $step["jobTypeId"] ?>"><?= $step["jobTypeName"] ?></option>
							</select>
						</td>
						<td>
							<input type="text" id="stepNameInput<?= $step["stepId"] ?>" class="form-control" value="<?= $step["stepName"] ?>">
						</td>
						<td>
							<input type="text" id="sortInput<?= $step["stepId"] ?>" class="form-control" value="<?= $step["sort"] ?>">
						</td>
						<td class="text-center">
							<button class="button-green button-xs mt-10" onclick='javascript:updateJobStep(<?= $step["stepId"] ?>)'>
								<i class="fa fa-check" aria-hidden="true"></i>
							</button>
							<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $step["stepId"] ?>)'>
								<i class="fa fa-minus" aria-hidden="true"></i>
							</button>
						</td>
					</tr>

				<?php
					$i++;
				endforeach;
			} else { ?>
				<tr>
					<td colspan="6" class="text-center"> Setp not set</td>
				</tr>
			<?php
			}
			?>
		</tbody>

	</table>
</div>