<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\AdditionalStepMaster;

/**
* This is the model class for table "additional_step".
*
* @property integer $additionalStepId
* @property integer $jobId
* @property integer $stepId
* @property integer $jobCategoryId
* @property string $addtionalStepName
* @property integer $sort
* @property string $dueDate
* @property string $completeDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTtime
*/

class AdditionalStep extends \common\models\lower_management\master\AdditionalStepMaster{
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
