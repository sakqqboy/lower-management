<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\Kgi1Master;

/**
* This is the model class for table "kgi1".
*
* @property integer $kgi1Id
* @property integer $branchId
* @property string $kgi1Name
* @property string $targetAmount
* @property integer $amountType
* @property string $code
* @property integer $status
* @property string $createDateTime
* @property string $udpateDateTime
* @property string $kgi1col
*/

class Kgi1 extends \common\models\lower_management\master\Kgi1Master{
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
