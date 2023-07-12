<?php

use common\models\ModelMaster;
use yii\bootstrap4\ActiveForm;

$this->title = 'Create KGI Group';

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kpi',

	],

]);
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 border create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class="col-12 text-center font-size24 font-weight-bold">
				KGI Group
			</div>
			<div class="col-12 mt-10">
				<input type="text" name="kgiGroupName" class="form-control" placeholder="KGI GroupName" required>
			</div>
			<div class="col-12">
				<div class="row">
					<div class="col-6 mt-10 text-left">
						<a href="<?= Yii::$app->homeUrl ?>kpi/default/index" class="btn button-blue">
							<i class="fa fa-chevron-left mr-2 mr-2" aria-hidden="true"></i>Back
						</a>
					</div>
					<div class="col-6 mt-10 text-right">
						<button type="submit" class="btn button-blue">
							<i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Submit
						</button>
					</div>
				</div>
			</div>
			<?php
			if (isset($kgiGroups) && count($kgiGroups) > 0) { ?>
				<div class="col-12 mt-10 font-size14">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>KGI Group Name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							foreach ($kgiGroups as $kgiGroup) : ?>
								<tr id="kgi-group-<?= $kgiGroup['kgiGroupId'] ?>">
									<td><?= $i ?></td>
									<td><?= $kgiGroup["kgiGroupName"] ?></td>
									<td>
										<a href="<?= Yii::$app->homeUrl ?>kpi/default/update-kgi-group/<?= ModelMaster::encodeParams(["kgiGroupId" => $kgiGroup['kgiGroupId']]) ?>" class="btn button-yellow button-xs">
											<i class="fa fa-edit" aria-hidden="true"></i>
										</a>
										<a href="javascript:disableKgiGroup(<?= $kgiGroup['kgiGroupId'] ?>)" class="btn button-red button-xs font-size14">
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
<?php ActiveForm::end(); ?>