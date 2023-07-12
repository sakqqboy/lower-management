<div class="body-content pt-30">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-6 col-16">
            <select class="form-control">
                <option>Branch</option>
                <?php

                use common\models\ModelMaster;
                use frontend\models\lower_management\Chart;
                use miloschuman\highcharts\Highcharts;

                if (isset($branch) && count($branch) > 0) {
                    foreach ($branch as $b) : ?>
                        <option value="<?= $b['branchId'] ?>"><?= $b["branchName"] ?></option>
                <?php

                    endforeach;
                }
                ?>
            </select>
        </div>
        <div class="col-lg-3 text-right">
            <select class="form-control">
                <option>Team</option>
                <?php
                if (isset($team) && count($team) > 0) {
                    foreach ($team as $t) : ?>
                        <option value="<?= $t['teamId'] ?>"><?= $t["teamName"] ?></option>
                <?php

                    endforeach;
                }
                ?>
            </select>
        </div>
        <div class="col-12 mt-10 font-size22 font-weight-bolder">
            <hr>
            <a href="<?= Yii::$app->homeUrl ?>mms/default/adding-report" class="no-underline-black">
                <img src="<?= Yii::$app->homeUrl ?>images/icon/plus.png" class="header-icon mr-20">
                Adding Analysis report
            </a>
            <hr>
        </div>
        <?php
        if (isset($charts) && count($charts) > 0) {
            foreach ($charts as $chart) :
                $dataType = "<b>{point.name}</b>: {point.y}";
                if ($chart["dataType"] == 2) {
                    $dataType = "<b>{point.name}</b>: {point.percentage:.2f} %";
                }

        ?>
                <div class="col-lg-3  col-md-6 col-12 mt-10 mb-10">
                    <a href="<?= Yii::$app->homeUrl ?>mms/default/show-graph/<?= ModelMaster::encodeParams(['chartId' => $chart['chartId']]) ?>" class="no-underline-black">
                        <div class="col-12 font-size14 mb-1">
                            <b><?= $chart["chartName"] ?></b>
                        </div>
                        <div class="col-12 border">
                            <?php
                            if ($chart["chartType"] == Chart::TYPE_LINE) {
                                $data = Chart::chartResult($chart["chartId"]);
                                if (isset($data["data"]) && count($data["data"]) > 0) {
                                    $xData = Chart::getXvacter($chart["xType"], $chart["startYear"]);
                                    foreach ($data as $all) :
                                        foreach ($all as $row => $dataArr) :
                                            $i = 0;
                                            while ($i < count($xData)) {
                                                if (!isset($dataArr[$i])) {
                                                    $dataArr[$i] = null;
                                                }
                                                $i++;
                                            }
                                            ksort($dataArr);
                                            $dataLine[$row - 1] = [
                                                "name" => Chart::findRowName($chart["chartId"], $row),
                                                "data" => $dataArr
                                            ];
                                        endforeach;
                                    endforeach;
                                    //throw new Exception(print_r($value, true));
                                } else {
                                    $dataLine[0] = [
                                        "name" => $chart["resultName"],
                                        "data" =>  $data

                                    ];
                                }
                                echo Highcharts::widget([
                                    'options' => [
                                        'title' => [
                                            'text' => $chart["chartName"],
                                            'style' => [
                                                'fontSize' => '12px'
                                            ]
                                        ],
                                        'chart' => [
                                            'height' => '200'
                                        ],
                                        'xAxis' => [
                                            'categories' => Chart::getXvacter($chart["xType"], $chart["startYear"])
                                        ],
                                        'yAxis' => [
                                            'title' => ['text' => $chart["yName"]]
                                        ],
                                        'series' => $dataLine,
                                    ]
                                ]);
                            }
                            if ($chart["chartType"] == Chart::TYPE_PIE) {
                                $result = Chart::chartResult($chart["chartId"]);
                                if (isset($result) && count($result) > 0) {
                                    foreach ($result as $index => $re) :
                                        $value[$index] = [
                                            Chart::pieVacter($chart["chartId"], $index), $re
                                        ];
                                    endforeach;
                                }
                                echo Highcharts::widget([
                                    'options' => [
                                        'title' => [
                                            'text' => $chart["chartName"],
                                            'style' => [
                                                'fontSize' => '12px'
                                            ]
                                        ],
                                        'tooltip' => [
                                            //'pointFormat' => "{series.name}: <b>{point.percentage:.1f}%</b>"
                                        ],
                                        'chart' => [
                                            'plotBackgroundColor' => null,
                                            'plotBorderWidth' => null,
                                            'plotShadow' => false,
                                            'height' => '200'
                                        ],
                                        'plotOptions' => [
                                            'pie' => [
                                                'cursor' => 'pointer',
                                                "dataLabels" => [
                                                    "enabled" => true,
                                                    "color" => "#000000",
                                                    "format" =>  $dataType
                                                ],
                                                'colors' => $colors,
                                            ],

                                        ],
                                        'xAxis' => [
                                            //'categories' => ['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums'],
                                        ],
                                        'yAxis' => [
                                            // 'title' => ['text' => 'Fee']
                                        ],
                                        'series' =>
                                        [
                                            [ // new opening bracket
                                                'type' => 'pie',
                                                'data' => $value,
                                                'size' => 100,
                                                'showInLegend' => false,
                                                'dataLabels' => [
                                                    'enabled' => true,
                                                ],
                                            ]
                                        ]

                                    ]
                                ]);
                            }
                            if ($chart["chartType"] == Chart::TYPE_BAR) {
                                $data = Chart::chartResult($chart["chartId"]);
                                if (isset($data["data"]) && count($data["data"]) > 0) {
                                    $xData = Chart::getXvacter($chart["xType"], $chart["startYear"]);
                                    foreach ($data as $all) :
                                        foreach ($all as $row => $dataArr) :
                                            $i = 0;
                                            while ($i < count($xData)) {
                                                if (!isset($dataArr[$i])) {
                                                    $dataArr[$i] = null;
                                                }
                                                $i++;
                                            }
                                            ksort($dataArr);
                                            $value[$row - 1] = [
                                                "name" => Chart::findRowName($chart["chartId"], $row),
                                                "data" => $dataArr
                                            ];
                                        endforeach;
                                    endforeach;
                                    //throw new Exception(print_r($value, true));
                                } else {
                                    $value[0] = [
                                        'type' => 'column',
                                        "name" => $chart["resultName"],
                                        'data' =>  Chart::chartResult($chart["chartId"]) // Your dataset
                                    ];
                                }
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'modules/exporting',
                                        'themes/grid-light',
                                    ],

                                    'options' => [
                                        'title' => [
                                            'text' => $chart["chartName"],
                                            'style' => [
                                                'fontSize' => '12px'
                                            ]
                                        ],
                                        'chart' => [
                                            'type' => 'column',
                                            'height' => '200'


                                        ],
                                        //'colors'=> ["#000000"],
                                        'xAxis' => [
                                            'categories' => Chart::getXvacter($chart["xType"], $chart["startYear"]),
                                            'minPadding' => 1,
                                            'maxPadding' => 1,
                                            // 'gridLineColor' => '#9E9998',
                                            'gridLineColor' => '#ffffff',
                                        ],
                                        'yAxis' => [
                                            //'max' => 4000,
                                            'type' => 'logarithmic',
                                            //'gridLineColor' => '#9E9998',
                                            'gridLineColor' => '#ffffff',
                                        ],
                                        'legend' => [
                                            'enable' => 'false',
                                        ],
                                        'plotOptions' => [

                                            'series' => [
                                                'pointPadding' => 0.25,
                                                'groupPadding' => 0,

                                                'dataLabels' => [
                                                    // 'enabled' => true,
                                                    //'format' => '{point.y}',

                                                ],
                                            ],
                                            'column' => [
                                                'borderRadius' => 2,
                                                'pointWidth' => 10,
                                            ]
                                        ],
                                        'labels' => [
                                            'items' => [
                                                [
                                                    'html' => 'result',
                                                    'style' => [
                                                        // 'left' => '50px',
                                                        // 'top' => '100px',
                                                        'color' => new \yii\web\JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
                                                    ],
                                                ],
                                            ],
                                        ],
                                        'series' =>  $value,
                                    ],
                                ]);
                            }
                            ?>
                        </div>
                        <div class="col-12 text-right mt-1">
                            <a href="<?= Yii::$app->homeUrl ?>mms/default/edit-graph/<?= ModelMaster::encodeParams(["chartId" => $chart["chartId"]]) ?>" class="button-yellow no-underline button-sm">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>
                            <a href="javascrit:deleteChart(<?= $chart["chartId"] ?>)" class="button-red no-underline button-sm ml-10">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </a>
                </div>
        <?php
            endforeach;
        }
        ?>
    </div>


</div>