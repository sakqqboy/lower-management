<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_job_pic".
*
    * @property integer $id
    * @property integer $jobId
    * @property integer $userId
    * @property integer $percentage
    * @property integer $typeId
    * @property integer $jobStepId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogJobPicMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_job_pic';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'userId', 'typeId', 'jobStepId'], 'required'],
            [['jobId', 'userId', 'percentage', 'typeId', 'jobStepId'], 'integer'],
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
    'userId' => 'User ID',
    'percentage' => 'Percentage',
    'typeId' => 'Type ID',
    'jobStepId' => 'Job Step ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
