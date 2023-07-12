<?php

use frontend\models\lower_management\Chart;

$this->title = $chart["chartName"];
?>
<div class="row">
	<div class="offset-lg-1 offset-md-1 col-lg-10 col-md-10 col-12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>color</th>
					<th>Title</th>
					<th>value</th>
				</tr>
			</thead>
			<tbody>

				<?php
				$colors = Chart::setColor();
				if (count($chartResult) > 0) {
					$i = 0;
					foreach ($chartResult as $re) : ?>
						<tr>
							<td>
								<div class="col-12" style="background-color:<?= $colors[$i] ?>;min-height:25px;">

								</div>
							</td>
							<td>
								<?= Chart::pieVacter($chart["chartId"], $i) ?>
							</td>
							<td>
								<?= $re["value"] ?>
							</td>
						</tr>
				<?php
						$i++;
					endforeach;
				}

				?>
			</tbody>
		</table>
	</div>
</div>