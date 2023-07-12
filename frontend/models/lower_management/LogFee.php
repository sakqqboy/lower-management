<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogFeeMaster;

/**
* This is the model class for table "log_fee".
*
* @property integer $id
* @property string $jobId
* @property integer $jobCategoryId
* @property integer $jobStepId
* @property string $fee
* @property string $feeChargeDate
* @property string $advanceReceivable
* @property string $advancedChargeDate
* @property string $outSourcingFee
* @property integer $estimateTime
* @property string $createDateTime
*/

class LogFee extends \frontend\models\lower_management\master\LogFeeMaster{
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
