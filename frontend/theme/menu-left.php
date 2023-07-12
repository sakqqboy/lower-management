<?php

use common\models\ModelMaster;
use frontend\models\wikiinvestment\Member;

?>
<div class="row">
        <div class=" col-lg-12 text-center user-image-box">
                <?php
                $picture = Member::userProfilePicture();
                if ($picture != null) {
                ?>
                        <a href="<?= Yii::$app->homeUrl ?>member/default/profile-detail/<?= ModelMaster::encodeParams(["id" => Yii::$app->user->id]) ?>">
                                <img src="<?= Yii::$app->homeUrl . $picture ?>" class="picture-profile-left">
                        </a>
                <?php
                } else { ?>
                        <img src="<?= Yii::$app->homeUrl ?>images/user/person.png" class="picture-profile-left">
                <?php
                }
                ?>
        </div>
        <div class="col-lg-12 user-zone text-center">
                <div class="row">
                        <?php
                        if (Yii::$app->user->id) { ?>
                                <div class="profile-name col-12"><?= Member::username(Yii::$app->user->id) ?></div>
                                <div class="text-center col-6">
                                        <a href="<?= Yii::$app->homeUrl ?>member/default/profile-detail/<?= ModelMaster::encodeParams(["id" => Yii::$app->user->id]) ?>" class="update-profile" id="updateProfile"><?= Yii::t('app', 'Profile') ?></a>
                                </div>
                                <div class="text-center col-6">
                                        <a href="<?= Yii::$app->homeUrl ?>site/logout" class="logout-profile" id="logout"><i class="fa fa-sign-out" aria-hidden="true"></i> <?= Yii::t('app', 'Log out') ?></a>
                                </div>

                        <?php
                        } else { ?>
                                <div class="col-12 text-center">
                                        <?= Yii::t('app', 'No Account') ?>
                                </div>
                                <div class="col-12 text-center mb-2">
                                        <button class="btn-register" id="registerCreate"><?= Yii::t('app', 'Create an account') ?></button>
                                </div>
                        <?php
                        }
                        ?>
                </div>
        </div>
</div>