<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\KpiUnitMaster;

/**
* This is the model class for table "kpi_unit".
*
* @property integer $kpiUnitId
* @property string $name
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class KpiUnit extends \common\models\lower_management\master\KpiUnitMaster{
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
