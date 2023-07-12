<?php

use frontend\models\lower_management\Country;
use yii\bootstrap4\ActiveForm;

$this->title = 'Branch';
?>

<div class="body-content pt-20">
	<div class="col-12 mt-10 text-left page-title">
		Branch
	</div>
	<div class="col-12">
		<table class="table table-hover ">
			<tr class="text-center table-head">
				<td>No.</td>
				<td>Branch / Company</td>
				<td>Country</td>
				<td>Action</td>

			</tr>
			<?php $form = ActiveForm::begin([
				'id' => 'login-form',
				'action' => Yii::$app->homeUrl . 'setting/structure/create-branch',
				'method' => 'post'
			]); ?>
			<tr>
				<td>
					<div class="text-success mt-10">
						<i class="fa fa-plus" aria-hidden="true"></i>
					</div>

				</td>
				<td class="border-right">
					<input type="text" name="branchName" class="form-control" placeholder="Branch Name" required>
				</td>
				<td class="border-right">
					<select class="form-control" name="countryId" required>
						<option value="">Country</option>
						<?php if (isset($countries) && count($countries) > 0) {
							foreach ($countries as $country) : ?>
								<option value="<?= $country["countryId"] ?>"><?= $country["countryName"] ?></option>
						<?php
							endforeach;
						} ?>
					</select>
				</td>
				<td class="text-center"><button class="button-blue button-md">Create</button></td>
			</tr>
			<?php ActiveForm::end(); ?>
			<?php
			if (isset($branches) && count($branches) > 0) {
				$i = 1;
				foreach ($branches as $branch) :
			?>
					<tr id="branch<?= $branch["branchId"] ?>">
						<td><?= $i ?></td>
						<td id="branchName<?= $branch["branchId"] ?>"><?= $branch["branchName"] ?></td>
						<td id="countryName<?= $branch["branchId"] ?>"><?= Country::countryName($branch["countryId"]) ?></td>
						<td class="text-center">
							<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $branch["branchId"] ?>)'>
								<i class="fa fa-edit" aria-hidden="true"></i>
							</button>
							<button class="button-red button-xs" onclick='javascript:disableBranch(<?= $branch["branchId"] ?>,0)'>
								<i class="fa fa-times" aria-hidden="true"></i>
							</button>
						</td>
					</tr>
					<tr id="tr-edit<?= $branch["branchId"] ?>" style="display:none;">
						<td>
							<div class="mt-10">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</div>
						</td>
						<td>
							<input type="text" id="branchNameInput<?= $branch["branchId"] ?>" class="form-control" placeholder="Branch Name" value="<?= $branch["branchName"] ?>">
						</td>
						<td>
							<select class="form-control" id="countryId<?= $branch["branchId"] ?>">
								<option value="<?= $branch["countryId"] ?>"><?= Country::countryName($branch["countryId"]) ?></option>
								<?php if (isset($countries) && count($countries) > 0) {
									foreach ($countries as $country) : ?>
										<option value="<?= $country["countryId"] ?>"><?= $country["countryName"] ?></option>
								<?php
									endforeach;
								} ?>
							</select>
						</td>
						<td class="text-center">
							<button class="button-green button-xs mt-10" onclick='javascript:updateBranch(<?= $branch["branchId"] ?>)'>
								<i class="fa fa-check" aria-hidden="true"></i>
							</button>
							<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $branch["branchId"] ?>)'>
								<i class="fa fa-minus" aria-hidden="true"></i>
							</button>
						</td>
					</tr>
				<?php
					$i++;
				endforeach;
			} else { ?>
				<tr class="tr-no-data">
					<td colspan="4">Not set</td>
				</tr>
			<?php
			}
			?>

		</table>
	</div>
</div>