<div class="row">
	<div class="col-12  mt-20 font-size18">
		<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Create job step
	</div>
	<div class="col-lg-6 col-6  mt-20">
		<label class="label-input">Branch</label>
		<?php
		if (isset($branch) && count($branch) > 0) { ?>
			<select name="branch" class="form-control" id="branchSearchType">
				<option value="">Branch</option>
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
	</div>
	<div class="col-lg-6 col-6  mt-20">
		<label class="label-input">Job type</label>
		<select name="jobType" class="form-control" required="required" id="jobType" disabled>
			<option value="">Job type</option>
		</select>
	</div>

	<div class="col-lg-6 col-6  mt-20 mb-10">
		<div class="row" id="add-more-step">
			<div class="col-10">
				<label class="label-input">Step</label>
				<input type="text" name="stepName" id="stepName" class="form-control" placeholder="Step Name" required disabled>
			</div>
			<div class="col-2">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/add.png" class="add-image displayNone" id="add-step">
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-6  mt-20 mb-10">
		<div id="add-more-sort">
			<label class="label-input">Sort</label>
			<input type="text" id="sort" name="sort" class="form-control" placeholder="Sort" required disabled onKeyUp="if(isNaN(this.value)){this.value='';}">
		</div>
	</div>
	<div class="col-lg-2 col-6 text-right mt-20 mb-10">
		<button class="button-blue button-md mt-30" id="create-step">Create</button>
	</div>
</div>