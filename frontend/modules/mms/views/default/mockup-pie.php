<?php

use miloschuman\highcharts\Highcharts;

echo Highcharts::widget([
	'options' => [
		'title' => [
			'text' => $chartName,
			'style' => [
				'fontSize' => '16px'
			],

		],

		'tooltip' => [
			//'pointFormat' => "{series.name}: <b>{point.percentage:.1f}%</b>"
		],
		'chart' => [
			'plotBackgroundColor' => null,
			'plotBorderWidth' => null,
			'plotShadow' => false,
			'height' => '400',

		],
		'plotOptions' => [
			'pie' => [
				'cursor' => 'pointer',
				'animation' => false,
				"dataLabels" => [
					"enabled" => true,
					"color" => "#000000",
					//"format" => "<b>{point.name}</b>: {point.percentage:.2f} %"
					"format" => $dataType
				],
				'colors' => $colors,
			],
		],
		'xAxis' => [
			//'categories' => $title,
		],
		'yAxis' => [
			//'title' =>  $title
		],
		'series' =>
		[
			[ // new opening bracket
				'type' => 'pie',
				'data' => $value,
				'size' => 300,
				'showInLegend' => false,
				'dataLabels' => [
					'enabled' => true,
				],
			]
		]

	]
]);
