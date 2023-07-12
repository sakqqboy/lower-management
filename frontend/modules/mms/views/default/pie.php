<div class="body-content pt-30">
	<div class="col-12" style="height:400px !important;">
		<?php

		use miloschuman\highcharts\Highcharts;
		use yii\web\JsExpression;

		echo Highcharts::widget([
			'options' => [
				'title' => ['text' => $chart["chartName"]],
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
					'categories' => ['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums'],
				],
				'yAxis' => [
					'title' => ['text' => 'Fee']
				],
				'series' =>
				[
					[ // new opening bracket
						'type' => 'pie',
						'data' => $value,
						'size' => 300,
						'showInLegend' => true,
						'dataLabels' => [
							'enabled' => true,
						],
					]
				]

			]
		]);
		?>
	</div>
	<div class="offset-lg-1 offset-md-1 col-lg-10 col-md-10 col-12  mt-20">
		<?= $this->render('detail_pie', [
			"chartResult" => $chartResult,
			"xData" => $xData,
			"chart" => $chart
		]) ?>
	</div>
</div>