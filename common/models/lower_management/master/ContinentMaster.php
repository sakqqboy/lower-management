<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "continent".
*
    * @property integer $continentId
    * @property string $continentName
    * @property integer $sort
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class ContinentMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'continent';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['continentName'], 'required'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['continentName'], 'string', 'max' => 255],
            [['sort', 'status'], 'string', 'max' => 6],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'continentId' => 'Continent ID',
    'continentName' => 'Continent Name',
    'sort' => 'Sort',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
