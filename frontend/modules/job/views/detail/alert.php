<?php

use yii\helpers\ArrayHelper;

if (Yii::$app->session->hasFlash('create')) {
	$message = ArrayHelper::getValue(Yii::$app->session->getFlash('create'), 'body');
?>
	<div class="alert-noti alert-green">
		<div class="row">
			<div class="col-10 text-left"><?= $message ?></div>
			<div class="col-2 text-right">
				<i class="fa fa-times close-noti" id="close-noti" aria-hidden="true"></i>
			</div>
		</div>
	</div>
<?php
}
if (Yii::$app->session->hasFlash('update')) {
	$message = ArrayHelper::getValue(Yii::$app->session->getFlash('update'), 'body')
?>
	<div class="alert-noti alert-yellow">
		<div class="row">
			<div class="col-10 text-left"><?= $message ?></div>
			<div class="col-2 text-right">
				<i class="fa fa-times close-noti" id="close-noti" aria-hidden="true"></i>
			</div>
		</div>
	</div>
<?php
}
?>