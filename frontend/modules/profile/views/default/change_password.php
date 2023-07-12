<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;

$this->title = 'Profile'
?>
<div class="body-content pt-20 mb-50">
	<div class="col-12">
		<div class="row">
			<div class="col-lg-10">
				<div class="offset-lg-3 col-lg-6 change-password-box mt-40">
					<div class="row">
						<div class="col-lg-4"></div>
						<div class="col-lg-8 text-center font-size26 mt-10">
							Change Password
						</div>
					</div>
					<div class="col-12 text-center mt-20">
						<div class="row">
							<div class="col-lg-4 col-md-5 key-box">
								<img src="<?= Yii::$app->homeUrl ?>images/icon/padlock.png" class="img-change-password">
							</div>
							<div class="col-lg-8 col-md-7 col-12 change-password-box2">
								<div class="col-12  mt-20">
									<input type="password" class="form-control" placeholder="Old password" id="old-password">
								</div>
								<div class="col-12  mt-20">
									<input type="password" class="form-control" placeholder="New password" id="new-password">
								</div>
								<div class="col-12  mt-20">
									<input type="password" class="form-control" placeholder="Confirm new password" id="confirm-password">
								</div>
								<div class="col-12 text-right mt-20">
									<a class="btn button-blue" href="javascript:changePassword()">Comfirm</a>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="col-lg-2 profile-site profile-change-password">
				<?= $this->render('user_info', ["employee" => $employee]) ?>
			</div>
		</div>
	</div>
</div>