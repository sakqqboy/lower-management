<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi1".
*
    * @property integer $kgi1Id
    * @property integer $branchId
    * @property string $kgi1Name
    * @property string $targetAmount
    * @property integer $amountType
    * @property string $code
    * @property string $detail
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class Kgi1Master extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi1';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['branchId', 'kgi1Name'], 'required'],
            [['branchId'], 'integer'],
            [['targetAmount'], 'number'],
            [['detail'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['kgi1Name'], 'string', 'max' => 25],
            [['amountType', 'code', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'kgi1Id' => 'Kgi1id',
    'branchId' => 'Branch ID',
    'kgi1Name' => 'Kgi1name',
    'targetAmount' => 'Target Amount',
    'amountType' => 'Amount Type',
    'code' => 'Code',
    'detail' => 'Detail',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
