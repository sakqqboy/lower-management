<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kpi".
*
    * @property integer $kpiId
    * @property string $kpiName
    * @property string $kpiDetail
    * @property integer $unit
    * @property string $targetAmount
    * @property integer $amountType
    * @property string $symbolCheck
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class KpiMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kpi';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kpiName'], 'required'],
            [['kpiDetail'], 'string'],
            [['targetAmount'], 'number'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['kpiName'], 'string', 'max' => 255],
            [['unit', 'amountType', 'symbolCheck', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'kpiId' => 'Kpi ID',
    'kpiName' => 'Kpi Name',
    'kpiDetail' => 'Kpi Detail',
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
