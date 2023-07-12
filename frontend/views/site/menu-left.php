<?php



use frontend\models\wikiinvestment\Menu;

use frontend\models\wikiinvestment\MemberPlan; 
 
$menus = Menu::getMenu2();

if (count($menus) > 0) {

	$i = 0;

	foreach ($menus as $parentId => $mainMenu) :

		$cloud = 0;

		if ($mainMenu["link"] != '') {

			$moduleArr = explode('/', $mainMenu["link"]);

			if ($moduleArr[0] == 'video' && $_SERVER['HTTP_HOST'] != "localhost") {

				$cloud = 1;

			}

?>

			<a href="<?= $cloud == 1 ? 'http://tcg-wiki-investment.com/' . $mainMenu["link"] : Yii::$app->homeUrl . $mainMenu["link"] ?>" style="color:black;text-decoration:none;">

			<?php

		} else { ?>

				<a style="color:black;text-decoration:none;" onclick="javascript:showSubMenu(<?= $i ?>)">

				<?php

			} ?>
<?php
$memberPlan = MemberPlan::memberplanid();

?>
						<div class="col-lg-10 col-10">

<?php 
if($mainMenu["menuName"]=='My Questions' && ($memberPlan=='3' || $memberPlan=='10'))
{
}
else
{
	?>
				<div class="col-lg-12 list-menu-left">

					<div class="row">

						<div class="col-lg-2 col-2">

							<i class="<?= $mainMenu["icon"] ?> menu-left-icon"></i>

						</div>



							<?= Yii::t('app', $mainMenu["menuName"]) ?>
						<?php

							if ($mainMenu["isAlert"] == 1) {

								$total = Menu::countAlert($mainMenu["topic"]);

								if ($total > 0) {

							?>

									<span class="badge badge-warning" style="float:right;margin-top:5px;"><?= $total ?></span>



								<?php

								}

							}

							if ($mainMenu["link"] == '') {

								?>



								<i class="fa fa-angle-up" id="up<?= $i ?>" aria-hidden="true" style="float:right;display:none;"></i>

								<i class="fa fa-angle-down" id="down<?= $i ?>" aria-hidden="true" style="float:right;"></i>

							<?php

							}

							?>

						</div>

					</div>
<?php

}
?>
				</div>

				</a>

				<div id="menu<?= $i ?>" style="display: none;">

					<?php

					if ($mainMenu["link"] == '') {

						foreach ($mainMenu as $menuId => $child) :

							$cloud = 0;

							if (gettype($menuId) == 'integer') {

								$moduleArr = explode('/', $child["link"]);

								if ($moduleArr[0] == 'video' && $_SERVER['HTTP_HOST'] != "localhost") {

									$cloud = 1;

								}



					?>

								<a href="<?= $cloud == 1 ? 'http://tcg-wiki-investment.com/' . $child["link"] : Yii::$app->homeUrl . $child["link"] ?>" style="color:black;text-decoration:none;">

									<div class="col-lg-12 list-subMenu">

										<div class="row">

											<div class="col-lg-1 col-1 subMenu-box">

												<i class="<?= $child["icon"] ?>"></i>

											</div>

											<div class="col-lg-10 col-10 subMenu-box">

												<?= Yii::t('app', $child["menuName"]) ?>

												<?php

												if ($child["isAlert"] == 1) {

													$total = Menu::countAlert($child["topic"]);

													if ($total > 0) {

												?>

														<span class="badge badge-warning" style="float:right;margin-top:5px;"><?= $total ?></span>



												<?php

													}

												}

												?>

											</div>

										</div>

									</div>

								</a>

					<?php

							}

						endforeach;

					} ?>

				</div>

			<?php

			$i++;

		endforeach;

	}

	if (Yii::$app->user->id) { ?>


<a href="<?= Yii::$app->homeUrl ?>member/contact?q=Request Withdawal" style="color:black;text-decoration:none;">

				<div class="col-lg-12 list-menu-left">

					<div class="row">



						<div class="col-lg-2 col-2">

							<i class="fa fa-sign-out menu-left-icon"></i>

						</div>

						<div class="col-lg-10 col-10">

							<?= Yii::t('app', '退会') ?>

						</div>



					</div>

				</div>

			</a>
			<a href="<?= Yii::$app->homeUrl ?>site/logout" style="color:black;text-decoration:none;">

				<div class="col-lg-12 list-menu-left">

					<div class="row">



						<div class="col-lg-2 col-2">

							<i class="fa fa-sign-out menu-left-icon"></i>

						</div>

						<div class="col-lg-10 col-10">

							<?= Yii::t('app', 'Log out') ?>

						</div>



					</div>

				</div>

			</a>

		<?php

	}

		?>