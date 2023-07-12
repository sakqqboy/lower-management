<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "sales_attendance".
*
    * @property integer $saleAttendanceId
    * @property integer $employeeId
    * @property integer $typeId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class SalesAttendanceMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'sales_attendance';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['employeeId'], 'required'],
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
    'saleAttendanceId' => 'Sale Attendance ID',
    'employeeId' => 'Employee ID',
    'typeId' => 'Type ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
