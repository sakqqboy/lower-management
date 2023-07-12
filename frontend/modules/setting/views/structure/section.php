<?php

use frontend\models\lower_management\Branch;
use yii\bootstrap4\ActiveForm;

$this->title = 'Section';
?>
<div class="body-content pt-20">
	<div class="col-12 mt-10 text-left page-title">
		Section
	</div>
	<div class="col-12 text-right">
		Ex. Accounting, IT, Legal, ..
	</div>

	<div class="col-12">
		<table class="table table-hover" style="border-bottom: 1px !important;">
			<tr class="text-center table-head">
				<td>No.</td>
				<td>Branch</td>
				<td>Section</td>
				<td>Position</td>
				<td>Action</td>

			</tr>
			<?php $form = ActiveForm::begin([
				'id' => 'section-form',
				'action' => Yii::$app->homeUrl . 'setting/structure/create-section',

				'options' => [
					//'class' => 'panel panel-default form-horizontal',
					'enctype' => 'multipart/form-data',
					'method' => 'POST'
				],
			]); ?>
			<tr>
				<td>
					<div class="text-success mt-10">
						<i class="fa fa-plus" aria-hidden="true"></i>
					</div>
				</td>
				<td class="border-right">
					<select name="branch" class="form-control" required="required" id="branch-section" onchange="javascript:sectionPosition()">
						<option value="">Branch</option>
						<?php
						if (isset($branch) && count($branch) > 0) {
							foreach ($branch as $b) : ?>
								<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
						<?php
							endforeach;
						}
						?>
					</select>

				</td>
				<td class="border-right">
					<input type="text" name="sectionName" class="form-control" placeholder="Section Name" required>

				</td>
				<td class="border-right" id="section-position">
					<?php
					if (isset($position) && count($position) > 0) {
						foreach ($position as $p) : ?>
							<div class="col-12" id="position<?= $p["positionId"] ?>" style="display:none;">
								<input type="checkbox" id="position-input<?= $p["positionId"] ?>" name="position[]" class="form-check-inline" value="<?= $p["positionId"] ?>">
								<label class="label-input"><?= $p["positionName"] ?></label><br>
							</div>
					<?php endforeach;
					}
					?>
				</td>
				<td class="text-center">
					<button class="button-blue button-md" type="submit">Create</button>
				</td>
			</tr>
			<?php ActiveForm::end(); ?>
			<tr class="tr-filter"><?= $this->render('filter_section', ["branch" => $branch]) ?></tr>
			<tbody id="section-search">
				<?php
				if (isset($sections) && count($sections) > 0) {
					$i = 1;
					foreach ($sections as $section) :
				?>
						<tr id="section<?= $section["sectionId"] ?>">
							<td><?= $i ?></td>
							<td id="sectionBranch<?= $section["sectionId"] ?>"><?= Branch::branchName($section["branchId"]) ?></td>
							<td id="sectionName<?= $section["sectionId"] ?>"><?= $section["sectionName"] ?></td>

							<td id="sectionPosition<?= $section["sectionId"] ?>">
								<?php
								if (isset($sp[$section["sectionId"]]) && count($sp[$section["sectionId"]]) > 0) {
									$j = 1;
									foreach ($sp[$section["sectionId"]] as $positioinId => $p) :
										echo $j . ".  " . $p . "<br>";
										$j++;
									endforeach;
								}
								?>
							</td>
							<td class="text-center">
								<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $section["sectionId"] ?>)'>
									<i class="fa fa-edit" aria-hidden="true"></i>
								</button>
								<button class="button-red button-xs" onclick='javascript:disableSection(<?= $section["sectionId"] ?>,0)'>
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
							</td>
						</tr>

						<tr id="tr-edit<?= $section["sectionId"] ?>" style="display:none;">
							<td>
								<div class="mt-10">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</div>
							</td>
							<td>
								<select id="branchInput<?= $section["sectionId"] ?>" class="form-control" onchange="javascript:sectionPositionInput(<?= $section['sectionId'] ?>)">
									<option value="<?= $section["branchId"] ?>"><?= Branch::branchName($section["branchId"]) ?></option>
									<?php
									if (isset($branch) && count($branch) > 0) {
										foreach ($branch as $b) : ?>
											<option value="<?= $b['branchId'] ?>"><?= $b['branchName'] ?></option>
									<?php
										endforeach;
									}
									?>
								</select>


							</td>
							<td>
								<input type="text" id="sectionNameInput<?= $section["sectionId"] ?>" class="form-control" placeholder="Section Name" value="<?= $section["sectionName"] ?>">
							</td>

							<td id="section-position-edit">

								<?php
								if (isset($position) && count($position) > 0) {
									foreach ($position as $p) :
										$show = 0;
										$checked = '';
										if (isset($positionBranch[$section["branchId"]][$p["positionId"]])) {
											$show = 1;
										}
										if (isset($sp[$section["sectionId"]][$p["positionId"]])) {
											$checked = 'checked';
										}
								?>
										<div class="col-12" id="positionEdit<?= $section["sectionId"] ?>l<?= $p["positionId"] ?>" style="display:<?= $show == 1 ? '' : 'none' ?>">
											<input type="checkbox" id="position-input<?= $section["sectionId"] ?>l<?= $p["positionId"] ?>" name="position<?= $section["sectionId"] ?>" class="form-check-inline" value="<?= $p["positionId"] ?>" <?= $checked ?>>
											<label class="label-input"><?= $p["positionName"] ?></label><br>
										</div>
								<?php endforeach;
								}
								?>
							</td>
							<td class="text-center">
								<button class="button-green button-xs mt-10" onclick='javascript:updateSection(<?= $section["sectionId"] ?>)'>
									<i class="fa fa-check" aria-hidden="true"></i>
								</button>
								<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $section["sectionId"] ?>)'>
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
			</tbody>
		</table>
	</div>
</div>