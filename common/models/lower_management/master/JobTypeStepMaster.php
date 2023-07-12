<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_type_step".
*
    * @property integer $jobTypeStepId
    * @property integer $jobTypeId
    * @property integer $stepId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobTypeStepMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_type_step';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobTypeId', 'stepId'], 'required'],
            [['jobTypeId', 'stepId'], 'integer'],
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
    'jobTypeStepId' => 'Job Type Step ID',
    'jobTypeId' => 'Job Type ID',
    'stepId' => 'Step ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
