<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\KpiMaster;

/**
* This is the model class for table "kpi".
*
* @property integer $kpiId
* @property integer $kgiId
* @property integer $sectionId
* @property integer $teamPosition
* @property string $kpiName
* @property string $kpiDetail
* @property string $targetAmount
* @property integer $amountType
* @property string $symbolCheck
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Kpi extends \common\models\lower_management\master\KpiMaster{
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
