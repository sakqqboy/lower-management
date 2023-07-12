<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogJobApproverMaster;

/**
* This is the model class for table "log_job_approver".
*
* @property integer $id
* @property integer $jobId
* @property integer $employeeId
* @property integer $jobCategoryId
* @property integer $jobStepId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class LogJobApprover extends \frontend\models\lower_management\master\LogJobApproverMaster{
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
