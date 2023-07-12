<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\PieTitleMaster;

/**
* This is the model class for table "pie_title".
*
* @property integer $id
* @property integer $chartId
* @property integer $index
* @property string $title
*/

class PieTitle extends \common\models\lower_management\master\PieTitleMaster{
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
