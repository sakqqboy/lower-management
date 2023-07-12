<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_step_content".
*
    * @property integer $jobStepContentId
    * @property integer $jobStepId
    * @property string $content
    * @property string $targetDate
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobStepContentMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_step_content';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobStepId', 'content'], 'required'],
            [['jobStepId'], 'integer'],
            [['content'], 'string'],
            [['targetDate', 'createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'jobStepContentId' => 'Job Step Content ID',
    'jobStepId' => 'Job Step ID',
    'content' => 'Content',
    'targetDate' => 'Target Date',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
