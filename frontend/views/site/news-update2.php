<div class="row">

        <?php



        use common\models\ModelMaster;



        if (isset($newsUpdate) && count($newsUpdate) > 0) {

                foreach ($newsUpdate as $countryId => $allNews) : ?>

                        <?php

                        foreach ($allNews as $newsId => $news) :

                                if (count($newsUpdate[$countryId]) == 1) { //only2content

                        ?>

                                        <div class="col-lg-4 col-md-6 col-sm-12 col-12 news-box">

                                                <a href="<?= Yii::$app->homeUrl ?>news-update/default/news-detail/<?= ModelMaster::encodeParams(["newsId" => $newsId]) ?>" class="news-update-a">

                                                        <img src="http://tcg-wiki-investment.com/<?= Yii::$app->homeUrl . $news["picture"] ?>" class="news-update-image">

                                                </a>



                                                <div class="col-lg-12 news-topic-home">

                                                        <span class=" news-topic-home-span1"><?= Yii::t('app', $news["topicName"]) ?></span>

                                                        <span class="news-topic-home-span2">:: <?= Yii::t('app', $news["country"]) ?> ::</span>

                                                </div>

                                                <div class="col-lg-12">

                                                        <div class="row ">

                                                                <?php

                                                                $length = strlen(trim($news["title"]));

                                                                ?>

                                                                <div class="col-lg-12 news-update-title">



                                                                        <a href="<?= Yii::$app->homeUrl ?>news-update/default/news-detail/<?= ModelMaster::encodeParams(["newsId" => $newsId]) ?>" style="text-decoration-line: none;" class="news-update-a">

                                                                                <?= mb_substr(trim($news["title"]), 0, 50) ?><?= $length > 50 ? '...' : '' ?>

                                                                        </a>

                                                                </div>

                                                                <div class="col-lg-12">

                                                                        <hr class="hr-line">

                                                                </div>

                                                                <div class="col-lg-12 news-update-content ">



                                                                        <?php echo mb_substr(strip_tags($news["content"]), 0, 150) ?>...

                                                                </div>

                                                                <div class="col-lg-12  news-update-like">

                                                                        <span class="news-topic-home-span1"><?= ModelMaster::engDate($news["createDateTime"], 2) ?></span>

                                                                        <span class="news-topic-home-span2">

                                                                                <?php

                                                                                if ($news["question"] > 0) {

                                                                                ?>

                                                                                        <a href="<?= Yii::$app->homeUrl ?>news-update/news/news-question/ <?= ModelMaster::encodeParams(["newsId" => $newsId]) ?>"><?= $news["question"] ?>&nbsp;&nbsp;<?= Yii::t('app', 'Questions') ?></a>

                                                                                        &nbsp;&nbsp;&nbsp;<?= $news["views"] ?> <?= Yii::t('app', 'views') ?>

                                                                                <?php

                                                                                } else { ?>

                                                                                        <?= $news["question"] ?>&nbsp;&nbsp;<?= Yii::t('app', 'Questions') ?>

                                                                                        &nbsp;&nbsp;&nbsp;<?= $news["views"] ?> <?= Yii::t('app', 'views') ?>

                                                                                <?php

                                                                                }

                                                                                ?>

                                                                        </span>

                                                                </div>

                                                        </div>

                                                </div>

                                        </div>

                        <?php

                                }

                        endforeach;

                endforeach;

        } else {

                $i = 0;

                while ($i < 12) {

                        ?>

                        <div class="col-lg-6 text-center mb-20 bordertest">

                                <div class="col-lg-12 no-slide">

                                        <h2>Coming soon...</h2>

                                </div>



                        </div>



        <?php

                        $i++;

                }

        }

        ?>





</div>