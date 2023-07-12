<?php

use miloschuman\highcharts\Highcharts;

$dataType = "<b>{point.name}</b>: {point.y}";
echo Highcharts::widget([
	'options' => [
		'title' => ['text' => $chartName],
		'tooltip' => [
			'pointFormat' => "{series.name}: <b>{point.percentage:.1f}%</b>"
		],
		'chart' => [
			'plotBackgroundColor' => null,
			'plotBorderWidth' => null,
			'plotShadow' => false
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
			//'title' => ['text' => 'Fee']
		],
		'series' =>
		[
			[ // new opening bracket
				'type' => 'pie',
				'data' => $values,
				'size' => 300,
				'showInLegend' => false,
				'dataLabels' => [
					'enabled' => true,
				],
			]
		]

	]
]);
