<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\LogCancelMaster;

/**
* This is the model class for table "log_cancel".
*
* @property integer $id
* @property integer $jobStepId
* @property string $reason
* @property string $createDateTime
*/

class LogCancel extends \common\models\lower_management\master\LogCancelMaster{
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
