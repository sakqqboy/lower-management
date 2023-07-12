<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\AdjustDuedateMaster;

/**
* This is the model class for table "adjust_duedate".
*
* @property integer $id
* @property integer $jobStepId
* @property string $lmsDate
* @property string $newDate
* @property string $createDateTime
*/

class AdjustDuedate extends \common\models\lower_management\master\AdjustDuedateMaster{
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
