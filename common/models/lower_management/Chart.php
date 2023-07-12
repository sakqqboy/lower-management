<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\ChartMaster;

/**
* This is the model class for table "chart".
*
* @property integer $chartId
* @property string $chartName
* @property integer $chartType
* @property string $yName
* @property integer $xType
* @property integer $dataType
* @property string $formula
* @property string $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Chart extends \common\models\lower_management\master\ChartMaster{
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
