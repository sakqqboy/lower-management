<div class="row">
	<div class="col-3">

		<input type="input" class="form-control search-input" placeholder='Name / email' id="search-name-right" onkeyup="javascript:filterEmployeeRight()">
		<span class="font-size18 search-icon"><i class="fa fa-search" aria-hidden="true"></i></span>

	</div>
	<div class="col-3">
		<select class="form-control" id="branch-search-right-employee" onchange="javascript:filterEmployeeRight()">
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

</div>