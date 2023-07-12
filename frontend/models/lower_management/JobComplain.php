<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\JobComplainMaster;

/**
* This is the model class for table "job_complain".
*
* @property integer $id
* @property integer $jobId
* @property string $complain
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class JobComplain extends \frontend\models\lower_management\master\JobComplainMaster{
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
