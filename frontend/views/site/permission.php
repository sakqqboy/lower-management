<?php
$this->title = Yii::t('app', 'Access denide');
?>
<div class="container pt-40">
	<div class="col-12 text-center access-denide-image-box">
		<img src="<?= Yii::$app->homeUrl ?>images/icon/padlock.png" class="access-denide-image">
	</div>
	<div class="col-lg-12 text-center mt-20 access-denide-text">
		Access Denide
	</div>
	<div class="col-lg-12 w-100 text-center mt-20">
		You don't have permissions to access this page.<br>
		<?php
		if (isset(Yii::$app->user->id)) {
		?>
			Contact an administrator to get permissions or go to the home page<br>
			and browse the other pages.
		<?php } else { ?>
			Please Log in, if you don't have an account, Contact administrator.
		<?php
		}
		?>
		<br><br>
		<a href="<?= Yii::$app->homeUrl ?>" class="btn button-sky" style="text-decoration-line: none;">Go to home page</a>
	</div>
</div>