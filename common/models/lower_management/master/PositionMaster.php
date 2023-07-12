<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "position".
*
    * @property integer $positionId
    * @property integer $branchId
    * @property string $positionName
    * @property string $positionDetail
    * @property integer $level
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class PositionMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'position';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['branchId', 'positionName'], 'required'],
            [['branchId', 'level'], 'integer'],
            [['positionDetail'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['positionName'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'positionId' => 'Position ID',
    'branchId' => 'Branch ID',
    'positionName' => 'Position Name',
    'positionDetail' => 'Position Detail',
    'level' => 'Level',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
