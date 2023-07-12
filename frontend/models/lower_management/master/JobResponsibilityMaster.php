<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_responsibility".
*
    * @property integer $id
    * @property integer $jobId
    * @property integer $employeeId
    * @property integer $responsibility
    * @property integer $percentage
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobResponsibilityMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_responsibility';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'employeeId', 'responsibility'], 'required'],
            [['jobId', 'employeeId', 'percentage'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['responsibility', 'status'], 'string', 'max' => 10],
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
    'employeeId' => 'Employee ID',
    'responsibility' => 'Responsibility',
    'percentage' => 'Percentage',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
