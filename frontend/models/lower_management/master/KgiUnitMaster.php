<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi_unit".
*
    * @property integer $kgiUnitId
    * @property string $name
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class KgiUnitMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi_unit';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'kgiUnitId' => 'Kgi Unit ID',
    'name' => 'Name',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
