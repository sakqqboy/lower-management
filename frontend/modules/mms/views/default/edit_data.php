<table class="table mb-20">
	<thead>
		<tr class="text-center">
			<th class="">Title</th>
			<?php
			foreach ($xData as $column) : ?>
				<th class=""><?= $column ?></th>
			<?php
			endforeach;
			?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="border pt-10 pb-10">
				<input type="text" name="formula" value="<?= $chart["formula"] ?>" class="form-control">

			</td>
			<?php
			$r = 0;
			while ($r < count($xData)) { ?>
				<td class="border text-right pr-1 formular-result" id="column-<?= $r ?>">
					<?php
					if (isset($chartResult) && count($chartResult) > 0) {
						foreach ($chartResult as $result) :
							if ($result["index"] == $r) {
								echo $result["value"];
							}
						endforeach;
					}
					?>
				</td>
				<input type="hidden" name="result[<?= $r ?>]" value="" id="result-<?= $r ?>">
			<?php
				$r++;
			}
			?>
			<input type="hidden" id="totalColumn" value="<?= count($xData) ?>">
		</tr>
		<?php
		$i = 1;
		$cell = 1;
		while ($i <= 5) {
			$j = 0;
		?>
			<tr style="height: 50px;">
				<td class="border">
					<?php
					if (isset($chartData) && count($chartData) > 0) {
						foreach ($chartData as $data) :
							if ($data["row"] == $i && $data["index"] == $j) { ?>
								<input type="text" name="row[<?= $i ?>]" value="<?= $data["rowName"] ?>" class="form-control">

					<?php
							}
						endforeach;
					}

					?>

				</td>
				<?php
				throw new Exception(print_r($chartData, true));
				while ($j < count($xData)) { ?>
					<td class="text-right border" style="padding-right:5px;">
						<?php
						if (isset($chartData) && count($chartData) > 0) {
							$data["value"] = null;
							foreach ($chartData as $data) :
								if (isset($data))
									if ($data["row"] == $i && $data["index"] == $j) { ?>
									<input type="text" name="dataRow[<?= $i ?>][<?= $j ?>]" value="<?= $data["value"] ?>" class="form-control text-right">
						<?php
									}
							endforeach;
						}
						?>
						<!-- <input type="text" class="text-right formular-input" name="dataRow[<?php //$cell 
																	?>][<?php //$j 
																		?>]" style="width: 100px;" id="row<?= $cell ?>-<?= $j ?>"> -->
					</td>
				<?php
					$j++;
				}
				?>
			</tr>
		<?php
			$i++;
			$cell++;
		}
		?>

	</tbody>
</table>