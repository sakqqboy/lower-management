<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobResponsibility;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\ReadChat;
use frontend\models\lower_management\SubmitReport;
use frontend\models\lower_management\Team;
use kartik\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Jobs';
?>
<div class="body-content pt-20">
	<div class="col-12 pt-20 filter-row" id="filter-list">
		<?= $this->render('filter', [
			"branch" => $branch,
			"category" => $category,
			"fields" => $fields,
			"branchId" => $branchId,
			"teams" => $teams,
			"teamId" => $teamId,
			"persons" => $persons,
			"client" => null,
			"groupFields" => $groupFields,
			"jobType" => $jobType
		]) ?>
	</div>
	<div class="spinner-border loading">
	</div>
	<div class="col-12 mt-20">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-6">
				<select class="form-control" id="show-carlendar">
					<option value="1">List</option>
					<option value="2">Schedule</option>
				</select>
			</div>
			<div class="col-2 pt-10 ">
				<input type="checkbox" class="checkbox-sm" id="report"> Business report jobs
			</div>
			<div class="col-3 pt-10">
				<div class="row">
					<div class="col-4 font-size18"><i class='fa fa-file text-success mr-10 ' aria-hidden='true'></i><?= $submit ?></div>
					<div class="col-8 font-size18"><i class='fa fa-file text-danger mr-10' aria-hidden='true'></i> <?= $notSubmit ?></div>
				</div>
			</div>
			<?= $this->render('alert') ?>
			<div class="col-3 text-right pt-10">
				<span class="box-sort" onclick="javascript:sortStepDue()">Step due
					<img src="<?= Yii::$app->homeUrl ?>images/icon/maxmin.png" class="sort-icon ml-10" id="maxmin-step">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/minmax.png" class="sort-icon ml-10" id="minmax-step" style="display:none;">
					<input type="hidden" id="sort-step" value="0">
				</span>
				<span class="box-sort ml-20" onclick="javascript:sortFinalDue()">Final due
					<img src="<?= Yii::$app->homeUrl ?>images/icon/maxmin.png" id="maxmin-final" class="sort-icon ml-10">
					<img src="<?= Yii::$app->homeUrl ?>images/icon/minmax.png" id="minmax-final" class="sort-icon ml-10" style="display:none;">
					<input type="hidden" id="sort-final" value="0">
				</span>

			</div>
		</div>
	</div>
	<div class="col-12 mt-30 font-size12" style="overflow-x:auto;" id="job-result">
		<?php
		$gridColumns = [
			['class' => 'kartik\grid\SerialColumn'],
			[
				//'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'Client',
				'vAlign' => 'middle',
				'value' => function ($model) {
					return Client::clientName($model->clientId);
				}
			],
			[
				'attribute' => 'Job',
				'format' => 'raw',
				'value' => function ($model) {
					$show = Job::checkAdditional($model->jobId);
					$checkList = '';
					if ($show == 1) {
						$checkList = "<span class='text-success mr-10' style='right:0;'><i class='fa fa-check' aria-hidden='true'></i></span>";
					}
					if ($show == 2) {
						$checkList = "<span class='text-success mr-10' style='right:0;'><i class='fa fa-circle' aria-hidden='true'></i></span>";
					}
					if ($show == 3) {
						$checkList = "<span class='mr-10 font-size16' style='right:0;color:#FF8C00'><i class='fa fa-star' aria-hidden='true'></i></span>";
					}
					$report = '';
					if ($model->report == 1) {
						$report = SubmitReport::showText($model->jobId);
					}
					return  $checkList . $model->jobName . $report;
				},
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'Job Type',
				'format' => 'raw',
				'value' => function ($model) {


					return  JobType::jobTypeName($model->jobTypeId);
				},
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'dueDate',
				'label' => 'Step Due',
				'filter' => '',
				'value' => function ($model) {
					return JobStep::CurrentStep($model->jobId);
				},
				'format' => 'raw',
				'vAlign' => 'middle',
			],
			[
				//'attribute' => 'Final Due',
				'attribute' => 'targetDate',
				'label' => 'Final Due',
				'value' => function ($model) {
					return JobCategory::CurrentJobCategory($model->jobId);
				},
				'format' => 'raw',
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'Team',
				'format' => 'raw',
				'value' => function ($model) {
					return  Team::teamName($model->teamId);
				},
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'PIC 1',
				'format' => 'raw',
				'value' => function ($model) {
					return '<div class="row">' . Job::jobResponsibility($model->jobId, JobResponsibility::PIC1) . '</div>';
				},
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'PIC 2',
				'format' => 'raw',
				'value' => function ($model) {
					return '<div class="row">' . Job::jobResponsibility($model->jobId, JobResponsibility::PIC2) . '</div>';
				},
				'vAlign' => 'middle',
			],
			[

				'attribute' => 'Status',
				'value' => function ($model) {
					return  JobStep::CurrentStepStatus($model->jobId, $model->status);
				},
				'format' => 'raw',
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'Action',
				'format' => 'raw',
				'value' => function ($model) {
					$text = '';
					//$canEdit = EmployeeType::findCanEdit();
					$hasNewChat = ReadChat::checkNewChat($model->jobId);
					$text .= '<a href="' . Yii::$app->homeUrl . 'job/detail/complete-job/' . ModelMaster::encodeParams(["jobId" => $model->jobId]) . '" title="Detail" class="mr-1 btn button-sky button-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';
					$text .= '<a href="javascript:showChatBox(' . $model->jobId . ',0)" title="Start chat" class="btn button-xs button-blue mr-1"><i class="fa fa-comment" aria-hidden="true"></i></a>';
					if ($hasNewChat == 1) {
						$text .= '<span id="noit-chat-' . $model->jobId . '"><i class="fa fa-circle noti-circle" aria-hidden="true"></i></span>';
					}
					$text .= '<a href="' . Yii::$app->homeUrl . 'job/detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $model->jobId]) . '" title="Update" class="mr-1 btn button-yellow button-xs"><i class="fa fa-edit" aria-hidden="true"></i></a>';
					$text .= '<a href="javascript:deleteJob(' . $model->jobId . ')" title="Delete" class="btn button-xs button-red"><i class="fa fa-trash" aria-hidden="true"></i></a>';

					return $text;
				}
			]

		];
		echo GridView::widget([
			'dataProvider' => $dataProviderJob,
			'columns' => $gridColumns,
			'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
			/*'beforeHeader' => [
				[
					'columns' => [
						['content' => 'Header Before 1', 'options' => ['colspan' => 4, 'class' => 'text-center warning']],
						['content' => 'Header Before 2', 'options' => ['colspan' => 4, 'class' => 'text-center warning']],
						['content' => 'Header Before 3', 'options' => ['colspan' => 3, 'class' => 'text-center warning']],
					],
					'options' => ['class' => 'skip-export'] // remove this row from export
				]
			],*/

			'pjax' => true,
			'bordered' => true,
			'striped' => false,
			'condensed' => false,
			'responsive' => true,
			'hover' => true,
			'floatHeader' => true,

			//'showPageSummary' => true,
			/*'panel' => [
				'type' => GridView::TYPE_LIGHT
			],*/
		]);
		?>
	</div>
	<?php
	?>
	<div class="col-12 mt-30" style="overflow-x:auto;display:none;" id="job-schedule">
		<?php /* $this->render('schedule', [
			"dateValue" => $dateValue,
			"selectMonth" => $selectMonth,
			"selectDate" => $selectDate,
			"jobStatus" => $jobStatus
		])*/ ?>
	</div>
</div>