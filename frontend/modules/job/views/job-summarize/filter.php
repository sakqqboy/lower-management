<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\Team;

?>
<div class="row">
	<div class="col-2">
		<select class="form-control" name="client" onchange="javascript:submitFilter()" id="filter-client">
			<?php
			if (isset($clients) && $clients != null) {
				if (isset($clientId) && $clientId != null) {
			?>
					<option value="<?= $clientId ?>"><?= Client::clientName($clientId) ?></option>
					<option value="">Cleint</option>
				<?php
				} else { ?>
					<option value="">Cleint</option>
				<?php
				}
				?>

			<?php } else {
			?>
				<option value="">Cleint</option>
				<?php
			}
			if (isset($cleints) && count($cleints) > 0) {
				foreach ($cleints as $cleint) : ?>
					<option value="<?= $cleint["clientId"] ?>"><?= $cleint["clientName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
		<input type="hidden" id="filter-branch" value="<?= $branchId ?>">
	</div>
	<div class="col-2">
		<select class="form-control" name="field" onchange="javascript:submitFilter()" id="filter-field">
			<?php
			if (isset($fields) && $fields != null) {
				if (isset($fieldId) && $fieldId != null) {
			?>
					<option value="<?= $fieldId ?>"><?= Field::fieldName($fieldId) ?></option>
					<option value="">Field</option>
				<?php
				} else { ?>
					<option value="">Field</option>
				<?php
				}
				?>

			<?php } else {
			?>
				<option value="">Field</option>
				<?php
			}
			if (isset($fields) && count($fields) > 0) {
				foreach ($fields as $field) : ?>
					<option value="<?= $field["fieldId"] ?>"><?= $field["fieldName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" name="category" onchange="javascript:submitFilter()" id="filter-category">
			<?php
			if (isset($category) && $category != null) {
				if (isset($categoryId) && $categoryId != null) {
			?>
					<option value="<?= $categoryId ?>"><?= Category::categoryName($categoryId) ?></option>
					<option value="">Category</option>
				<?php
				} else { ?>
					<option value="">Category</option>
				<?php
				}
				?>

			<?php } else {
			?>
				<option value="">Category</option>
				<?php
			}
			if (isset($category) && count($category) > 0) {
				foreach ($category as $c) : ?>
					<option value="<?= $c["categoryId"] ?>"><?= $c["categoryName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" name="team" onchange="javascript:submitFilter()" id="filter-team">
			<?php
			if (isset($teams) && $teams != null) {
				if (isset($teamId) && $teamId != null) {
			?>
					<option value="<?= $teamId ?>"><?= Team::teamName($teamId) ?></option>
					<option value="">Team</option>
				<?php
				} else { ?>
					<option value="">Team</option>
				<?php
				}
				?>

			<?php } else {
			?>
				<option value="">Team</option>
				<?php
			}
			if (isset($teams) && count($teams) > 0) {
				foreach ($teams as $team) : ?>
					<option value="<?= $team["teamId"] ?>"><?= $team["teamName"] ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" name="year" onchange="javascript:submitFilter()" id="filter-fiscalYear">
			<option value="<?= $currentYear ?>"><?= $currentYear ?></option>
			<?php
			$startYear = 2020;
			$i = 0;
			while ($i < 10) { ?>
				<option value="<?= $startYear ?>"><?= $startYear ?></option>
			<?php
				$startYear++;
				$i++;
			}
			?>
		</select>
	</div>
	<div class="col-2">
		<select class="form-control" name="month" onchange="javascript:submitFilter()" id="filter-month">
			<?php
			if (isset($currentMonth) && count($currentMonth) > 0) { ?>
				<option value="<?= $currentMonth['value'] ?>"><?= $currentMonth["name"] ?></option>
			<?php } ?>
			<option value="">Month</option>
			<?php
			$month = ModelMaster::month();
			if (isset($month) && count($month) > 0) {
				foreach ($month as $index => $m) : ?>
					<option value="<?= $index ?>"><?= $m ?></option>
			<?php
				endforeach;
			}
			?>
		</select>
	</div>
</div>