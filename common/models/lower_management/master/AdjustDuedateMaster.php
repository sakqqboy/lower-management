<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "adjust_duedate".
*
    * @property integer $id
    * @property integer $jobStepId
    * @property string $lmsDate
    * @property string $newDate
    * @property integer $employeeId
    * @property string $createDateTime
*/
class AdjustDuedateMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'adjust_duedate';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobStepId', 'lmsDate', 'newDate', 'employeeId'], 'required'],
            [['jobStepId', 'employeeId'], 'integer'],
            [['lmsDate', 'newDate', 'createDateTime'], 'safe'],
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
    'lmsDate' => 'Lms Date',
    'newDate' => 'New Date',
    'employeeId' => 'Employee ID',
    'createDateTime' => 'Create Date Time',
];
}
}
