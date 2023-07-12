<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\JobStepContentMaster;

/**
* This is the model class for table "job_step_content".
*
* @property integer $jobStepContentId
* @property integer $jobStepId
* @property string $content
* @property string $targetDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class JobStepContent extends \frontend\models\lower_management\master\JobStepContentMaster{
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
