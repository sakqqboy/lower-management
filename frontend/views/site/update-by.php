<div class="row">
        <?php

        use common\models\ModelMaster;

        $total = count($newsRight);
        // throw new Exception($total);
        if ($total > 0) {
                $i = 1;
                foreach ($newsRight as $newsId => $news) :
        ?>
                        <div class="col-lg-12 col-md-12 col-sm-6 col-12 text-left  update-by-site">
                                <a href="<?= Yii::$app->homeUrl ?>news-update/default/news-detail/<?= ModelMaster::encodeParams(["newsId" => $newsId]) ?>" style="text-decoration: none;">
                                        <img src="<?= Yii::$app->homeUrl . $news['picture'] ?>" class="update-by-image">
                                        <div class="text-on1 <?= $news["hasBranch"] == 1 ? 'news-topic-slide1' : 'news-topic-slide2' ?>">
                                                <?= $news["title"] ?>
                                        </div>
                                </a>
                        </div>
                <?php
                        $i++;
                        if ($i == 3) {
                                break;
                        }
                endforeach;
        } else { ?>
                <div class="col-lg-12  text-left" style="height: 150px;">
                        <img src="<?= Yii::$app->homeUrl ?>images/news/updateBy/g1.jpg" class="update-by-image">
                        <div class="text-on1">
                                <a href="#" style="color:lightgray;text-decoration: none;">Waiting for update ...</a>
                        </div>
                </div>
                <div class="col-lg-12  text-left" style="height: 150px;">
                        <img src="<?= Yii::$app->homeUrl ?>images/news/updateBy/g2.jpg" class="update-by-image">
                        <div class="text-on1">
                                <a href="#" style="color:lightgray;text-decoration: none;">Waiting for update ...</a>
                        </div>
                </div>

        <?php    }
        if ($total == 1) { ?>
                <div class="col-lg-12  text-left" style="height: 150px;">
                        <img src="<?= Yii::$app->homeUrl ?>images/news/updateBy/g2.jpg" class="update-by-image">
                        <div class="text-on1">
                                <a href="#" style="color:lightgray;text-decoration: none;">Waiting for update ...</a>
                        </div>
                </div>
        <?php }
        ?>

</div>