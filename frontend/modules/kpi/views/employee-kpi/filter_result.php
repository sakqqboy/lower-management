<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use frontend\models\lower_management\Type;

?>
<div class="row">
	<div class="col-2">
		<select class="form-control" id="branch-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<?php
			if (isset($branchId) && $branchId != '') { ?>
				<option value="<?= $branchId ?>"><?= Branch::branchName($branchId) ?></option>
				<option value="">Branch</option>
			<?php
			} else { ?>
				<option value="">Branch</option>
			<?php
			}
			?>

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
			<?php
			if (isset($sectionId) && $sectionId != '') { ?>
				<option value="<?= $sectionId ?>"><?= Section::sectionName($sectionId) ?></option>
				<option value="">Section</option>
			<?php
			} else { ?>
				<option value="">Section</option>
			<?php
			}
			?>

			<?php
			if (isset($section) && count($section) > 0) {
				foreach ($section as $s) : ?>
					<option value="<?= $s['sectionId'] ?>"><?= $s['sectionName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="position-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<?php
			if (isset($positionId) && $positionId != '') { ?>
				<option value="<?= $positionId ?>"><?= Position::positionName($positionId) ?></option>
				<option value="">Position</option>
			<?php
			} else { ?>
				<option value="">Position</option>
			<?php
			}
			?>

			<?php
			if (isset($position) && count($position) > 0) {
				foreach ($position as $p) : ?>
					<option value="<?= $p['positionId'] ?>"><?= $p['positionName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="team-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<?php
			if (isset($teamId) && $teamId != '') { ?>
				<option value="<?= $teamId ?>"><?= Team::teamName($teamId) ?></option>
				<option value="">Team</option>
			<?php
			} else { ?>
				<option value="">Team</option>
			<?php
			}
			?>

			<?php
			if (isset($team) && count($team) > 0) {
				foreach ($team as $te) : ?>
					<option value="<?= $te['teamId'] ?>"><?= $te['teamName'] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" id="team-position-search-employee-kpi" onchange="javascript:filterEmployeeKpi()">
			<?php
			if (isset($teamPositionId) && $teamPositionId != '') { ?>
				<option value="<?= $teamPositionId ?>"><?= TeamPosition::positionName($teamPositionId) ?></option>
				<option value="">Team Position</option>
			<?php
			} else { ?>
				<option value="">Team Position</option>
			<?php
			}
			?>
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
			<?php
			if (isset($userTypeId) && $userTypeId != '') { ?>
				<option value="<?= $userType ?>"><?= Type::TypeName($userTypeId) ?></option>
				<option value="">User Type</option>

			<?php
			} else { ?>
				<option value="">User Type</option>
			<?php
			}
			?>
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