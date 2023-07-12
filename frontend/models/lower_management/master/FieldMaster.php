<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "field".
*
    * @property integer $fieldId
    * @property integer $fieldGroupId
    * @property integer $subFieldGroupId
    * @property string $fieldName
    * @property integer $branchId
    * @property string $fieldDetail
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class FieldMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'field';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['fieldGroupId', 'subFieldGroupId', 'branchId'], 'integer'],
            [['fieldName'], 'required'],
            [['fieldDetail'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['fieldName'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'fieldId' => 'Field ID',
    'fieldGroupId' => 'Field Group ID',
    'subFieldGroupId' => 'Sub Field Group ID',
    'fieldName' => 'Field Name',
    'branchId' => 'Branch ID',
    'fieldDetail' => 'Field Detail',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
