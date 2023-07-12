<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "field_group".
*
    * @property integer $fieldGroupId
    * @property string $fieldGroupName
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class FieldGroupMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'field_group';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['fieldGroupName'], 'required'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['fieldGroupName'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'fieldGroupId' => 'Field Group ID',
    'fieldGroupName' => 'Field Group Name',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
