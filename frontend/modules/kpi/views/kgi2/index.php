<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use yii\bootstrap4\ActiveForm;

$this->title = 'KGI 1';
?>
<div class="mt-40">
	<div class="row">
		<div class="col-12  pt-40 pb-40 mt-40">
			<div class="col-12 text-center font-size24 font-weight-bold">
				KGI 2 List
			</div>
			<div class="col-12 text-right">
				<a href="<?= Yii::$app->homeUrl ?>kpi/kgi2/create-kgi2" class="btn button-green">
					<i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>
					Create new KGI <b>2</b>
				</a>
			</div>
			<?php
			if (isset($data) && count($data) > 0) { ?>
				<div class="col-12 mt-10 font-size14">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Branch</th>
								<th>Content</th>
								<th>Dream Team</th>
								<th>Position</th>
								<th class="text-center">Code</th>
								<th>Target Amount</th>
								<th>Actual Amount</th>
								<th>Achive ratio</th>
								<th>Relate Main KGI1</th>
								<th>KGI 1</th>
								<th>KPI</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach ($data as $kgi2Id => $kgi) :


							?>
								<tr id="kgi2-<?= $kgi2Id ?>">
									<td><?= $i ?></td>
									<td><?= $kgi["branch"] ?></td>
									<td><?= $kgi["content"] ?></td>
									<td><?= $kgi["team"] ?></td>
									<td><?= $kgi["position"] ?></td>
									<td><?= $kgi["code"] ?></td>
									<td><?= $kgi["targetAmount"] ?></td>
									<td><?= $kgi["actualAmount"] ?></td>
									<td><?= $kgi["achiveRatio"] ?></td>
									<td><?= $kgi["main"] ?></td>
									<td><?= $kgi["kgi1"] ?></td>
									<td><?= $kgi["kpi"] ?></td>
									<td class="text-center">
										<a href="<?= Yii::$app->homeUrl ?>kpi/kgi2/kgi2-view/<?= ModelMaster::encodeParams(["kgi2Id" => $kgi2Id]) ?>" class="btn button-sky button-xs">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
										<a href="<?= Yii::$app->homeUrl ?>kpi/kgi2/update-kgi1/<?= ModelMaster::encodeParams(["kgi2Id" => $kgi2Id]) ?>" class="btn button-yellow button-xs">
											<i class="fa fa-edit" aria-hidden="true"></i>
										</a>
										<a href="javascript:disableKgi1(<?= $kgi2Id ?>)" class="btn button-red button-xs font-size14">
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
				</div><?php
				}
					?>
		</div>
	</div>
</div>