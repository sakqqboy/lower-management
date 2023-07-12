<?php

use common\carlendar\Carlendar;
?>
<table class="table table-bordered ">
	<thead class="font-size12 ">
		<th>M</th>
		<th>T</th>
		<th>W</th>
		<th>Th</th>
		<th>F</th>
		<th>S</th>
		<th>Su</th>
	</thead>
	<tbody class="font-size12 text-center">
		<?php
		$date = $year . '-' . $month . '-01';
		$dateValue = Carlendar::currentMonth($date);
		$totalCount = 0;
		$day = 1;
		$other = '';
		foreach ($dateValue as $index => $value) :
			$dateArr = explode('-', $value["date"]);
			$day = (int)$dateArr[2];
			$thisMonth = $dateArr[1];
			$year = $dateArr[0];
			$other = '';
			if ((int)$thisMonth != (int)$month) {
				$other = "other-month";
			} else {
				$other = '';
			}
			if (($totalCount % 7) == 0) { ?>
				<tr>
				<?php
			}
				?>
				<td class="<?= $other ?>">
					<?= $day ?>

				</td>
				<?php
				$totalCount++;
				if (($totalCount % 7) == 0) { ?>
				</tr>
		<?php
				}
			endforeach;
		?>

	</tbody>
</table>