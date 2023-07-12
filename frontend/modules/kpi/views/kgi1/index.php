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
				KGI 1 List
			</div>
			<div class="col-12 text-right">
				<a href="<?= Yii::$app->homeUrl ?>kpi/kgi1/create-kgi1" class="btn button-green">
					<i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>
					Create new KGI <b>1</b>
				</a>
			</div>
			<?php
			if (isset($kgi1) && count($kgi1) > 0) { ?>
				<div class="col-12 mt-10 font-size14">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Branch</th>
								<th>Content</th>
								<th class="text-center">Code</th>
								<th class="text-center">Target Amount</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach ($kgi1 as $kgi) :

								if ($kgi["code"] == 1) {
									$code = '>';
								}
								if ($kgi["code"] == 2) {
									$code = '<';
								}
								if ($kgi["code"] == 3) {
									$code = '=';
								}
							?>
								<tr id="kgi1-<?= $kgi['kgi1Id'] ?>">
									<td><?= $i ?></td>
									<td><?= Branch::branchName($kgi["branchId"]) ?></td>
									<td><?= $kgi["kgi1Name"] ?></td>
									<td class="text-center"><?= $code ?></td>
									<td class="text-right"><?= number_format($kgi["targetAmount"], 2) ?></td>
									<td class="text-center">
										<a href="<?= Yii::$app->homeUrl ?>kpi/kgi1/kgi1-view/<?= ModelMaster::encodeParams(["kgi1Id" => $kgi['kgi1Id']]) ?>" class="btn button-sky button-xs">
											<i class="fa fa-eye" aria-hidden="true"></i>
										</a>
										<a href="<?= Yii::$app->homeUrl ?>kpi/kgi1/update-kgi1/<?= ModelMaster::encodeParams(["kgi1Id" => $kgi['kgi1Id']]) ?>" class="btn button-yellow button-xs">
											<i class="fa fa-edit" aria-hidden="true"></i>
										</a>
										<a href="javascript:disableKgi1(<?= $kgi['kgi1Id'] ?>)" class="btn button-red button-xs font-size14">
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