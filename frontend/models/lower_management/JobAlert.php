<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\JobAlertMaster;

/**
* This is the model class for table "job_alert".
*
* @property integer $jobAlertId
* @property integer $jobId
* @property string $userId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class JobAlert extends \frontend\models\lower_management\master\JobAlertMaster{
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
