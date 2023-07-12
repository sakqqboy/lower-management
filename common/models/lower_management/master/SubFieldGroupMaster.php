<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "sub_field_group".
*
    * @property integer $subFieldGroupId
    * @property string $subFieldGroupName
    * @property integer $fieldGroupId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class SubFieldGroupMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'sub_field_group';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['fieldGroupId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['subFieldGroupName'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'subFieldGroupId' => 'Sub Field Group ID',
    'subFieldGroupName' => 'Sub Field Group Name',
    'fieldGroupId' => 'Field Group ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
