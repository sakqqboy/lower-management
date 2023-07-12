<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "type".
*
    * @property integer $typeId
    * @property string $typeName
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class TypeMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'type';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['typeName'], 'required'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['typeName'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'typeId' => 'Type ID',
    'typeName' => 'Type Name',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
