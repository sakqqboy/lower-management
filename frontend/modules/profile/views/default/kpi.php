<div class="col-12 font-size24">K P I Lists</div>
<div class="col-12 mt-10 border mb-20" style="border-radius:10px;padding-top:10px;">
	<div class="row border-bottom pb-2">
		<div class="col-6 text-center font-size16 font-weight-bold">Kpi</div>
		<div class="col-2 text-center font-size16 font-weight-bold">Unit</div>
		<div class="col-2 text-center font-size16 font-weight-bold">Target Amount</div>
		<div class="col-2 text-center font-size16 font-weight-bold">Achieved</div>
	</div>
	<?php

	use common\models\ModelMaster;

	if (isset($kpi) && count($kpi) > 0) {
		$i = 1;
		foreach ($kpi as $pkpiId => $data) : ?>
			<div class="row border-bottom pb-2">
				<div class="col-6 text-left font-size16 pt-2">
					<b><?= $i ?>.&nbsp;
						<a href="<?= Yii::$app->homeUrl ?>kpi/update/personal-update/<?= ModelMaster::encodeParams(["pkpiId" => $pkpiId]) ?>" class="no-underline-black">
							<?= $data["kpiName"] ?>
						</a>
					</b>
					<div class="col-12 font-size14 mt-10"><?= $data["detail"] ?></div>
				</div>
				<div class="col-2 text-center font-size14 pt-30"><?= $data["unit"] ?></div>
				<div class="col-2 text-right font-size14 pt-30"><?= $data["target"] ?></div>
				<div class="col-2 text-right font-size14 pt-30"><?= $data["achieved"] ?></div>
			</div>

	<?php
			$i++;
		endforeach;
	}
	?>
</div>