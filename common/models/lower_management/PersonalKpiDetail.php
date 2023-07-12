<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\PersonalKpiDetailMaster;

/**
* This is the model class for table "personal_kpi_detail".
*
* @property integer $personalKpiDetailId
* @property integer $pkpiId
* @property integer $kpiId
* @property integer $day
* @property integer $month
* @property integer $year
* @property string $file
* @property string $amount
* @property string $detail
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class PersonalKpiDetail extends \common\models\lower_management\master\PersonalKpiDetailMaster{
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
