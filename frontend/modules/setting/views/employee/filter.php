<div class="row">
	<div class="col-2">

		<input type="input" class="form-control search-input" placeholder='Name / email' id="searchName" onkeyup="javascript:filterEmployee()">
		<span class="font-size18 search-icon"><i class="fa fa-search" aria-hidden="true"></i></span>

	</div>
	<div class="col-2">
		<select class="form-control" id="branch-search-employee">
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
		<select class="form-control" id="section-search-employee" onchange="javascript:filterEmployee()">
			<option value="">Section</option>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="position-search-employee" onchange="javascript:filterEmployee()">
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
		<select class="form-control" id="team-employee" onchange="javascript:filterEmployee()">
			<option value="">Team</option>
		</select>
	</div>

	<div class="col-2">
		<select class="form-control" id="user-type-search-employee" onchange="javascript:filterEmployee()">
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