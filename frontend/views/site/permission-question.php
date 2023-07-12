<?php
$this->title = "Access denide";
?>
<div class="container">
	<div class="col-lg-12 pt-20 main-background" style="padding-top:10%;padding-bottom:14%;">
		<div class="col-lg-12 text-center " style="font-weight:bolder;">

			<img src="<?= Yii::$app->homeUrl ?>images/icon/padlock.png" style="width:100px;height:100px;">
		</div>
		<div class="col-lg-12 text-center mt-20 access-denide-text">
			<?= Yii::t('app', "Access Denide") ?>
		</div>
		<div class="col-lg-12 w-100 text-center mt-20">
			<?= Yii::t('app', "Your promotion isn't allowed for viewing Q & A of this country") ?>.<br><br>
			<a href="<?= Yii::$app->homeUrl ?>member/promotion" style="text-decoration-line: none;"><b><?= Yii::t('app', "Select Promotion") ?></b></a>
			<?= Yii::t('app', 'to see Q & A') ?> .<br>
			<br><br>
			<a href="<?= Yii::$app->homeUrl ?>" class="btn btn-success" style="text-decoration-line: none;"><?= Yii::t('app', 'Go to home') ?></a>
		</div>
	</div>
</div>