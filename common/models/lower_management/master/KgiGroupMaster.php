<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi_group".
*
    * @property integer $kgiGroupId
    * @property string $kgiGroupName
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class KgiGroupMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi_group';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgiGroupName'], 'required'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['kgiGroupName'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'kgiGroupId' => 'Kgi Group ID',
    'kgiGroupName' => 'Kgi Group Name',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
