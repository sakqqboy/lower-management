<?php

use common\models\ModelMaster;
use yii\bootstrap4\Carousel;
?>
<div class="row">
        <a href="<?= Yii::$app->homeUrl ?>qna">
                <img src="<?= Yii::$app->homeUrl ?>images/logo/earth.gif" class="earth">
                <div class="text-earth"><b>Go to map</b></div>
        </a>

        <div class="col-lg-8 col-md-8 col-sm-12 col-12 text-center slide-news">
                <?php carousel::widget([]);
                $total = count($slide);
                // $total2 = count($dataSide);
                // $total = $total1 + $total2;
                if ($total > 0) {
                ?>
                        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                        <?php
                                        for ($i = 0; $i < $total; $i++) :
                                        ?>
                                                <li data-target="#carouselExampleCaptions" data-slide-to="<?= $i ?>" class="<?= $i == 0 ? 'active' : '' ?>"></li>
                                        <?php
                                        endfor;
                                        ?>
                                </ol>
                                <div class="carousel-inner">
                                        <?php
                                        $i = 0;
                                        if (isset($slide) && count($slide) > 0) { //level4

                                                foreach ($slide as $newsId => $news) : ?>

                                                        <div class="carousel-item <?= $i == 0 ? 'active' : '' ?> ">
                                                                <a href="<?= Yii::$app->homeUrl ?>news-update/default/news-detail/<?= ModelMaster::encodeParams(["newsId" => $newsId]) ?>" style="text-decoration-line: none;" class="news-update-a">
                                                                        <img src="<?= Yii::$app->homeUrl . $news["picture"] ?>" class="d-block w-100">
                                                                        <div class="carousel-caption d-none d-md-block slide-text <?= $news["hasBranch"] == 1 ? 'news-topic-slide-red' : 'news-topic-slide-blue' ?>">
                                                                                <p class="slide-topic"> <?= $news["topicName"] ?></p>
                                                                                <h5 class="slide-title"><?= $news["title"] ?></h5>
                                                                        </div>
                                                                </a>
                                                        </div>

                                        <?php
                                                        $i++;
                                                endforeach;
                                        } else {
                                        }
                                        ?>

                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                </a>
                        </div>
                <?php
                } else { ?>
                        <div class="col-lg-12 text-center no-slide">
                                <h2>Coming soon...</h2>
                        </div>
                <?php  }
                ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">

                <?= $this->render(
                        'update-by',
                        [
                                "newsRight" => isset($newsRight) && count($newsRight) > 0 ? $newsRight : $newsRight = [],
                        ]
                ) ?>
        </div>
</div>