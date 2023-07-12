<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;

$this->title = 'Client';
?>
<div class="body-content pt-20">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-3 mt-10 text-left page-title">
			Client
		</div>
		<div class="col-lg-4 col-md-3 col-6 mt-10 text-right">
			<input type="text" id="search-client" placeholder="Search Client" class="form-control" onkeyup="javascript:filterClient();">
		</div>
		<div class="col-lg-2 col-md-2 col-3 mt-10 text-right">
			<select id="branch-client" class="form-control" onchange="javascript:filterClient();">
				<option value="">Branch</option>
				<?php
				if (isset($branches) && count($branches) > 0) {
					foreach ($branches as $branch) : ?>
						<option value="<?= $branch["branchId"] ?>"><?= $branch["branchName"] ?></option>
				<?php
					endforeach;
				}
				?>
			</select>
		</div>
		<div class="col-lg-4 col-md-5 col-12 mt-10 mb-10 page-list-menu text-right">
			<a href="<?= Yii::$app->homeUrl ?>client/default/import-client" class="btn button-blue mr-20">
				<i class="fa fa-upload mr-1" aria-hidden="true"></i> Uplode client file
			</a>
			<a href="<?= Yii::$app->homeUrl ?>client/default/create-client" class="btn button-green">
				<i class="fa fa-plus-square mr-1" aria-hidden="true"></i> Create new client
			</a>
		</div>
	</div>
	<div class="col-12">
		<table class="table table-hover">
			<tr class="text-left table-head">
				<td>#</td>
				<td>Name</td>
				<td>Branch</td>
				<td>Email</td>
				<td>Tel</td>
				<td>Remark</td>
				<td>Action</td>
			</tr>
			<tbody id="client-result">
				<?php
				if (isset($clients) && count($clients) > 0) {
					$i = 1;
					foreach ($clients as $client) :
				?>
						<tr id="client<?= $client["clientId"] ?>">
							<td><?= $i ?></td>
							<td><?= $client["clientName"] ?></td>
							<td><?= Branch::branchName($client["branchId"]) ?></td>
							<td><?= $client["email"] ?></td>
							<td><?= $client["clientTel1"] ?></td>
							<td><?= $client["remark"] ?></td>
							<td>
								<a href="<?= Yii::$app->homeUrl ?>client/default/client-detail/<?= ModelMaster::encodeParams(["clientId" => $client["clientId"]]) ?>" class="btn button-turqouise button-xs">
									<i class="fa fa-info" aria-hidden="true"></i>
								</a>
								<a href="<?= Yii::$app->homeUrl ?>client/default/update-client/<?= ModelMaster::encodeParams(["clientId" => $client["clientId"]]) ?>" class="btn button-yellow button-xs">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a>
								<a class="btn button-red button-xs" onclick=' javascript:disableClient(<?= $client["clientId"] ?>)'>
									<i class="fa fa-times" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
					<?php
						$i++;
					endforeach;
				} else { ?>
					<tr class="tr-no-data">
						<td colspan="7">Not set</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

</div>