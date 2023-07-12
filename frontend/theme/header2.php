<?php

//use Yii;

use frontend\models\wikiinvestment\Member;
use frontend\models\wikiinvestment\MemberPlan;
use frontend\models\wikiinvestment\MemberQuestion;
use frontend\models\wikiinvestment\Notification;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$notiList = Notification::notificationList();
?>
<div class="navbar-top col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-1  <?= !isset(Yii::$app->user->id) ? 'search-box-zone' : '' ?>">
                        <div class="row">
                                <?php if (isset(Yii::$app->user->id)) { ?>
                                        <div class="pt-10 w-20 text-center menu">
                                                <i class=" fas fa-bars menu-icon" id="menu-icon"></i>
                                        </div>
                                <?php } ?>
                                <div class="search-box <?= isset(Yii::$app->user->id) ? 'w-80' : 'width-100' ?>">
                                        <input type=" text" class="form-control input-box" placeholder="<?= Yii::t('app', 'Search') ?>" id="<?= Yii::$app->user->id ? 'search' : 'registerSearch' ?>">
                                        <i class="fas fa-search search-icon"></i>
                                </div>
                        </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-4  <?= !isset(Yii::$app->user->id) ? 'col-6' : 'col-11' ?> text-center padding-top10">

                        <a href="<?= Yii::$app->homeUrl ?>" class="logo-link" id="logo-link" style="text-decoration: none;">
                                <span><img src="<?= Yii::$app->homeUrl ?>images/logo/logo.png" class="logo"></span>
                                <!-- <span id="text-link" class="bolder text-link"> WIKI INVESTMENT</span> -->
                        </a>

                </div>

                <?php
                if (isset(Yii::$app->user->id)) {
                        $planName = MemberPlan::MemberCurrentPlan();
                        $totalQna = 0;
                        $ask = 0;
                        if ($planName != '') { ?>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-6 text-right pt-10 fontSize-12">
                                        <?php
                                        $totalQna = MemberPlan::CurrentPlanLeftQna();
                                        $asked = MemberQuestion::countMemberQuestionPerMonth();
                                        $remain = $totalQna - $asked;
                                        ?>


                                        <span class="pull-right">
                                                <?= Yii::t('app', $planName) ?> Q & A : <?= $remain ?>/<?= $totalQna ?>
                                        </span>
                                </div>
                <?php    }
                }
                ?>

                <div class="<?= isset(Yii::$app->user->id) ? 'col-lg-3' : 'col-lg-3' ?> col-md-12 col-sm-12 <?= !isset(Yii::$app->user->id) ? 'col-6' : 'col-6' ?> login-zone bordertest">

                        <?php
                        if (isset(Yii::$app->user->id)) {
                                $member = 1;
                        } else {
                                $member = 0;
                        }
                        if ($notiList > 0) { ?>

                                <span class="alert-bell-yellow" id="noti-alert" style="display:<?= $member == 0 ? 'none' : '' ?>">
                                        <i class="fa fa-bell" aria-hidden="true"></i>
                                        <span class="count-alert text-center"><?= $notiList ?></span>
                                </span>


                        <?php
                        } else { ?>

                                <span class="alert-bell-white" id="noti-alert" style="display:<?= $member == 0 ? 'none' : '' ?>">
                                        <i class="fa fa-bell" aria-hidden="true"></i>
                                        <span class="count-alert text-center" style="display:<?= $notiList == 0 ? 'none' : '' ?>"></span>
                                </span>
                        <?php

                        }
                        ?>

                        <!-- <span class="change-language mt-10"> -->
                        <?php /*
                                echo Html::a('EN', Url::current(['language' => 'en-US']), ['class' => (Yii::$app->request->cookies['language'] == 'en-US' ? 'active text-language' : 'text-language')]);
                                echo " | ";
                                echo Html::a('JP', Url::current(['language' => 'jp-JP']), ['class' => (Yii::$app->request->cookies['language'] == 'jp-JP' ? 'active text-language' : 'text-language')]);
                               */ ?>
                        <!-- </span> -->
                        <?php
                        if (!Yii::$app->user->id) {
                        ?>
                                <a href="#" style="text-decoration: none;" class="regisText " id="register"><?= Yii::t('app', 'Register') ?></a>
                                <button class="login-button mt-10">
                                        <a href="#" class="white" style="text-decoration: none;" id="login"><b><?= Yii::t('app', 'Login') ?></b>
                                        </a>
                                </button>
                                <?php
                        } else {
                                $picture = Member::userProfilePicture();
                                if ($picture != null) {
                                ?>
                                        <a href="<?= Yii::$app->homeUrl ?>member/default/profile" style="text-decoration-line: none;">
                                                <img src="<?= Yii::$app->homeUrl . $picture ?>" class="image-top profile-top" />
                                        </a>
                                <?php
                                } else { ?>
                                        <img src="<?= Yii::$app->homeUrl . $picture ?>images/user/male.png" class="image-top profile-top" />
                                <?php
                                }
                                ?>

                                <span class="name-top"><?= Member::username(Yii::$app->user->id) ?></span>


                                <!-- <i class="fa fa-bell alert-bell" aria-hidden="true"></i> -->


                        <?php
                        } ?>
                </div>
        </div>
</div>