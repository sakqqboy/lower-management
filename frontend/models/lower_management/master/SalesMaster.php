<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "sales".
*
    * @property integer $salesId
    * @property string $title
    * @property string $date
    * @property string $clientName
    * @property string $planceWayId
    * @property string $clientAttendance
    * @property string $clientEmail
    * @property string $explanation
    * @property integer $branchId
    * @property integer $teamId
    * @property integer $employeeId
    * @property integer $isConfirm
    * @property integer $isSales
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class SalesMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'sales';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['salesId', 'title', 'date'], 'required'],
            [['salesId', 'branchId', 'teamId', 'employeeId'], 'integer'],
            [['date', 'createDateTime', 'updateDateTime'], 'safe'],
            [['explanation'], 'string'],
            [['title', 'clientName', 'clientAttendance', 'clientEmail'], 'string', 'max' => 255],
            [['planceWayId'], 'string', 'max' => 45],
            [['isConfirm', 'isSales', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'salesId' => 'Sales ID',
    'title' => 'Title',
    'date' => 'Date',
    'clientName' => 'Client Name',
    'planceWayId' => 'Plance Way ID',
    'clientAttendance' => 'Client Attendance',
    'clientEmail' => 'Client Email',
    'explanation' => 'Explanation',
    'branchId' => 'Branch ID',
    'teamId' => 'Team ID',
    'employeeId' => 'Employee ID',
    'isConfirm' => 'Is Confirm',
    'isSales' => 'Is Sales',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
