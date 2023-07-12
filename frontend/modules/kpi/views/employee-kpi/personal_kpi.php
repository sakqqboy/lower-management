<?php



$this->title = 'Personal KPI';

use common\models\ModelMaster;
?>
<div class="body-content pt-20">
	<div class="col-12">
		<div class="col-12 font-size24"><b>"<?= $employeeName ?>"</b> &nbsp;&nbsp;&nbsp;K P I Lists</div>

		<div class="col-12 mt-10">
			<?php
			if (count($kpi) > 0) {
			?>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Kpi</th>
							<th>Unit</th>
							<th>Target Amount</th>
							<th>Pernal Amount</th>
							<th>Achieved</th>
							<th>Update</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($kpi) && count($kpi) > 0) {
							$i = 1;
							foreach ($kpi as $pkpiId => $data) : ?>
								<tr>
									<td>
										<b><?= $i ?>.&nbsp;

											<?= $data["kpiName"] ?>
										</b>
										<div class="col-12 font-size14 mt-10"><?= $data["detail"] ?></div>
									</td>
									<td><?= $data["unit"] ?></td>
									<td><?= $data["targetAmount"] ?></td>
									<td>
										<input type="number" value="<?= $data["personalAmount"] ?>" id="personalKpi-<?= $pkpiId ?>" class="text-right form-control">
									</td>
									<td>0.00%</td>
									<td><a href="javascript:saveUpdatePersonalKpi(<?= $pkpiId ?>)" class="btn button-yellow button-xs mr-2">
											<i class="fa fa-edit" aria-hidden="true"></i>
										</a>
										<i class="fa fa-check text-success" aria-hidden="true" style="display:none;" id="check-<?= $pkpiId ?>"></i>
									</td>
								</tr>


						<?php
								$i++;
							endforeach;
						}
						?>
					</tbody>
				</table>
			<?php
			} else { ?>
				<div class="col-12 text-center">
					<h3>No KPI match with this emplyee's position.</h3>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div>