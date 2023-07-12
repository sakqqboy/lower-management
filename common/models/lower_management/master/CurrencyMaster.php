<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "currency".
*
    * @property integer $currencyId
    * @property string $name
    * @property string $code
    * @property string $symbol
    * @property integer $status
*/
class CurrencyMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'currency';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 3],
            [['symbol'], 'string', 'max' => 5],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'currencyId' => 'Currency ID',
    'name' => 'Name',
    'code' => 'Code',
    'symbol' => 'Symbol',
    'status' => 'Status',
];
}
}
