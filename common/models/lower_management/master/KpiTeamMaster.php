<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kpi_team".
*
    * @property integer $kpiTeamId
    * @property integer $kpiId
    * @property integer $teamId
    * @property integer $teamPositionId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class KpiTeamMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kpi_team';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kpiId', 'teamId', 'teamPositionId'], 'required'],
            [['kpiId', 'teamId', 'teamPositionId'], 'integer'],
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
    'kpiTeamId' => 'Kpi Team ID',
    'kpiId' => 'Kpi ID',
    'teamId' => 'Team ID',
    'teamPositionId' => 'Team Position ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
