<div class="header-top col-12">
	<div class="row">

		<div class="col-1 menu-box">
			<a href="<?= Yii::$app->homeUrl ?>job/default/create">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/plus.png" class="header-icon mt-10">
				<div class="row">
					<div class="col-12 mt-10 header-menu">
						New job
					</div>
				</div>
			</a>
		</div>
		<div class="col-1 menu-box">
			<a href="<?= Yii::$app->homeUrl ?>job/detail/index">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/open-folder.png" class="header-icon mt-10">
				<div class="col-12 mt-10 header-menu text-center">
					Jobs
				</div>
			</a>
		</div>
		<?php

		use frontend\models\lower_management\Chat;

		if (isset(Yii::$app->user->id)) {
		?>
			<div class="col-1 menu-box">
				<a href="<?= Yii::$app->homeUrl ?>job/job-summarize/index">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/ready.png" class="header-icon mt-10">
					<div class="col-12 mt-10 header-menu text-center">
						Summary sheet
					</div>
				</a>
			</div>
		<?php
		}
		?>
		<div class="col-1 menu-box">
			<a href="<?= Yii::$app->homeUrl ?>client/default/index">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/team.png" class="header-icon mt-10">
				<div class="row">
					<div class="col-12 mt-10 header-menu">
						Clients
					</div>
				</div>
			</a>
		</div>
		<div class="col-2 menu-box">
			<a href="#">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/table.png" class="header-icon mt-10">
				<div class="row">
					<div class="col-12 mt-10 header-menu">
						Credit Control
					</div>
				</div>
			</a>
		</div>
		<div class="col-1 menu-box">
			<a href="<?= Yii::$app->homeUrl ?>job/job-type-calendar">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/calendar.png" class="header-icon mt-10">
				<div class="col-12 mt-10 header-menu text-center">
					JobType
				</div>
			</a>
		</div>

		<div class="col-1 menu-box">
			<a href="<?= Yii::$app->homeUrl ?>mms/analysis/index">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/manager.png" class="header-icon mt-10">
				<div class="row">
					<div class="col-12 mt-10 header-menu">
						MMS
					</div>
				</div>
			</a>
		</div>
		<div class="col-1 menu-box">
			<a href="<?= Yii::$app->homeUrl ?>profile/default/index">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/profile.png" class="header-icon mt-10">
				<div class="row">
					<div class="col-12 mt-10 header-menu">
						Profile
					</div>
				</div>
			</a>
		</div>
		<div class="col-1 menu-box">
			<!-- <a href="<?php // Yii::$app->homeUrl 
					?>sales/default/index"> -->
			<a href="#">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/sales.png" class="header-icon mt-10">
				<div class="col-12 mt-10 header-menu text-center">
					Sales
				</div>
			</a>
		</div>
		<?php
		$unread = Chat::unreadMessage();
		?>
		<div class="col-1 menu-box" style="cursor:pointer;" id="new-noti">
			<img src="<?= Yii::$app->homeUrl ?>images/icon/info.png" class="header-icon mt-10">
			<div class="row">
				<div class="col-12 font-size12" id="unread-message" style="display:<?= $unread == 0 ? 'none' : '' ?>;margin-bottom:-5px;">
					<i class="fa fa-circle noti-circle-top" aria-hidden="true"></i>
					<span id="total-unread" class="font-size14  font-weight-bold"><?= $unread ?></span> new
					<input type="hidden" id="old-unread" value="<?= $unread ?>">
					<br>
				</div>
				<div class="col-12 header-menu text-center <?= $unread == 0 ? 'mt-10' : '' ?> text-massege">
					Message
				</div>
			</div>
		</div>

		<div class="col-1 menu-box">
			<a href="<?= Yii::$app->homeUrl ?>site/logout">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/logout.png" class="header-icon mt-10">
				<div class="row">
					<div class="col-12 mt-10 header-menu">
						Log out
					</div>

				</div>
			</a>
		</div>
		<div class="offset-lg-8 offset-md-7 offset-5 col-lg-3 col-md-4 col-7  message-noti-box border">
			<div class="col-2 close-box">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/multiply.png" class="admin-menu-close" id="close-noti-message">
			</div>
			<div class="col-12 mt-40" id="unread-noti">

			</div>
		</div>
	</div>
</div>