<?php

use common\models\ModelMaster;
use yii\bootstrap4\ActiveField;
use yii\bootstrap4\ActiveForm;
?>
<?php $form = ActiveForm::begin([
	'id' => 'register-form',
	'action' => Yii::$app->homeUrl . 'site/signup',
	'method' => 'post'
]); ?>
<div id="#registerModal" class="modal">
	<div class="modal-content-register">
		<div class="close-register pull-right"><i class="far fa-times"></i></div>
		<div class="col-lg-12 container" style="margin-top: -20px;">
			<div class="row">
				<div class="col-lg-12 text-center head-signup"><?= Yii::t('app', 'Sign up') ?></div>
				<div class="col-lg-12" style="margin-top: 20px;">
					<input type="text" name="email" id="memberEmail" class="form-control" placeholder="<?= Yii::t('app', 'Email address to register') ?>   * * <?= Yii::t('app', 'Require') ?>" required>
					<span class="col-lg-12 text-danger" id="duplicate-email" style="margin-left:-10px;display:none;position:absolute;">
						* * This email is already used, please try again.
					</span>
					<span class="col-lg-12 text-danger" id="incorrect-email" style="margin-left:-10px;display:none;position:absolute;">
						* * You have entered an invalid email address!.
					</span>
				</div>
				<div class="col-lg-12" style="margin-top: 25px;">
					<input type="text" name="firstname" class="form-control" id="firstname" placeholder="<?= Yii::t('app', 'Firstname') ?>  * * <?= Yii::t('app', 'Require') ?>">
					<span class="col-lg-12 text-danger" id="empty-name" style="margin-left:-10px;display:none;position:absolute;">
						* * Firstname Required !.
					</span>
				</div>
				<div class="col-lg-12" style="margin-top: 25px;">
					<input type="text" name="lastname" class="form-control" id="lastname" placeholder="<?= Yii::t('app', 'Surename') ?>   * * <?= Yii::t('app', 'Require') ?>">
					<span class="col-lg-12 text-danger" id="empty-lastname" style="margin-left:-10px;display:none;position:absolute;">
						* * Surename Required !.
					</span>

				</div>
				<div class="col-lg-12" style="margin-top: 25px;">
					<input type="text" name="company" class="form-control" id="company" placeholder="<?= Yii::t('app', 'Company name') ?>">
				</div>
				<div class="col-lg-12" style="margin-top: 25px;">
					<select name="country" class="form-control" id="country">
						<option value="0"><?= Yii::t('app', 'Choose Country') ?></option>
						<?php
						$countries = ModelMaster::country();
						if (isset($countries) && count($countries) > 0) {
							foreach ($countries as $countryId => $country) :
						?>
								<option value="<?= $countryId ?>" class="custom-options"><?= Yii::t('app', $country["name"]) ?></option>
							<?php
							endforeach;
						} else { ?>
							<option><?= Yii::t('app', 'No Data') ?></option>
						<?php }
						?>
					</select>
					<span class="col-lg-12 text-danger" id="empty-country" style="margin-left:-10px;display:none;position:absolute;">
						* * Country Required ! ! !
					</span>
				</div>
				<div class="col-lg-12 mt-25">
					<input type="password" name="password" class="form-control" id="password" placeholder="<?= Yii::t('app', 'Password') ?> (<?= Yii::t('app', 'more than 6 characters') ?>)  * * <?= Yii::t('app', 'Require') ?>" required>
					<span class="col-lg-12 text-danger" id="pass-less" style="margin-left:-10px;display:none;position:absolute;">
						* * password must be more than 6 characters.
					</span>
				</div>
				<div class="col-lg-12 mt-25">
					<input type="password" name="confpass" class="form-control" id="confpass" placeholder="<?= Yii::t('app', 'Confirm password') ?>  * * <?= Yii::t('app', 'Require') ?>" required>
					<span class="col-lg-12 text-danger" id="incorrect-confpass" style="margin-left:-10px;display:none;position:absolute;">
						* * Incorrect confirm password.
					</span>
				</div>


				<div class="col-lg-12 text-center mt-20" style="font-size:16px;">
					<?= Yii::t('app', 'If you have an account') ?>, <a href="#" id="loginText"> <?= Yii::t('app', 'Log in') ?> </a>
				</div>
				<!-- <div class="col-lg-12">
					<div class="row" style="margin-top: 20px;">
						<div class="col-lg-4">
							<hr>
						</div>
						<div class="col-lg-4 text-center" style="color:gray">Or</div>
						<div class="col-lg-4">
							<hr>
						</div>
					</div>
				</div> -->

			</div>
			<!-- <div class="col-lg-12 facebook-button text-center">
				<i class="fab fa-facebook-f" style="margin-right: 10px;"></i> Facebook
			</div>
			<div class="col-lg-12 google-button text-center">
				<i class="fab fa-google-plus-g" style="margin-right: 10px;"></i> Google-Plus
			</div> -->
			<div class="col-lg-12 text-left text-info mt-20" style="font-size:14px;">
				* * <?= Yii::t('app', 'Accept term & condition to register') ?>.
			</div>
			<div class="col-lg-12 agreement text-left container pt-10">
				<div class="col-lg-12">
					&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;WebsitePolicies made my life so much easier. Thanks to their smart solution,
					I was able to launch my new online service page within a few hours.
					It saved me hours and hours of research and work. All policies are professional and correct.
				</div>
				<div class="col-lg-12 pt-10">
					<input type="checkbox" style="margin-right: 15px;" id="agree-term">I have read and agree to the terms and conditions.
					and privacy policy of Wiki Invesment.
				</div>
			</div>
			<div class="col-lg-12 mt-25" style="display:none;" id="enable-register">
				<a herf="#" class="btn btn-primary" style="width:100%;font-weight:bold;" id="signup-btn" style="pointer-events: none !important;"><?= Yii::t('app', 'Sign up') ?></a>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>