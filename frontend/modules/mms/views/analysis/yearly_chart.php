<?php

use miloschuman\highcharts\Highcharts;

//throw new Exception(print_r($values, true));
echo Highcharts::widget([
	'scripts' => [
		'modules/exporting',
		'themes/grid-light',
	],

	'options' => [
		'title' => [
			'text' => $chartName
		],
		'chart' => [
			'type' => 'column',


		],
		//'colors'=> ["#000000"],
		'xAxis' => [
			'categories' => $xData,
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
					'enabled' => true,
					//'format' => '{point.y}',

				],
			],
			'column' => [
				'borderRadius' => 2,
				'pointWidth' => 13,
			]
		],
		'labels' => [
			'items' => [
				[
					'html' => 'result',
					'style' => [
						//'left' => '50px',
						//'top' => '100px',
						//'colors' => new \yii\web\JsExpression('(Highcharts.theme && Highcharts.theme.textColor) || "black"'),
						//'colors' => new \yii\web\JsExpression('["#0066FF", "#FF9900", "#BEBEBE"] || "black"'),
					],
				],
			],
		],
		'exporting' => [
			'enabled' => false
		],
		'series' =>  $values,
	],
]);
