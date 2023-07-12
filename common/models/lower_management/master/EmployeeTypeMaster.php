<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "employee_type".
*
    * @property integer $employeeTypeId
    * @property integer $employeeId
    * @property integer $typeId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class EmployeeTypeMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'employee_type';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['employeeId', 'typeId'], 'required'],
            [['employeeId', 'typeId'], 'integer'],
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
    'employeeTypeId' => 'Employee Type ID',
    'employeeId' => 'Employee ID',
    'typeId' => 'Type ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
