<?php

use frontend\models\lower_management\Branch;
use yii\bootstrap4\ActiveForm;

$this->title = 'Job type';
?>
<div class="body-content pt-20 container">
	<div class="row">
		<div class="col-7 mb-20">
			<span class="font-size20 mr-10 font-weight-bold">Job type</span>Ex Bookeeping, Tax filling, Audit, ..
		</div>
		<div class="col-5 mb-20 text-right">
			<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/import-job-type-step" class="btn button-blue mr-10">
				<i class="fa fa-upload mr-1" aria-hidden="true"></i> Uplode Job type file
			</a>
		</div>
	</div>
	<table class="table table-hover">
		<tr class="text-center table-head">
			<td>No.</td>
			<td>branch</td>
			<td>Job type</td>
			<td>Documents List</td>
			<td>Step</td>
			<td>Action</td>
		</tr>
		<?php $form = ActiveForm::begin([
			'id' => 'login-form',
			'action' => Yii::$app->homeUrl . 'setting/job-structure/create-job-type',
			'method' => 'post'
		]); ?>
		<tr>
			<td>
				<div class="text-success mt-10">
					<i class="fa fa-plus" aria-hidden="true"></i>
				</div>
			</td>
			<td>

				<select name="branch" class="form-control" required="required">

					<?php

					if (isset($branch) && count($branch) == 1) {
						foreach ($branch as $b) : ?>
							<option value="<?= $b["branchId"] ?>"><?= $b["branchName"] ?></option>
						<?php
						endforeach;
					} else {
						if (isset($branch) && count($branch) > 0) { ?>
							<option value="">Branch</option>
							<?php
							foreach ($branch as $b) : ?>
								<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
					<?php
							endforeach;
						}
					}
					?>
				</select>
			</td>
			<td class="border-right">
				<input type="text" name="jobTypeName" class="form-control" placeholder="Job Type" required>
			</td>
			<td class="border-right">
				<textarea name="jobTypeDetail" class="form-control" placeholder="Documents List" style="height:40px;"></textarea>
			</td>
			<td class="border-right">

			</td>
			<td class="text-center"><button class="button-blue button-md">Create</button></td>
		</tr>
		<?php ActiveForm::end(); ?>
		<tr class="tr-filter">
			<?= $this->render('filter_job_type_search', [
				"branch" => $branch,
				"jobType" => $jobType,
				"employeeBranch" => $employeeBranch,
				"branchFilter" => $branchFilter,
				"searchJobType" => $searchJobType,
				"jobTypeFilter" => $jobTypeFilter
			]) ?>
		</tr>
		<tbody id="job-type-search">
			<?php
			if (isset($jobType) && count($jobType) > 0) {
				$i = 1;
				foreach ($jobType as $type) : ?>
					<tr id="jobType<?= $type["jobTypeId"] ?>">
						<td><?= $i ?></td>
						<td id="typebranch<?= $type["jobTypeId"] ?>"><?= Branch::branchName($type["branchId"]) ?></td>
						<td id="typeName<?= $type["jobTypeId"] ?>"><?= $type["jobTypeName"] ?></td>

						<td id="typeDetail<?= $type["jobTypeId"] ?>"><?= $type["jobTypeDetail"] ?></td>
						<td id="typeStep<?= $type["jobTypeId"] ?>">
							<?php

							if (isset($jobTypeSteps[$type["jobTypeId"]]) && count($jobTypeSteps[$type["jobTypeId"]]) > 0) {
								//throw new Exception(print_r($jobTypeSteps[$type["jobTypeId"]], true));
								$j = 1;
								foreach ($jobTypeSteps[$type["jobTypeId"]] as $stepId => $stepSelect) :
									echo $j . '. ' . $stepSelect . '<br>';
									$j++;
								endforeach;
							} else { ?>
								<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/job-step" class="no-underline text-success ">
									+ Create step
								</a>
							<?php
							}
							?>
						</td>
						<td class="text-center">
							<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $type["jobTypeId"] ?>)'>
								<i class="fa fa-edit" aria-hidden="true"></i>
							</button>
							<button class="button-red button-xs" onclick='javascript:disableJobType(<?= $type["jobTypeId"] ?>)'>
								<i class="fa fa-times" aria-hidden="true"></i>
							</button>
						</td>
					</tr>
					<tr id="tr-edit<?= $type["jobTypeId"] ?>" style="display:none;">
						<td>
							<div class="mt-10">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</div>
						</td>
						<td>
							<select id="branchInput<?= $type["jobTypeId"] ?>" class="form-control">
								<option value="<?= $type['branchId'] ?>"><?= Branch::branchName($type['branchId']) ?></option>
								<?php
								if (isset($branch) && count($branch) > 0) {
									foreach ($branch as $b) : ?>
										<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
								<?php
									endforeach;
								}
								?>
							</select>
						</td>
						<td>
							<input type="text" id="jobTypeNameInput<?= $type["jobTypeId"] ?>" class="form-control" value="<?= $type["jobTypeName"] ?>">

						</td>
						<td>
							<textarea id="jobTypeDetailInput<?= $type["jobTypeId"] ?>" class="form-control" style="height:100px;"><?= $type["jobTypeDetail"] ?></textarea>

						</td>
						<td>
							<?php

							if (isset($jobTypeSteps[$type["jobTypeId"]]) && count($jobTypeSteps[$type["jobTypeId"]]) > 0) {
								//throw new Exception(print_r($jobTypeSteps[$type["jobTypeId"]], true));
								$j = 1;
								foreach ($jobTypeSteps[$type["jobTypeId"]] as $stepId => $stepSelect) :
									echo $j . '. ' . $stepSelect . '<br>';
									$j++;
								endforeach;
							} else { ?>
								<a href="<?= Yii::$app->homeUrl ?>setting/job-structure/job-step" class="no-underline text-success ">
									+ Create step
								</a>
							<?php
							}
							?>
						</td>
						<td class="text-center">
							<button class="button-green button-xs mt-10" onclick='javascript:updateJobType(<?= $type["jobTypeId"] ?>)'>
								<i class="fa fa-check" aria-hidden="true"></i>
							</button>
							<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $type["jobTypeId"] ?>)'>
								<i class="fa fa-minus" aria-hidden="true"></i>
							</button>
						</td>
					</tr>

				<?php
					$i++;
				endforeach;
			} else { ?>
				<tr>
					<td colspan="6"> Job type not set</td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>