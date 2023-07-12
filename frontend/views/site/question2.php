<?php



use common\models\ModelMaster;
use frontend\models\wikiinvestment\MemberPlan; 
?>

<div class="question-big-box2 row">



        <div class="col-12">

                <div class="col-lg-12 head-qna">
<?php
$memberPlan = MemberPlan::memberplanid();

?>

<?php 
if($memberPlan=='10')
{
	?>
     <a href="<?= Yii::$app->homeUrl ?>site/access-denied" style="text-decoration-line: none;">

                                <h2 style="color:#73C6B6;"><b>Q & A</b></h2>

                        </a>
    <?php
}
else
{
	?>
                        <a href="<?= Yii::$app->homeUrl ?>qna" style="text-decoration-line: none;">

                                <h2 style="color:#73C6B6;"><b>Q & A</b></h2>

                        </a>
                        
<?php
}
?>

                </div>

                <div class="col-12 main-background qr-scrollbar">

                        <div class="row">

                                <?php

                                $i = 0;

                                if (isset($question) && count($question) > 0) {

                                        foreach ($question as $questionId => $q) : ?>

                                                <div class="col-lg-12 home-question text-left">

         <a href="<?= Yii::$app->homeUrl ?>qna/topic/answer/<?= ModelMaster::encodeParams(["questionId" => $questionId]) ?>" class="question-link">

                                                                <?= $q ?>



                                                        </a>

                                                        <div class="col-lg-12 mb-4 pr-0">

                                                                <img src="<?= Yii::$app->homeUrl . $flag[$questionId] ?>" class="flag-head">

                                                        </div>

                                                        <hr class="hr-line">

                                                </div>



                                <?php

                                        endforeach;

                                }

                                ?>

                        </div>

                </div>

        </div>

</div>