<div class="body-content pt-30">
	<div class="row">
		<div class="col-lg-6 col-12">
			<?= $this->render('edit', [
				"chart" => $chart,
				"country" => $country,
				"pie" => 1
			]) ?>
		</div>
		<div class="col-lg-6 col-12">
			<?php

			use miloschuman\highcharts\Highcharts;

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
							'size' => 250,
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
			<?= $this->render('edit_data', [
				"chart" => $chart,
				"country" => $country,
				"chartData" => $chartData,
				"chartResult" => $chartResult,
				"xData" => $xData,
			]) ?>
		</div>
	</div>

</div>