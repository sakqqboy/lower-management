<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "personal_kgi".
*
    * @property integer $personalKgiId
    * @property integer $kgiId
    * @property string $targetAmount
    * @property string $personalTargetAmount
    * @property integer $employeeId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class PersonalKgiMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'personal_kgi';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgiId', 'targetAmount', 'personalTargetAmount', 'employeeId'], 'required'],
            [['kgiId', 'employeeId'], 'integer'],
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
    'personalKgiId' => 'Personal Kgi ID',
    'kgiId' => 'Kgi ID',
    'targetAmount' => 'Target Amount',
    'personalTargetAmount' => 'Personal Target Amount',
    'employeeId' => 'Employee ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
