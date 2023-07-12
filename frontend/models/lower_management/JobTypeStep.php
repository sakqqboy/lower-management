<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\JobTypeStepMaster;

/**
* This is the model class for table "job_type_step".
*
* @property integer $jobTypeStepId
* @property integer $jobTypeId
* @property integer $stepId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class JobTypeStep extends \frontend\models\lower_management\master\JobTypeStepMaster{
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
