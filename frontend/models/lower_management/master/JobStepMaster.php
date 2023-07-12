<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_step".
*
    * @property integer $jobStepId
    * @property integer $jobId
    * @property integer $jobCategoryId
    * @property integer $stepId
    * @property string $content
    * @property string $remark
    * @property string $dueDate
    * @property string $completeDate
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobStepMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_step';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'jobCategoryId', 'stepId'], 'required'],
            [['jobId', 'jobCategoryId', 'stepId'], 'integer'],
            [['content', 'remark'], 'string'],
            [['dueDate', 'completeDate', 'createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'jobStepId' => 'Job Step ID',
    'jobId' => 'Job ID',
    'jobCategoryId' => 'Job Category ID',
    'stepId' => 'Step ID',
    'content' => 'Content',
    'remark' => 'Remark',
    'dueDate' => 'Due Date',
    'completeDate' => 'Complete Date',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
