<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi2_category".
*
    * @property integer $kgi2CategoryId
    * @property integer $kgi2Id
    * @property integer $fiscalYear
    * @property integer $month
    * @property string $actualAmount
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class Kgi2CategoryMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi2_category';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgi2CategoryId'], 'required'],
            [['kgi2CategoryId', 'kgi2Id', 'fiscalYear', 'month'], 'integer'],
            [['actualAmount'], 'number'],
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
    'kgi2CategoryId' => 'Kgi2category ID',
    'kgi2Id' => 'Kgi2id',
    'fiscalYear' => 'Fiscal Year',
    'month' => 'Month',
    'actualAmount' => 'Actual Amount',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
