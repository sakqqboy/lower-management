<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Alert;
use yii\helpers\ArrayHelper;

$this->title = 'WIKI INVESTMENT';
?>
<div class="col-12">

        <?php if (Yii::$app->session->hasFlash('alert')) : ?>
                <?= Alert::widget([
                        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                ]) ?>
        <?php endif; ?>

        <div class="row">
                <div class="col-lg-9 col-md-12">
                        <div class="row">
                                <div class="col-lg-12">
                                        <?= $this->render('slide-news3', [

                                                "slide" => isset($slide) && count($slide) > 0 ? $slide : $slide = [],
                                                "newsRight" => isset($newsRight)  && count($newsRight) > 0 ? $newsRight : $newsRight = [],
                                        ]) ?>
                                </div>
                                <div class="col-lg-12" style="min-height: 300px;">

                                        <?= $this->render('news-update', [
                                                "newsUpdate" => isset($newsUpdate) && count($newsUpdate) > 0 ? $newsUpdate : $newsUpdate["all"] = []
                                        ]) ?>


                                </div>
                        </div>
                </div>
                <div class="col-lg-3 col-md-12 text-center question-right">
                        <?= $this->render('question2', ["question" => $question, "flag" => $flag]) ?>
                        <div class="row">
                                <div class="col-lg-12 text-center question-right">
                                        <?php // $this->render('movie', ["question" => $question, "flag" => $flag]) 
                                        ?>
                                </div>
                        </div>
                </div>
                <?php
                // throw new Exception(count($newsUpdate));
                if (count($newsUpdate) > 8) { ?>
                        <div class="col-lg-12">
                                <?= $this->render('news-update3', [
                                        "newsUpdate" => isset($newsUpdate) && count($newsUpdate) > 0 ? $newsUpdate : $newsUpdate["all"] = []
                                ]) ?>
                                <?= $this->render('news-update2', [
                                        "newsUpdate" => isset($newsUpdate) && count($newsUpdate) > 0 ? $newsUpdate : $newsUpdate["all"] = []
                                ]) ?>
                        </div>
                <?php
                }
                ?>

        </div>

</div>
<?php
$this->registerJs('
$("#first-page").ready(function() {
        setTimeout(function() {
            $("#first-page").css("display", "none");
            $("#main-page").show();
        }, 2000);
    });
');
?>