<?

use frontend\models\lower_management\Team;

$this->title = 'Award';
?>
<div class="body-content container">
	<div class="row" style="margin-top: -20px;">
		<div class="col-1"></div>
		<div class="col-10">
			<div class="row pr-0">
				<?php
				if (isset($teamReward) && count($teamReward) > 0) {
					$i = 0;
					foreach ($teamReward as $teamId => $detail) :
						if ($i < 2) {
				?>
							<div class="col-5 box-award pr-0">
								<div class="row">
									<div class="col-8 text-left">
										<div class="col-12 font-size26 font-weight-bold pt-20 text-left">
											<?= Team::teamName($teamId) ?>
										</div>
										<div class="col-12 font-size18 font-weight-bold mt-20">
											<i class="fa fa-star text-warning mr-40" aria-hidden="true"></i>
											<span class="mr-30"><?= $detail["star"] ?></span>Stars.
										</div>
										<div class="col-12 font-size18 font-weight-bold mt-20">
											<i class="fa fa-check text-success mr-40" aria-hidden="true"></i>
											<span class="mr-30"><?= $detail["checkList"] ?></span>Checklists.
										</div>
										<div class="col-12 font-size18 font-weight-bold mt-20">
											<i class="fa fa-circle text-success mr-40" aria-hidden="true"></i>
											<span class="mr-30"><?= $detail["manual"] ?></span>Manuals.
										</div>
										<div class="col-12 font-size18 font-weight-bold mt-20">
											<i class="fa fa-times text-danger mr-40" aria-hidden="true"></i>
											<span class="mr-30"><?= $detail["none"] ?></span>None.
										</div>
										<div class="col-12 font-size20 font-weight-bold mt-20">
											Total<span class="ml-30 mr-30"><?= $detail["star"] + $detail["checkList"] + $detail["manual"] + $detail["none"] ?></span>Jobs.
										</div>


									</div>
									<div class="col-4 pr-0 text-right" style="padding-right: -100px;">
										<?php
										if ($i == 0) {
										?>
											<img src="<?= Yii::$app->homeUrl ?>images/icon/gold.png" class="medal-reward">
										<?php
										} else { ?>
											<img src="<?= Yii::$app->homeUrl ?>images/icon/silver.png" class="medal-silver-reward">
										<?php
										}
										?>
									</div>
								</div>

							</div>
							<?php if ($i == 0) {
							?>
								<div class="col-1"></div>
							<?php
							}
							?>
				<?php
						}
						$i++;
					endforeach;
				}
				?>

			</div>
		</div>
		<div class="col-1"></div>

	</div>
	<div class="col-12 mt-30">
		<div class="row">
			<?php
			if (isset($teamReward) && count($teamReward) > 0) {
				$i = 0;
				foreach ($teamReward as $teamId => $detail) :
					if ($i >= 2) {
			?>
						<div class="col-3 mb-40">
							<div class="col-12 border pb-20" style="border-radius: 5px;">
								<div class="col-12 font-size20 font-weight-bold pt-10">
									<?= Team::teamName($teamId) ?>
								</div>
								<div class="col-12 font-size16 font-weight-bold mt-20">
									<i class="fa fa-star text-warning mr-40" aria-hidden="true"></i>
									<span class="mr-30"><?= $detail["star"] ?></span>Stars.
								</div>
								<div class="col-12 font-size16 font-weight-bold mt-20">
									<i class="fa fa-check text-success mr-40" aria-hidden="true"></i>
									<span class="mr-30"><?= $detail["checkList"] ?></span>Checklists.
								</div>
								<div class="col-12 font-size16 font-weight-bold mt-20">
									<i class="fa fa-circle text-success mr-40" aria-hidden="true"></i>
									<span class="mr-30"><?= $detail["manual"] ?></span>Manuals.
								</div>
								<div class="col-12 font-size16 font-weight-bold mt-20">
									<i class="fa fa-times text-danger mr-40" aria-hidden="true"></i>
									<span class="mr-30"><?= $detail["none"] ?></span>None.
								</div>
								<div class="col-12 font-size16 font-weight-bold mt-20">
									Total<span class="ml-30 mr-30"><?= $detail["star"] + $detail["checkList"] + $detail["manual"] + $detail["none"] ?></span>Jobs.
								</div>
							</div>
						</div>
						<?php if ($i == 0) {
						?>
							<div class="col-1"></div>
						<?php
						}
						?>
			<?php
					}
					$i++;
				endforeach;
			}
			?>

		</div>
	</div>
</div>