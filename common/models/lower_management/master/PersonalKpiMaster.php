<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "personal_kpi".
*
    * @property integer $personalKpiId
    * @property integer $kpiId
    * @property string $targetAmount
    * @property string $personalTargetAmount
    * @property integer $employeeId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class PersonalKpiMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'personal_kpi';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kpiId', 'targetAmount', 'personalTargetAmount', 'employeeId'], 'required'],
            [['kpiId', 'employeeId'], 'integer'],
            [['targetAmount', 'personalTargetAmount'], 'number'],
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
    'personalKpiId' => 'Personal Kpi ID',
    'kpiId' => 'Kpi ID',
    'targetAmount' => 'Target Amount',
    'personalTargetAmount' => 'Personal Target Amount',
    'employeeId' => 'Employee ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
