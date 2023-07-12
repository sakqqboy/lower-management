<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\LogJobCategoryMaster;

/**
* This is the model class for table "log_job_category".
*
* @property integer $id
* @property integer $jobId
* @property integer $jobStepId
* @property integer $jobCategoryId
* @property integer $oldTargetDate
* @property integer $newTargetDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class LogJobCategory extends \common\models\lower_management\master\LogJobCategoryMaster{
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
