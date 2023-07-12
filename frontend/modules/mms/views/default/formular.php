<table class="mb-20">
	<thead>
		<tr class="text-center">
			<th class="td-width-150">Title</th>
			<?php
			foreach ($format as $input) : ?>
				<th class="td-width-150"><?= $input ?></th>
			<?php
			endforeach;
			?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="border pt-10 pb-10 text-center">
				Result
			</td>
			<?php
			$r = 0;
			while ($r < count($format)) { ?>
				<td class="border formular-input  text-right pr-1 formular-result" id="column-<?= $r ?>">

				</td>
				<input type="hidden" name="result[<?= $r ?>]" value="" id="result-<?= $r ?>">
			<?php
				$r++;
			}
			?>
			<input type="hidden" id="totalColumn" value="<?= count($format) ?>">
		</tr>
		<?php
		$i = 0;
		$cell = 1;
		while ($i < 5) {
			$j = 0;
		?>
			<tr>
				<td>
					<input type="text" class="formular-input td-width-120" placeholder="r<?= $cell ?>" name="row[<?= $cell ?>]">
				</td>
				<?php
				while ($j < count($format)) { ?>
					<td class="td-width-70">
						<input type="text" class="text-right formular-input" name="dataRow[<?= $cell ?>][<?= $j ?>]" style="width: 100px;" id="row<?= $cell ?>-<?= $j ?>">
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
	<tbody id="more-row">
	</tbody>
	<tr>
		<td colspan="4" class="pt-10 pb-10">
			<input type="text" class="form-control" placeholder="Formular" id="formula-text" name="formula">
		</td>
		<td>
			<div class="button-sky text-center form-control calculate-button" onclick="javascript:caculateGraph(0)">
				<i class="fa fa-calculator" aria-hidden="true"></i>
			</div>
		</td>
		<td colspan="3" class="pt-10 pb-10">
			<input type="text" class="form-control" placeholder="Result name" id="result-name" name="resultName">
		</td>
		<td class="pt-10 pb-10">
			<button type="submit" class="button-blue text-center form-control calculate-button" id="save-cal">
				<i class="fa fa-download mr-10" aria-hidden="true"></i></i> <b>Save</b>
			</button>
		</td>
		<td>
			<a href="javascript:addRow(<?= count($format) ?>)">
				<img src="<?= Yii::$app->homeUrl ?>images/icon/plus.png" class="header-icon">
				<input type="hidden" value="5" id="last-row">
			</a>
		</td>
	</tr>
	</tbody>
</table>