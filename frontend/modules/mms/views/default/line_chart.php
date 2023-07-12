<?php


use miloschuman\highcharts\Highcharts;

?>
<div class="row">
	<div class="col-1">
		<select class="form-control font-size12">
			<option>Fee</option>
			<option>Percent</option>
		</select>

	</div>
	<div class="col-10">
		<div class="col-12" style="height:400px !important;">
			<?php
			echo Highcharts::widget([
				'options' => [
					'title' => ['text' => 'Balance of uncollected Fee (by month of occurence)'],
					'xAxis' => [
						'categories' => $monthText
					],
					'yAxis' => [
						'title' => ['text' => 'Fee']
					],
					'series' => $chartData
				]
			]);
			?>
		</div>
	</div>
	<div class="col-1">
		<select class="form-control font-size12">
			<option>Type</option>
			<option>Line</option>
			<option>Bar</option>
			<option>Pie</option>
		</select>
		<select class="form-control font-size12" style="margin-top:240px;">
			<option>Vactor</option>
			<option>Dream team</option>
			<option>Branch</option>
			<option>Year</option>
		</select>
	</div>
</div>