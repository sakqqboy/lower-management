<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\JobResponsibilityMaster;

/**
* This is the model class for table "job_responsibility".
*
* @property integer $id
* @property integer $jobId
* @property integer $employeeId
* @property integer $responsibility
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class JobResponsibility extends \common\models\lower_management\master\JobResponsibilityMaster{
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
