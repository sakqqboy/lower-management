<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi".
*
    * @property integer $kgiId
    * @property integer $kgiGroupId
    * @property integer $branchId
    * @property integer $teamId
    * @property integer $teamPositionId
    * @property string $kgiName
    * @property string $kgiDetail
    * @property string $unit
    * @property string $targetAmount
    * @property integer $amountType
    * @property string $symbolCheck
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class KgiMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgiGroupId', 'branchId', 'teamId', 'teamPositionId', 'kgiName'], 'required'],
            [['kgiGroupId', 'branchId', 'teamId', 'teamPositionId'], 'integer'],
            [['kgiDetail'], 'string'],
            [['targetAmount'], 'number'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['kgiName'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 45],
            [['amountType', 'symbolCheck', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'kgiId' => 'Kgi ID',
    'kgiGroupId' => 'Kgi Group ID',
    'branchId' => 'Branch ID',
    'teamId' => 'Team ID',
    'teamPositionId' => 'Team Position ID',
    'kgiName' => 'Kgi Name',
    'kgiDetail' => 'Kgi Detail',
    'unit' => 'Unit',
    'targetAmount' => 'Target Amount',
    'amountType' => 'Amount Type',
    'symbolCheck' => 'Symbol Check',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
