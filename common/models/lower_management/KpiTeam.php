<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\KpiTeamMaster;

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

class KpiTeam extends \common\models\lower_management\master\KpiTeamMaster{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return array_merge(parent::rules(), []);
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), []);
    }
}
