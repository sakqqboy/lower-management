<?php

use frontend\models\lower_management\Chart;
use miloschuman\highcharts\Highcharts;
?>
<div class="row">
	<div class="col-lg-5 col-md-6 col-12 mt-20">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>color</th>
					<th>Title</th>
					<th>value</th>
				</tr>
			</thead>
			<tbody>
				<input type="hidden" id="total-piece" value="<?= count($value) ?>">
				<?php
				$colors = Chart::setColor();
				if (count($value) > 0) {
					$i = 0;
					while ($i < count($value)) { ?>
						<tr>
							<td>
								<div class="col-12" style="background-color:<?= $colors[$i] ?>;min-height:25px;">

								</div>
							</td>
							<td>
								<input type="text" class="form-control" name="piece[<?= $i ?>]" id="piece<?= $i ?>" onkeyup="javascript:generatePieGraph()">
							</td>
							<td>
								<input type="text" class="form-control text-right" name="value[<?= $i ?>]" id="piece-value-<?= $i ?>" onkeyup="javascript:generatePieGraph()">
							</td>
						</tr>
				<?php
						$i++;
					}
				}
				?>
			</tbody>
		</table>
		<div class="col-12 text-right">
			<button type="submit" class="button-blue text-center form-control calculate-button" id="save-cal">
				<i class="fa fa-download mr-10" aria-hidden="true"></i></i> <b>Save</b>
			</button>
		</div>
	</div>
	<div class="col-lg-7 col-md-6 col-12  mt-20" id="show-pie-chart">
		<?= Highcharts::widget([
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
						'size' => 300,
						'showInLegend' => false,
						'dataLabels' => [
							'enabled' => true,
						],
					]
				]

			]
		]);
		?>
	</div>
</div>