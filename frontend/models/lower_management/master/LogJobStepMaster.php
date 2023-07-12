<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_job_step".
*
    * @property integer $id
    * @property integer $jobId
    * @property integer $jobStepId
    * @property string $oldDueDate
    * @property string $newDueDate
    * @property integer $employeeId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogJobStepMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_job_step';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'jobStepId'], 'required'],
            [['jobId', 'jobStepId', 'employeeId'], 'integer'],
            [['oldDueDate', 'newDueDate', 'createDateTime', 'updateDateTime'], 'safe'],
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
    'oldDueDate' => 'Old Due Date',
    'newDueDate' => 'New Due Date',
    'employeeId' => 'Employee ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
