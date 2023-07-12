<?php
$this->title = 'KGI setting';

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi;
use frontend\models\lower_management\TeamPosition;
?>
<div class="body-content container">
	<div class="row" style="margin-top: -20px;">
		<div class="col-12">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-12 font-weight-bold font-size26">KGI SETTING</div>
				<div class="col-lg-9 col-md-9 col-12  pr-0 text-right">
					<a href="<?= Yii::$app->homeUrl ?>kpi/employee-kpi/employee" class="btn button-turqouise">
						<i class="fa fa-cogs mr-2" aria-hidden="true"></i>
						Employee KPI
					</a>
					<a href="<?= Yii::$app->homeUrl ?>kpi/default/create-kgi-group" class="btn button-blue">
						<i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>
						Create KGI Group
					</a>
					<a href="<?= Yii::$app->homeUrl ?>kpi/default/create-kgi" class="btn button-green">
						<i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>
						Create new KGI
					</a>
				</div>
			</div>
		</div>
		<div class="col-12 text-right mt-10">
			<div class="row">
				<div class="col-lg-3 col-md-4 col-6 ">
					<select class="form-control" required name="branch" id="branch-kgi-search">
						<option value="">Select Branch</option>
						<?php
						if (isset($branch) && count($branch) > 0) {
							foreach ($branch as $b) : ?>
								<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
						<?php
							endforeach;
						}
						?>
					</select>
				</div>
				<div class="col-lg-3 col-md-4 col-6">
					<select class="form-control" required id="dreamTeam-kgi" onchange="javascript:filterKgi()">
						<option value="">Dream Team</option>

					</select>
				</div>
				<div class="col-lg-3 col-md-4 col-6">
					<select class="form-control" required name="teamPosition" id="teamPosition" onchange="javascript:filterKgi()">
						<option value="">Team Position</option>
						<option value="1">Captain</option>
						<option value="2">Sub Captain</option>
						<option value="3">Staff</option>
					</select>
				</div>
				<div class="col-lg-3 col-md-4 col-6">
					<select class="form-control" required id="kgi-group" onchange="javascript:filterKgi()">
						<option value="">KGI Group</option>
						<?php
						if (isset($kgiGroups) && count($kgiGroups) > 0) {
							foreach ($kgiGroups as $group) : ?>
								<option value="<?= $group['kgiGroupId'] ?>"><?= $group['kgiGroupName'] ?></option>
						<?php

							endforeach;
						}
						?>

					</select>
				</div>
			</div>
		</div>
		<div class="col-12 mt-20">
			<?php
			if (isset($kgi) && count($kgi) > 0) { ?>
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>Branch</th>
							<th>Title</th>
							<th>Unit</th>
							<th>Target Amount</th>
							<th>Position</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody id="kgi-search-result">
						<?php
						$i = 1;
						$symbol = '';
						foreach ($kgi as $k) :
							if ($k["symbolCheck"] == 1) {
								$symbol = '>';
							}
							if ($k["symbolCheck"] == 2) {
								$symbol = '<';
							}
							if ($k["symbolCheck"] == 3) {
								$symbol = '=';
							}
						?>
							<tr id="kgi-<?= $k['kgiId'] ?>">
								<td><?= $i ?></td>
								<td><?= Branch::branchName($k["branchId"]) ?></td>
								<td><?= $k["kgiName"] ?></td>
								<td><?= Kgi::unitName($k["unit"]) ?></td>
								<td><?= $symbol ?> <?= number_format($k["targetAmount"], 2) ?></td>
								<td><?= TeamPosition::positionName($k["teamPositionId"]); ?></td>
								<td class="font-size14 text-center">
									<a href="<?= Yii::$app->homeUrl ?>kpi/default/kgi-detail/<?= ModelMaster::encodeParams(["kgiId" => $k['kgiId']]) ?>" class="btn button-sky button-xs">
										<i class="fa fa-eye" aria-hidden="true"></i>
									</a>
									<a href="<?= Yii::$app->homeUrl ?>kpi/default/update-kgi/<?= ModelMaster::encodeParams(["kgiId" => $k['kgiId']]) ?>" class="btn button-yellow button-xs">
										<i class="fa fa-edit" aria-hidden="true"></i>
									</a>
									<a href="<?= Yii::$app->homeUrl ?>kpi/default/create-kpi/<?= ModelMaster::encodeParams(["kgiId" => $k['kgiId']]) ?>" class="btn button-blue button-xs">
										<i class="fa fa-plus-circle mr-1" aria-hidden="true"></i>
										KPI
									</a>
									<a href="javascript:disableKgi(<?= $k['kgiId'] ?>)" class="btn button-red button-xs font-size14">
										<i class="fa fa-trash" aria-hidden="true"></i>
									</a>
								</td>
							</tr>

						<?php
							$i++;
						endforeach;
						?>
					</tbody>
				</table>

			<?php
			} else { ?>
				<div class="col-12 text-center font-size22 font-weight-bold" style="color:gray;">
					> > > Let start KGI setting < < < </div>

					<?php
				}
					?>

				</div>
		</div>
	</div>