<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\PersonalKgiMaster;

/**
* This is the model class for table "personal_kgi".
*
* @property integer $personalKgiId
* @property integer $kgiId
* @property string $targetAmount
* @property string $personalTargetAmount
* @property integer $employeeId
* @property integer $status
* @property string $createDateTime
* @property string $udateDateTime
*/

class PersonalKgi extends \common\models\lower_management\master\PersonalKgiMaster{
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
