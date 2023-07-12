<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\Kgi2HasKpiMaster;

/**
* This is the model class for table "kgi2_has_kpi".
*
* @property integer $id
* @property integer $kgi2Id
* @property integer $kpiId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Kgi2HasKpi extends \frontend\models\lower_management\master\Kgi2HasKpiMaster{
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
