<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "submit_report".
*
    * @property integer $submitReportId
    * @property integer $jobId
    * @property integer $CategoryId
    * @property integer $stepId
    * @property string $submitDate
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class SubmitReportMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'submit_report';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'CategoryId', 'stepId'], 'required'],
            [['jobId', 'CategoryId', 'stepId'], 'integer'],
            [['submitDate', 'createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'submitReportId' => 'Submit Report ID',
    'jobId' => 'Job ID',
    'CategoryId' => 'Category ID',
    'stepId' => 'Step ID',
    'submitDate' => 'Submit Date',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
