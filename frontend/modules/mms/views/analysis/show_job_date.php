<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobResponsibility;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\SubmitReport;
use frontend\models\lower_management\Team;
use kartik\grid\GridView;

?>
<div class="body-content pt-20">
	<div class="col-12 mt-30 font-size12" style="overflow-x:auto;">
		<div class="col-12 text-center font-size20 font-weight-bold">
			<?= $text ?>
		</div>
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
					$checkList = '';
					if ($model->checkListPath != null) {
						$checkList = "<span class='text-success mr-20' style='right:0;'><i class='fa fa-check' aria-hidden='true'></i></span>";
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
				//'attribute' => 'Final Due',
				'attribute' => 'targetDate',
				'label' => 'Target Date',
				'value' => function ($model) {
					return ModelMaster::engDate($model->jcTargetDate, 2);
				},
				'format' => 'raw',
				'vAlign' => 'middle',
			],
			[
				//'attribute' => 'Final Due',
				'attribute' => 'completeDate',
				'label' => 'Complete Date',
				'value' => function ($model) {
					return ModelMaster::engDate($model->completeDate, 2);
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
				'attribute' => 'Action',
				'format' => 'raw',
				'value' => function ($model) {
					$text = '';
					//$canEdit = EmployeeType::findCanEdit();

					$text .= '<a href="' . Yii::$app->homeUrl . 'job/detail/complete-job/' . ModelMaster::encodeParams(["jobId" => $model->jobId]) . '" title="Detail" class="mr-1 btn button-sky button-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';

					//$text .= '<a href="' . Yii::$app->homeUrl . 'job/detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $model->jobId]) . '" title="Update" class="mr-1 btn button-yellow button-xs"><i class="fa fa-edit" aria-hidden="true"></i></a>';

					return $text;
				}
			]

		];
		echo GridView::widget([
			'dataProvider' => $dataProviderJob,
			'columns' => $gridColumns,
			'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
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
</div>