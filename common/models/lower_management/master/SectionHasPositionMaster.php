<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "section_has_position".
*
    * @property integer $spId
    * @property integer $sectionId
    * @property integer $positionId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class SectionHasPositionMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'section_has_position';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['sectionId', 'positionId'], 'required'],
            [['sectionId', 'positionId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'spId' => 'Sp ID',
    'sectionId' => 'Section ID',
    'positionId' => 'Position ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
