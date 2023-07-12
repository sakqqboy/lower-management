<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\models\lower_management\Type;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl; ?>/images/icon/icon.ico?v=1" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= isset(Yii::$app->user->id) ? Html::encode($this->title) : 'Log in' ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>
    <main role="main">
        <?php
        if (Yii::$app->user->id) {
            echo  $this->render('@frontend/theme/header');
        ?>
            <div class="col-12">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?php
                $right = 'all';
                //$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_GM . "," . Type::TYPE_HR . "," . Type::TYPE_STAFF;
                $access = Type::checkType($right);
                if ($access == 1) {
                ?>
                    <div class="admin-area">
                        <img src="<?= Yii::$app->homeUrl ?>images/icon/right.png" class="admin-icon" id="admin-icon">
                    </div>
                    <div class="admin-menu col-lg-3 col-md-4 col-sm-6 col-9 row" id="admin-menu">
                        <?= $this->render('@frontend/views/site/admin-menu') ?>
                    </div>
                <?php
                }
                ?>


            </div>
        <?php
        }
        ?>

        <?= $content ?>
        <div class="chat-box" id="chat-box">
            <div class="col-12">
                <div class="row">
                    <div class="col-10 text-left chat-job-name" id="jobName">
                    </div>
                    <div class="col-2 text-right">
                        <i class="fa fa-times close-chat" aria-hidden="true" id="close-chat"></i>
                    </div>
                </div>
            </div>
            <input type="hidden" id="isClose" value="1">
            <input type="hidden" id="lastShowChatId" value="0">
            <input type="hidden" id="lastChatId" value="0">
            <div class="show-chat" id="showChat" oncontextmenu="return false;">
            </div>
            <div class="new-message" id="new-message">
                *&nbsp;&nbsp;*&nbsp;&nbsp;*&nbsp;&nbsp; New massage &nbsp;&nbsp;*&nbsp;&nbsp;*&nbsp;&nbsp;*
            </div>
            <div class="reply-message" id="reply-message">
            </div>
            <input type="hidden" value="" id="jobChatId">

            <div class="col-12">

                <?= $this->render('@frontend/theme/chatbox')
                ?>
            </div>
        </div>
    </main>
    <?php
    if (class_exists('yii\debug\Module')) {
        $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
    }
    $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
