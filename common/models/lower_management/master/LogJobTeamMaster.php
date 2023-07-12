<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_job_team".
*
    * @property integer $id
    * @property integer $jobId
    * @property integer $teamId
    * @property integer $currentStepId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogJobTeamMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_job_team';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'teamId', 'currentStepId'], 'required'],
            [['jobId', 'teamId', 'currentStepId'], 'integer'],
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
    'teamId' => 'Team ID',
    'currentStepId' => 'Current Step ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
