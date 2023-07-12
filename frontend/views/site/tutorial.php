<?php

use yii\bootstrap4\Alert;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Tutorial');
?>
<div class="container pt-20">
	<?php if (Yii::$app->session->hasFlash('alert')) : ?>
		<?= Alert::widget([
			'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
			'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
		]) ?>
	<?php endif; ?>
	<div class="col-lg-12 main-background">
		<div class="row">
			<div class="col-lg-12 head-bar-main"> <?= Yii::t('app', 'Tutorial') ?></div>
			<div class="col-lg-12 mt-20">
				<div class="row">

					<div class="col-6 bordertest text-center plan-box"><a href="#updateProfile"><?= Yii::t('app', 'How to "Update Profile" ?') ?></a>
					</div>



					<div class="col-6 bordertest text-center plan-box"><a href="#regPlan"><?= Yii::t('app', 'How to "Register plan" ?') ?></a></div>

				</div>
			</div>
			<div class="col-lg-12 mt-20 tutorial-content" id="updateProfile">
				<span class="tutorial-head"><u><?= Yii::t('app', 'Update Profile') ?></u></span>
				<div class="col-12 pl-4 mt-20">
					<h4 class="mb-20 mt">1. <?= Yii::t('app', 'Click Profile on the left site') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/p1.png" class="image-tutorial">
					</div>
					<h4 class="mb-20">2. <?= Yii::t('app', 'Click Update Profile') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/p2.png" class="image-tutorial">
					</div>
					<h4 class="mb-20">3. <?= Yii::t('app', 'Fill in your personal information and upload profile picture') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/p3.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">4. <?= Yii::t('app', 'Update your head quater') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/p4.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">5. <?= Yii::t('app', 'Click +Add more Subcompany ( if any )') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/p5.png" class="image-tutorial2">
					</div>
				</div>

			</div>
			<div class="col-lg-12 mt-20 tutorial-content" id="regPlan">
				<span class="tutorial-head"><u><?= Yii::t('app', 'Register Plan') ?></u></span>
				<div class="col-12 pl-4 mt-20">
					<h4 class="mb-20 mt">1. <?= Yii::t('app', 'Click menu icon on the right top') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r1.png" class="image-tutorial">
					</div>
					<h4 class="mb-20">2. <?= Yii::t('app', 'Select Premium') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r2.png" class="image-tutorial">
					</div>
					<h4 class="mb-20">3. <?= Yii::t('app', 'Choose the plan which you want') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r3.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">4. <?= Yii::t('app', 'Select country which you want to see information') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r4.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">5. <?= Yii::t('app', 'Click Pay') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r5.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">6. <?= Yii::t('app', 'Verify the accuracy of the information') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r6.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">7. <?= Yii::t('app', 'Click CHECK OUT') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r7.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">8. <?= Yii::t('app', 'Choose payment method') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r8.png" class="image-tutorial2">
					</div>
					<h4 class="mb-20">9. <?= Yii::t('app', 'if payment is successful, the system will display this page, you will see the information of the selected country') ?></h4>
					<div class="col-12 text-center pt-20 mb-4">
						<img src="<?= Yii::$app->homeUrl ?>images/tutorial/r9.png" class="image-tutorial2">
					</div>
				</div>
			</div>

		</div>
	</div>
</div>