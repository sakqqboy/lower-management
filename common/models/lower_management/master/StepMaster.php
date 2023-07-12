<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "step".
*
    * @property integer $stepId
    * @property string $stepName
    * @property integer $jobTypeId
    * @property integer $sort
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class StepMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'step';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['stepName', 'jobTypeId'], 'required'],
            [['jobTypeId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['stepName'], 'string', 'max' => 200],
            [['sort', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'stepId' => 'Step ID',
    'stepName' => 'Step Name',
    'jobTypeId' => 'Job Type ID',
    'sort' => 'Sort',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
