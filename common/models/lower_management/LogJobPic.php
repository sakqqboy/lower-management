<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\LogJobPicMaster;

/**
* This is the model class for table "log_job_pic".
*
* @property integer $id
* @property integer $jobId
* @property integer $userId
* @property integer $typeId
* @property integer $jobStepId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class LogJobPic extends \common\models\lower_management\master\LogJobPicMaster{
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
