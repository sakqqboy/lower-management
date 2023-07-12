<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\KpiUnitMaster;

/**
* This is the model class for table "kpi_unit".
*
* @property integer $kpiUnitId
* @property string $name
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class KpiUnit extends \frontend\models\lower_management\master\KpiUnitMaster{
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
