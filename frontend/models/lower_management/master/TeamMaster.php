<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "team".
*
    * @property integer $teamId
    * @property string $teamName
    * @property integer $branchId
    * @property integer $sectionId
    * @property string $teamDetail
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class TeamMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'team';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['teamName', 'branchId'], 'required'],
            [['branchId', 'sectionId'], 'integer'],
            [['teamDetail'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['teamName'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'teamId' => 'Team ID',
    'teamName' => 'Team Name',
    'branchId' => 'Branch ID',
    'sectionId' => 'Section ID',
    'teamDetail' => 'Team Detail',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
