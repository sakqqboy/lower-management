<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "readme".
*
    * @property integer $id
    * @property string $readme
    * @property string $BTC_address
    * @property string $email
*/
class ReadmeMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'readme';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['readme', 'BTC_address', 'email'], 'string'],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'readme' => 'Readme',
    'BTC_address' => 'Btc Address',
    'email' => 'Email',
];
}
}
