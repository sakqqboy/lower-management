<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\LogTargetDateMaster;

/**
* This is the model class for table "log_target_date".
*
* @property integer $id
* @property integer $jobCategoryId
* @property string $oldTargetDate
* @property string $newTargetDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class LogTargetDate extends \common\models\lower_management\master\LogTargetDateMaster{
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
