<?php

use common\models\ModelMaster;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\ReadChat;
use kartik\grid\GridView;

$gridColumns = [
	['class' => 'kartik\grid\SerialColumn'],
	[
		'attribute' => 'Client',
		'vAlign' => 'middle',
		'value' => function ($model) {
			return Client::clientName($model->clientId);
		}
	],
	[
		'attribute' => 'Job',
		'value' => function ($model) {
			return $model->jobName;
		},
		'vAlign' => 'middle',
	],
	[
		'attribute' => 'Step Due',
		'value' => function ($model) {
			return JobStep::CurrentStep($model->jobId);
		},
		'format' => 'raw',
		'vAlign' => 'middle',
	],
	[
		'attribute' => 'Final Due',
		'value' => function ($model) {
			return JobCategory::CurrentJobCategory($model->jobId);
		},
		'format' => 'raw',
		'vAlign' => 'middle',
	],
	[
		'attribute' => 'PIC 1',
		'format' => 'raw',
		'value' => function ($model) {
			return '<div class="row">' . Job::jobResponsibility($model->jobId, 'PIC 1') . '</div>';
		},
		'vAlign' => 'middle',
	],
	[
		'attribute' => 'PIC 2',
		'format' => 'raw',
		'value' => function ($model) {
			return '<div class="row">' . Job::jobResponsibility($model->jobId, 'PIC 2') . '</div>';
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
			$canEdit = EmployeeType::findCanEdit();
			$hasNewChat = ReadChat::checkNewChat($model->jobId);
			$text .= '<a href="' . Yii::$app->homeUrl . 'job/detail/complete-job/' . ModelMaster::encodeParams(["jobId" => $model->jobId]) . '" title="Detail" class="mr-1 btn button-sky button-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';
			$text .= '<a href="javascript:showChatBox(' . $model->jobId . ')" title="Start chat" class="btn button-xs button-blue mr-1"><i class="fa fa-comment" aria-hidden="true"></i></a>';
			if ($hasNewChat == 1) {
				$text .= '<span id="noit-chat-' . $model->jobId . '"><i class="fa fa-circle noti-circle" aria-hidden="true"></i></span>';
			}
			if ($canEdit == 1) {
				$text .= '<a href="' . Yii::$app->homeUrl . 'job/detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $model->jobId]) . '" title="Update" class="mr-1 btn button-yellow button-xs"><i class="fa fa-edit" aria-hidden="true"></i></a>';
				$text .= '<a href="javascript:deleteJob(' . $model->jobId . ')" title="Delete" class="btn button-xs button-red"><i class="fa fa-trash" aria-hidden="true"></i></a>';
			}
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
	//'floatHeaderOptions' => ['scrollingTop' => $scrollingTop],
	//'showPageSummary' => true,
	'panel' => [
		//'type' => GridView::TYPE_PRIMARY
	],
]);
