<?php

use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Country;
use frontend\models\lower_management\Section;
use yii\bootstrap4\ActiveForm;

$this->title = 'Team';
?>

<div class="body-content pt-20">
	<div class="col-12 mt-10 text-left page-title">
		Dream Team
	</div>
	<div class="col-12">
		<table class="table table-hover ">
			<tr class="text-center table-head">
				<td>No.</td>
				<td>Branch</td>
				<td>Section</td>
				<td>Team</td>
				<td>Detail</td>
				<td>Action</td>

			</tr>
			<?php $form = ActiveForm::begin([
				'id' => 'team-form',
				'action' => Yii::$app->homeUrl . 'setting/structure/create-team',
				'method' => 'post'
			]); ?>
			<tr>
				<td>
					<div class="text-success mt-10">
						<i class="fa fa-plus" aria-hidden="true"></i>
					</div>

				</td>
				<td>
					<select name="branch" class="form-control" required="required" id="branchSearchSectionTeam" onchange="javascript:branchSectionTeam()">
						<option value="">Branch</option>
						<?php if (isset($branch) && count($branch) > 0) {
							foreach ($branch as $b) : ?>
								<option value="<?= $b["branchId"] ?>"><?= $b["branchName"] ?></option>
						<?php
							endforeach;
						} ?>
					</select>
				</td>
				<td class="border-right">
					<select class="form-control" id="sectionTeam" name="sectionId" required disabled>
						<option value="">Section</option>
					</select>
				</td>
				<td class="border-right">
					<input type="text" name="teamName" class="form-control" placeholder="Team Name" required>
				</td>
				<td class="border-right">
					<textarea name="teamDetail" class="form-control" placeholder="Description" style="height:40px;"></textarea>

				</td>

				<td class="text-center"><button class="button-blue button-md">Create</button></td>
			</tr>
			<?php ActiveForm::end(); ?>
			<tr class="tr-filter"><?= $this->render('filter_team', ["branch" => $branch]) ?></tr>
			<tbody id="team-search">
				<?php
				if (isset($teams) && count($teams) > 0) {
					$i = 1;
					foreach ($teams as $team) :
				?>
						<tr id="team<?= $team["teamId"] ?>">
							<td><?= $i ?></td>
							<td id="teamName<?= $team["teamId"] ?>"><?= Branch::branchName($team["branchId"]) ?></td>
							<td id="teamSection<?= $team["teamId"] ?>"><?= Section::sectionName($team["sectionId"]) ?></td>
							<td id="teamName<?= $team["teamId"] ?>"><?= $team["teamName"] ?></td>
							<td id="teamDetail<?= $team["teamId"] ?>"><?= $team["teamDetail"] ?></td>
							<td class="text-center">
								<button class="button-yellow button-xs" onclick='javascript:toggleTr(<?= $team["teamId"] ?>)'>
									<i class="fa fa-edit" aria-hidden="true"></i>
								</button>
								<button class="button-red button-xs" onclick='javascript:disableTeam(<?= $team["teamId"] ?>,0)'>
									<i class="fa fa-times" aria-hidden="true"></i>
								</button>
							</td>
						</tr>

						<tr id="tr-edit<?= $team["teamId"] ?>" style="display:none;">
							<td>
								<div class="mt-10">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</div>
							</td>
							<td>
								<select id="branchTeamInput<?= $team["teamId"] ?>" class="form-control" required="required" onchange="javascript:branchSectionEdit(<?= $team['teamId'] ?>)">
									<option value="<?= $team["branchId"] ?>"><?= Branch::branchName($team["branchId"]) ?></option>
									<?php if (isset($branch) && count($branch) > 0) {
										foreach ($branch as $b) : ?>
											<option value="<?= $b["branchId"] ?>"><?= $b["branchName"] ?></option>
									<?php
										endforeach;
									} ?>
								</select>
							</td>
							<td>
								<select class="form-control" id="sectionTeamInput<?= $team["teamId"] ?>" name="sectionId" disabled>
									<option value="">Section</option>
								</select>
							</td>
							<td>
								<input type="text" id="teamNameInput<?= $team["teamId"] ?>" class="form-control" value="<?= $team['teamName'] ?>">
							</td>
							<td>
								<textarea id="teamDetailInput<?= $team["teamId"] ?>" class="form-control" placeholder="Description" style="height:40px;"><?= $team["teamDetail"] ?></textarea>
							</td>

							<td class="text-center">
								<button class="button-green button-xs mt-10" onclick='javascript:updateTeam(<?= $team["teamId"] ?>)'>
									<i class="fa fa-check" aria-hidden="true"></i>
								</button>
								<button class="button-red button-xs mt-10" onclick='javascript:toggleTr(<?= $team["teamId"] ?>)'>
									<i class="fa fa-minus" aria-hidden="true"></i>
								</button>
							</td>
						</tr>
					<?php
						$i++;
					endforeach;
				} else { ?>
					<tr class="tr-no-data">
						<td colspan="6">Not set</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>