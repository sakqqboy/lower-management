<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi;
use frontend\models\lower_management\KgiGroup;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use yii\bootstrap4\ActiveForm;

$this->title = 'Update KGI';

$form = ActiveForm::begin([
	'options' => [
		'class' => 'panel panel-default form-horizontal',
		'enctype' => 'multipart/form-data',
		'id' => 'kgi',

	],
	'action' => Yii::$app->homeUrl . 'kpi/default/save-update-kgi'

]);
?>
<div class="body-content container">
	<div class="row">
		<div class="col-12 border create-empolyee-box pt-40 pb-40 mt-40" style="border-radius: 10px;">
			<div class="col-12 text-center font-size24 font-weight-bold">
				Update KGI : <?= $kgi["kgiName"] ?>
			</div>
			<div class="col-12 mt-20">
				<div class="row">
					<div class="col-lg-12 mb-20">
						<input type="text" name="kgiName" class="form-control font-weight-bold" placeholder="KGI name" value="<?= $kgi["kgiName"] ?>" required>
					</div>
					<div class="col-lg-3">
						<select class="form-control" required name="kgiGroup">
							<option value="<?= $kgi["kgiGroupId"] ?>"><?= KgiGroup::kgiGroupName($kgi["kgiGroupId"]) ?></option>
							<?php
							if (isset($kgiGroups) && count($kgiGroups) > 0) {
								foreach ($kgiGroups as $kgiGroup) : ?>
									<option value="<?= $kgiGroup['kgiGroupId'] ?>"><?= $kgiGroup['kgiGroupName'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" required name="branch" id="branch-kgi">
							<option value="<?= $kgi["branchId"] ?>"><?= Branch::branchName($kgi["branchId"]) ?></option>
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
					<div class="col-lg-3">
						<select class="form-control" required name="team" id="dreamTeam-kgi">
							<option value="<?= $kgi["teamId"] ?>"><?= Team::teamName($kgi["teamId"]) ?></option>
							<?php
							if (isset($teams) && count($teams) > 0) {
								foreach ($teams as $team) : ?>
									<option value="<?= $team['teamId'] ?>"><?= Team::teamName($team["teamId"]) ?></option>
							<?php
								endforeach;
							}
							?>
							<option value="">Dream Team</option>

						</select>
					</div>
					<div class="col-lg-3">
						<select class="form-control" required name="teamPosition">
							<option value="<?= $kgi["teamPositionId"] ?>"><?= TeamPosition::positionName($kgi["teamPositionId"]) ?></option>
							<option value="1">Captain</option>
							<option value="2">Sub Captain</option>
							<option value="3">Staff</option>
						</select>
					</div>

				</div>
			</div>
			<div class="col-12 mt-20">
				<span class="label-input ml-1">KGI detail</span>
				<textarea class="form-control" style="height:120px;" name="kgiDetail"><?= $kgi["kgiDetail"] ?></textarea>
			</div>
			<div class="col-12 mt-20">
				<div class="row">
					<div class="col-lg-3">
						<span class="label-input ml-1">Unit</span>
						<select class="form-control" required name="unit">
							<option value="<?= $kgi["unit"] ?>"><?= Kgi::unitName($kgi["unit"]) ?></option>
							<?php
							if (isset($kgiUnit) && count($kgiUnit) > 0) {
								foreach ($kgiUnit as $unit) : ?>
									<option value="<?= $unit['kgiUnitId'] ?>"><?= $unit['name'] ?></option>
							<?php
								endforeach;
							}
							?>
						</select>
					</div>
					<div class="col-lg-3">
						<span class="label-input ml-1">Check</span>
						<?php
						if ($kgi["symbolCheck"] == 1) {
							$check = '>';
						}
						if ($kgi["symbolCheck"] == 2) {
							$check = '<';
						}
						if ($kgi["symbolCheck"] == 3) {
							$check = '=';
						}
						if ($kgi["amountType"] == 2) {
							$percent = '%';
						} else {
							$percent = 'Number';
						}
						?>
						<select class="form-control" required name="check">
							<option value="<?= $kgi['symbolCheck'] ?>"><?= $check ?></option>
							<option value="1"><b> > </b>
							</option>
							<option value="2"><b>
									< </b>
							</option>
							<option value="3"><b> = </b></option>
						</select>
					</div>
					<div class="col-lg-3">
						<span class="label-input ml-1">Target amount</span>
						<input type="number" class="form-control text-right" min="0" name="targetAmount" required value="<?= $kgi["targetAmount"] ?>">
					</div>
					<div class="col-lg-3">
						<span class="label-input ml-1">Amount Type</span>
						<select class="form-control" name="type" required>
							<option value="<?= $kgi["amountType"] ?>"><?= $percent ?></option>
							<option value="1">Number</option>
							<option value="2">%</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-12 text-right mt-20">
				<a href="<?= Yii::$app->homeUrl ?>kpi/default/index" class="btn btn-outline-secondary mr-20">
					<i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back
				</a>
				<input type="hidden" name="kgiId" value="<?= $kgi["kgiId"] ?>">
				<button type="submit" class="btn button-yellow">
					<i class="fa fa-edit mr-2" aria-hidden="true"></i>Update
				</button>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>