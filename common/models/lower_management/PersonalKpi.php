<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\PersonalKpiMaster;

/**
* This is the model class for table "personal_kpi".
*
* @property integer $personalKpiId
* @property integer $kpiId
* @property string $targetAmount
* @property string $personalTargetAmount
* @property integer $employeeId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class PersonalKpi extends \common\models\lower_management\master\PersonalKpiMaster{
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
