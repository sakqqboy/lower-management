<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_job_approver".
*
    * @property integer $id
    * @property integer $jobId
    * @property integer $employeeId
    * @property integer $jobCategoryId
    * @property integer $jobStepId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogJobApproverMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_job_approver';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'employeeId', 'jobCategoryId'], 'required'],
            [['jobId', 'employeeId', 'jobCategoryId', 'jobStepId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
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
    'employeeId' => 'Employee ID',
    'jobCategoryId' => 'Job Category ID',
    'jobStepId' => 'Job Step ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
