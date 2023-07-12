<?php


use miloschuman\highcharts\Highcharts;

?>
<div class="body-content pt-30">
	<div class="row">
		<div class="offset-lg-1 offset-md-1 col-lg-10 col-md-10 col-12">
			<div class="col-12" style="height:400px !important;">
				<?php
				echo Highcharts::widget([
					'options' => [
						'title' => ['text' => $chart["chartName"]],
						'xAxis' => [
							'categories' => $xData
						],
						'yAxis' => [
							'title' => ['text' => $chart["yName"]]
						],
						'series' => $value
					]
				]);
				?>
			</div>
		</div>
		<div class="offset-lg-1 offset-md-1 col-lg-10 col-md-10 col-12 mt-20">
			<?= $this->render('detail', [
				"chartData" => $chartData,
				"chartResult" => $chartResult,
				"xData" => $xData,
				"chart" => $chart
			]) ?>
		</div>
	</div>
</div>