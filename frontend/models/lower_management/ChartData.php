<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\ChartDataMaster;

/**
* This is the model class for table "chart_data".
*
* @property integer $dataId
* @property integer $row
* @property integer $index
* @property integer $value
* @property integer $status
*/

class ChartData extends \frontend\models\lower_management\master\ChartDataMaster{
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
