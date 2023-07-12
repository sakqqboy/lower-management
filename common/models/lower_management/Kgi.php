<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\KgiMaster;

/**
* This is the model class for table "kgi".
*
* @property integer $kgiId
* @property integer $branchId
* @property string $kgiName
* @property string $kgiDetail
* @property string $unit
* @property string $targetAmount
* @property integer $amountType
* @property string $sysbolCheck
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Kgi extends \common\models\lower_management\master\KgiMaster{
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
