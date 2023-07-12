<div class="row">
	<div class="col-2">
		<select class="form-control" id="branch-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<option value="">Branch</option>
			<?php
			if (isset($branch) && count($branch) > 0) {
				foreach ($branch as $b) : ?>
					<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="section-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<option value="">Section</option>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="position-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<option value="">Position</option>
			<?php
			if (isset($category) && count($category) > 0) {
				foreach ($category as $c) : ?>
					<option value="<?= $c['categoryId'] ?>"><?= $c['categoryName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="team-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<option value="">Team</option>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="team-position-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<option value="">Team Position</option>
			<?php
			if (isset($teamPosition) && count($teamPosition) > 0) {
				foreach ($teamPosition as $position) : ?>
					<option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>

	<div class="col-2">
		<select class="form-control" id="user-type-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<option value="">User Type</option>
			<?php
			if (isset($userType) && count($userType) > 0) {
				foreach ($userType as $c) : ?>
					<option value="<?= $c['typeId'] ?>"><?= $c['typeName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
</div>