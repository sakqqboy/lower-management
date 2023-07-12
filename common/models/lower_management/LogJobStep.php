<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\LogJobStepMaster;

/**
* This is the model class for table "log_job_step".
*
* @property integer $id
* @property integer $jobId
* @property integer $jobStepId
* @property string $oldDueDate
* @property string $newDueDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class LogJobStep extends \common\models\lower_management\master\LogJobStepMaster{
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
