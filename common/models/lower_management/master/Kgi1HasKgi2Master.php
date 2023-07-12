<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi1_has_kgi2".
*
    * @property integer $id
    * @property integer $kgi1Id
    * @property integer $kgi2Id
    * @property integer $isMain
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class Kgi1HasKgi2Master extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi1_has_kgi2';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgi1Id', 'kgi2Id'], 'required'],
            [['kgi1Id', 'kgi2Id'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['isMain', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'kgi1Id' => 'Kgi1id',
    'kgi2Id' => 'Kgi2id',
    'isMain' => 'Is Main',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
