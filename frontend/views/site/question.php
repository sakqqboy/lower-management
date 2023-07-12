<?php

use common\models\ModelMaster;
?>
<div class="row question-big-box">
        <div class="col-lg-12" style="height: 50px;margin-bottom:5px;">
                <a href="<?= Yii::$app->homeUrl ?>qna" style="text-decoration-line: none;color:white">
                        <h3><b>Q & A</b></h3>
                </a>
        </div>

        <div class="container">
                <?php



                $i = 0;
                if (isset($question) && count($question) > 0) {
                        foreach ($question as $questionId => $q) : ?>
                                <div class="col-lg-12 home-question text-left">
                                        <a href="<?= Yii::$app->homeUrl ?>qna/topic/answer/<?= ModelMaster::encodeParams(["questionId" => $questionId]) ?>" class="question-link">
                                                <?= $q ?>
                                                <img src="<?= Yii::$app->homeUrl . $flag[$questionId] ?>" class="flag-head">
                                        </a>
                                </div>
                <?php
                        endforeach;
                }
                ?>

        </div>

</div>