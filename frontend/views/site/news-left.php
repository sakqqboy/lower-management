<?php

use common\models\ModelMaster;

if (isset($news) && count($news) > 0) {
        foreach ($news as $newsId => $new) : ?>
                <div class="row news-list-left">

                        <div class="col-lg-2">
                                <?php
                                if ($new["picture"] == NULL) {
                                        $picture = "images/user/male.png";
                                } else {
                                        $picture = $new["picture"];
                                }
                                ?>
                                <img src="<?= Yii::$app->homeUrl . $picture ?>" class="image-news-left" />
                        </div>
                        <div class="col-lg-10 pt-05">
                                <div class="col-lg-12">
                                        <span class="creater-name"><?= $new["creater"] ?></span>
                                        <br>
                                        <span class="time-create"><?= ModelMaster::engDate($new["createDateTime"], 2) ?><span>
                                </div>

                        </div>
                        <div class="col-lg-12 mt-10">
                                <?php
                                $length = strlen($new["title"]);
                                ?>
                                <a href="<?= Yii::$app->homeUrl ?>news-update/default/news-detail/<?= ModelMaster::encodeParams(["newsId" => $newsId]) ?>" class="news-update-a">

                                        <?= substr($new["title"], 0, 90) ?><?= $length > 140 ? '...' : '' ?>
                                </a>
                                <img src=<?= Yii::$app->homeUrl . $new["flag"] ?> class="flag-news-left" title="<?= $new["country"] ?>">
                        </div>


                </div>
        <?php

        endforeach;
} else { ?>
        <div class="col-lg-12" style="height: 120px;margin-top:7px;border-bottom:lightgrey solid thin;">
                <div class="row">

                        <div class="col-lg-12" style="font-size:14px;color:lightgray;">
                                coming soon!!!
                        </div>

                </div>

        </div>
<?php
}
?>