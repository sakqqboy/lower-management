<?php
if (isset($positions) && count($positions) > 0) {
	$i = 1;
	foreach ($positions as $position) :
?>
		<tr id="position<?= $position["positionId"] ?>">
			<td><?= $i ?></td>
			<td id="positionName<?= $position["positionId"] ?>"><?= $position["branchName"] ?></td>
			<td id="positionDetail<?= $position["positionId"] ?>"><?= $position["positionName"] ?></td>
			<td id="positionLevel<?= $position["positionId"] ?>"><?= $position["positionDetail"] ?></td>
			<td class="text-center">
				<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $position["positionId"] ?>)'>
					<i class="fa fa-edit" aria-hidden="true"></i>
				</button>
				<button class="button-red button-xs" onclick='javascript:disablePosition(<?= $position["positionId"] ?>,0)'>
					<i class="fa fa-times" aria-hidden="true"></i>
				</button>
			</td>
		</tr>
		<tr id="tr-edit<?= $position["positionId"] ?>" class="tr-edit" style="display:none;">
			<td>
				<div class="mt-10">
					<i class="fa fa-edit" aria-hidden="true"></i>
				</div>
			</td>
			<td>
				<?php
				if (isset($branch) && count($branch) > 0) { ?>
					<select id="branchInput<?= $position["positionId"] ?>" class="form-control">
						<option value="<?= $position["branchId"] ?>"><?= $position["branchName"] ?></option>
						<?php
						foreach ($branch as $b) : ?>
							<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
						<?php
						endforeach;
						?>
					</select><?php
						} else {
							$disable = 'disabled';
							?>
					<div class="no-underline font-size16 mt-10"><a href="<?= Yii::$app->homeUrl ?>setting/structure/branch"> Create Branch</a></div>
				<?php }
				?>
			</td>
			<td>
				<input type="text" id="positionNameInput<?= $position["positionId"] ?>" class="form-control" placeholder="Position Name" value="<?= $position["positionName"] ?>">
			</td>
			<td>
				<textarea id="positionDetailInput<?= $position["positionId"] ?>" class="form-control" placeholder="Description" style="height:40px;"><?= $position["positionDetail"] ?></textarea>
			</td>
			<td class="text-center">
				<button class="button-green button-xs mt-10" onclick='javascript:updatePosition(<?= $position["positionId"] ?>)'>
					<i class="fa fa-check" aria-hidden="true"></i>
				</button>
				<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $position["positionId"] ?>)'>
					<i class="fa fa-minus" aria-hidden="true"></i>
				</button>
			</td>
		</tr>
	<?php
		$i++;
	endforeach;
} else { ?>
	<tr class="tr-no-data">
		<td colspan="5">Not set</td>
	</tr>
<?php
}
?>