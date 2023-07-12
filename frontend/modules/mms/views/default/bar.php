<div class="body-content pt-30">
	<div class="col-12" style="height:400px !important;">
		<?php

		use miloschuman\highcharts\Highcharts;

		echo Highcharts::widget([
			'scripts' => [
				'modules/exporting',
				'themes/grid-light',
			],

			'options' => [
				'title' => [
					'text' => $chart["chartName"]
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
							// 'enabled' => true,
							//'format' => '{point.y}',

						],
					],
					'column' => [
						'borderRadius' => 2,
						'pointWidth' => 50,
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
		?>
	</div>
	<div class="offset-lg-1 offset-md-1 col-lg-10 col-md-10 col-12  mt-20">
		<?= $this->render('detail', [
			"chartData" => $chartData,
			"chartResult" => $chartResult,
			"xData" => $xData,
			"chart" => $chart
		]) ?>
	</div>
</div>