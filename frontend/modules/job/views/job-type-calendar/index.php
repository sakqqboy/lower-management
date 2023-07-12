<?php
$this->title = 'Job type Due date';
?>
<div class="body-content pt-20">
	<div class="col-12">
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<select name="branch" class="form-control" id="jobtype-branch">
					<?php
					if ($fag == 1) { ?>
						<option value="">Select branch</option>
						<?php }
					if (isset($branches) && count($branches) > 0) {
						foreach ($branches as $branch) : ?>
							<option value="<?= $branch['branchId'] ?>"><?= $branch["branchName"] ?></option>
					<?php
						endforeach;
					}
					?>
				</select>
			</div>

			<div class="col-lg-3 col-md-4">
				<select name="jobType" class="form-control" id="jobTypeBranch">
					<option value="">Select JobType</option>
					<?php
					if ($fag != 1) {
						if (isset($jobTypes) && count($jobTypes) > 0) {
							foreach ($jobTypes as $jobType) : ?>
								<option value="<?= $jobType['jobTypeId'] ?>"><?= $jobType["jobTypeName"] ?></option>
					<?php
							endforeach;
						}
					}
					?>
				</select>


			</div>
			<div class="col-lg-3 col-md-4">
				<select name="jobType" class="form-control" id="teamBranch">
					<option value="">Select Team</option>
					<?php
					if ($fag != 1) {
						if (isset($teams) && count($teams) > 0) {
							foreach ($teams as $team) : ?>
								<option value="<?= $team['teamId'] ?>"><?= $team["teamName"] ?></option>
					<?php
							endforeach;
						}
					}
					?>
				</select>


			</div>
			<div class="col-1">
				<a href="javascript:filterJobTypeCalendar()" class="btn button-blue">
					<i class="fa fa-search" aria-hidden="true"></i>
				</a>
			</div>
			<div class="col-12 mt-20">
				<div class="row">
					<div class="col-2 mt-10">
						<input type="radio" name="compareTarget" class="radio-md" value="0" checked onclick="javascript:addCompareValue(0)">&nbsp;&nbsp;Not compare
					</div>
					<div class="col-2 mt-10">
						<input type="radio" name="compareTarget" class="radio-md" value="1" onclick="javascript:addCompareValue(1)">&nbsp;&nbsp;Compare 1 Target
					</div>
					<div class="col-2 mt-10">
						<input type="radio" name="compareTarget" class="radio-md" value="2" checked onclick="javascript:addCompareValue(2)">&nbsp;&nbsp;Compare 2 Targets
					</div>
					<input type="hidden" id="compareTarget" value="2">
				</div>
			</div>
		</div>
		<div class="col-12 border mt-20 pt-20 min-vh-100 " id="jobtype-result">
			<h5>
				Select job type and branch
			</h5>
		</div>
	</div>
</div>