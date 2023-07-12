<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\LogSubStepMaster;

/**
* This is the model class for table "log_sub_step".
*
* @property integer $id
* @property string $additionalStepId
* @property string $oldDueDate
* @property string $newDueDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class LogSubStep extends \common\models\lower_management\master\LogSubStepMaster{
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
