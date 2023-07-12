<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi2_team".
*
    * @property integer $kgi2TeamId
    * @property integer $kgi2Id
    * @property integer $teamId
    * @property integer $teamPositionId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class Kgi2TeamMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi2_team';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgi2Id', 'teamId', 'teamPositionId'], 'required'],
            [['kgi2Id', 'teamId', 'teamPositionId'], 'integer'],
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
    'kgi2TeamId' => 'Kgi2team ID',
    'kgi2Id' => 'Kgi2id',
    'teamId' => 'Team ID',
    'teamPositionId' => 'Team Position ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
