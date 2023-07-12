<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "additional_step".
*
    * @property integer $additionalStepId
    * @property integer $jobId
    * @property integer $stepId
    * @property integer $jobCategoryId
    * @property string $additionalStepName
    * @property integer $sort
    * @property string $dueDate
    * @property string $completeDate
    * @property string $remark
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class AdditionalStepMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'additional_step';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'stepId', 'jobCategoryId', 'additionalStepName'], 'required'],
            [['jobId', 'stepId', 'jobCategoryId', 'sort'], 'integer'],
            [['dueDate', 'completeDate', 'createDateTime', 'updateDateTime'], 'safe'],
            [['remark'], 'string'],
            [['additionalStepName'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'additionalStepId' => 'Additional Step ID',
    'jobId' => 'Job ID',
    'stepId' => 'Step ID',
    'jobCategoryId' => 'Job Category ID',
    'additionalStepName' => 'Additional Step Name',
    'sort' => 'Sort',
    'dueDate' => 'Due Date',
    'completeDate' => 'Complete Date',
    'remark' => 'Remark',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
