<div class="col-12">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-12 mb-10">
			<div class="row">
				<div class="col-2 text-right">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/previous.png" class="carlendar-button" id="previous-year-sales">
				</div>
				<div class="col-8 text-center current-date" id="current-year"><?= $year ?></div>
				<div class="col-2 text-left">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/next.png" class="carlendar-button" id="next-year-sales">
				</div>
			</div>

		</div>
		<div class="col-lg-6 col-md-6 col-12 mb-10 font-size20">
			<div class="row">
				<div class="col-2 text-right">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/previous.png" class="carlendar-button" id="previous-month-sales">
				</div>
				<div class="col-8 text-center current-date" id="current-date"><?= $selectDate
													?></div>
				<div class="col-2 text-left">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/next.png" class="carlendar-button" id="next-month-sales">
				</div>
			</div>
		</div>

	</div>
</div>
<div class="col-12">
	<div class="row">
		<div class="col-lg-10 col-md-9 col-sm-6 col-6 mb-10 font-size20 ">
			<div class="row mt-10">
				<div class="col-lg-4 col-md-6 col-12 text-right">
					<select class="form-control" id="branch-sale">
						<option value="">Branch</option>
						<?php
						if (isset($branch) && !empty($branch)) {
							foreach ($branch as $b) : ?>
								<option><?= $b["branchName"] ?></option>
						<?php

							endforeach;
						}
						?>
					</select>
				</div>
			</div>

		</div>
		<div class="col-lg-2 col-md-3 col-sm-6 col-6 mb-10 font-size20">
			<select class="form-control mt-10" id="timezone">
				<option value="">Time zone</option>
			</select>
		</div>

	</div>
</div>
<div class="col-12">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-6 text-left">
			<input type="checkbox" onchange="javascript:carlendarFilter()" class="checkbox-sm check-box-red mt-10" id="sales-activity" <?= isset($salesActivity) && ($salesActivity == 1) ? "checked" : "" ?>> <span class="badge badge-custom badge-red">Sales activity</span>
		</div>
		<div class="col-lg-2 col-md-2 col-6  text-left">
			<input type="checkbox" onchange="javascript:carlendarFilter()" class="checkbox-sm check-box-blue mt-10" id="existing-meeting" <?= isset($existingMeeting) && ($existingMeeting == 1) ? "checked" : "" ?>> <span class="badge badge-custom badge-blue">Existing meeting</span>
		</div>
		<div class="col-lg-2 col-md-2 col-6 text-left">
			<input type="checkbox" onchange="javascript:carlendarFilter()" class="checkbox-sm check-box-green mt-10" id="internal-meeting" <?= isset($internalMeeting) && ($internalMeeting == 1) ? "checked" : "" ?>> <span class="badge badge-custom badge-green">Internal meeting</span>
		</div>
		<div class="col-lg-2 col-md-2 col-6 text-left">
			<input type="checkbox" onchange="javascript:carlendarFilter()" class="checkbox-sm check-box-yellow mt-10" id="other" <?= isset($other) && ($other == 1) ? "checked" : "" ?>> <span class="badge badge-custom badge-yellow">Other</span>
		</div>

	</div>
</div>
<input type="hidden" value="<?= isset($year) ? $year : (int)date('Y') ?>" id="year">
<input type="hidden" value="<?= isset($month) ? (int)$month : (int)date('m') ?>" id="month">