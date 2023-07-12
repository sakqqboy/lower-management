<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\JobMaster;

/**
* This is the model class for table "job".
*
* @property integer $jobId
* @property string $jobNumber
* @property integer $clientId
* @property integer $categoryId
* @property integer $fieldId
* @property integer $jobTypeId
* @property integer $teamId
* @property integer $pIc1
* @property integer $pIc2
* @property string $fee
* @property string $advanceReceivable
* @property string $chargeMonth
* @property string $outsourcingFee
* @property string $startDate
* @property string $targetDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Job extends \common\models\lower_management\master\JobMaster{
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
