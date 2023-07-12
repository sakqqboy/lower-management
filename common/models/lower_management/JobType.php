<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\JobTypeMaster;

/**
* This is the model class for table "job_type".
*
* @property integer $jobTypeId
* @property string $jobTypeName
* @property string $jobTypeDetail
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class JobType extends \common\models\lower_management\master\JobTypeMaster{
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
