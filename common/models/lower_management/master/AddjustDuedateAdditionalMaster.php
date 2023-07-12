<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "addjust_duedate_additional".
*
    * @property integer $id
    * @property integer $additionalStepId
    * @property string $lmsDate
    * @property string $newDate
    * @property integer $employeeId
    * @property string $createDateTime
*/
class AddjustDuedateAdditionalMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'addjust_duedate_additional';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['additionalStepId', 'lmsDate', 'newDate', 'createDateTime'], 'required'],
            [['additionalStepId', 'employeeId'], 'integer'],
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
    'additionalStepId' => 'Additional Step ID',
    'lmsDate' => 'Lms Date',
    'newDate' => 'New Date',
    'employeeId' => 'Employee ID',
    'createDateTime' => 'Create Date Time',
];
}
}
