<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\Kgi1CategoryMaster;

/**
* This is the model class for table "kgi1_category".
*
* @property integer $kgi1CategoryId
* @property integer $kgi1Id
* @property integer $fiscalYear
* @property integer $month
* @property string $actualAmount
* @property integer $status
* @property string $crateDateTime
* @property string $updateDateTime
*/

class Kgi1Category extends \common\models\lower_management\master\Kgi1CategoryMaster{
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
