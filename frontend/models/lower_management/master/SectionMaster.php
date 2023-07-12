<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "section".
*
    * @property integer $sectionId
    * @property string $sectionName
    * @property integer $branchId
    * @property string $sectionDetail
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class SectionMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'section';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['sectionName', 'branchId'], 'required'],
            [['branchId'], 'integer'],
            [['sectionDetail'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['sectionName'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'sectionId' => 'Section ID',
    'sectionName' => 'Section Name',
    'branchId' => 'Branch ID',
    'sectionDetail' => 'Section Detail',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
