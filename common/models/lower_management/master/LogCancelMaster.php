<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_cancel".
*
    * @property integer $id
    * @property integer $jobStepId
    * @property string $reason
    * @property string $createDateTime
    * @property integer $status
*/
class LogCancelMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_cancel';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobStepId', 'reason'], 'required'],
            [['jobStepId'], 'integer'],
            [['reason'], 'string'],
            [['createDateTime'], 'safe'],
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
    'jobStepId' => 'Job Step ID',
    'reason' => 'Reason',
    'createDateTime' => 'Create Date Time',
    'status' => 'Status',
];
}
}
