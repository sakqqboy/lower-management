<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\ChartResultMaster;

/**
* This is the model class for table "chart_result".
*
* @property integer $resultId
* @property integer $index
* @property integer $value
* @property integer $status
*/

class ChartResult extends \frontend\models\lower_management\master\ChartResultMaster{
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
