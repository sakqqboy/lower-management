<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_job_category".
*
    * @property integer $id
    * @property integer $jobId
    * @property integer $jobStepId
    * @property integer $jobCategoryId
    * @property string $oldTargetDate
    * @property string $newTargetDate
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogJobCategoryMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_job_category';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'jobStepId', 'jobCategoryId', 'newTargetDate'], 'required'],
            [['jobId', 'jobStepId', 'jobCategoryId'], 'integer'],
            [['oldTargetDate', 'newTargetDate', 'createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'jobId' => 'Job ID',
    'jobStepId' => 'Job Step ID',
    'jobCategoryId' => 'Job Category ID',
    'oldTargetDate' => 'Old Target Date',
    'newTargetDate' => 'New Target Date',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
