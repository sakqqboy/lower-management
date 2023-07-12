<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\JobStepMaster;

/**
* This is the model class for table "job_step".
*
* @property integer $jobStepId
* @property integer $jobId
* @property integer $stepId
* @property string $content
* @property string $targetDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class JobStep extends \common\models\lower_management\master\JobStepMaster{
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
