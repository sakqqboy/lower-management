<div class="col-12">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-12 mb-10">
			<div class="row">
				<div class="col-2 text-right">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/previous.png" class="carlendar-button" id="previous-year-kpi">
				</div>
				<div class="col-8 text-center current-date" id="current-year"><?= $yearKpi ?></div>
				<div class="col-2 text-left">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/next.png" class="carlendar-button" id="next-year-kpi">
				</div>
			</div>

		</div>
		<div class="col-lg-6 col-md-6 col-12 mb-10 font-size20">
			<div class="row">
				<div class="col-2 text-right">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/previous.png" class="carlendar-button" id="previous-month-kpi">
				</div>
				<div class="col-8 text-center current-date" id="current-date"><?= $selectDate
													?></div>
				<div class="col-2 text-left">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/next.png" class="carlendar-button" id="next-month-kpi">
				</div>
			</div>
		</div>

	</div>
</div>