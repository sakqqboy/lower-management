<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi1_category".
*
    * @property integer $kgi1CategoryId
    * @property integer $kgi1Id
    * @property integer $fiscalYear
    * @property integer $month
    * @property string $actualAmount
    * @property integer $status
    * @property string $crateDateTime
    * @property string $updateDateTime
*/
class Kgi1CategoryMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi1_category';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgi1Id'], 'required'],
            [['kgi1Id', 'fiscalYear', 'month'], 'integer'],
            [['actualAmount'], 'number'],
            [['crateDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'kgi1CategoryId' => 'Kgi1category ID',
    'kgi1Id' => 'Kgi1id',
    'fiscalYear' => 'Fiscal Year',
    'month' => 'Month',
    'actualAmount' => 'Actual Amount',
    'status' => 'Status',
    'crateDateTime' => 'Crate Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
