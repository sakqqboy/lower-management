<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_alert".
*
    * @property integer $jobAlertId
    * @property integer $jobId
    * @property string $userId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobAlertMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_alert';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'userId'], 'required'],
            [['jobId'], 'integer'],
            [['userId'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 4],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'jobAlertId' => 'Job Alert ID',
    'jobId' => 'Job ID',
    'userId' => 'User ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
