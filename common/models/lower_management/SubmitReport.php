<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\SubmitReportMaster;

/**
* This is the model class for table "submit_report".
*
* @property integer $submitReportId
* @property integer $jobId
* @property integer $CategoryId
* @property integer $stepId
* @property string $submitDate
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class SubmitReport extends \common\models\lower_management\master\SubmitReportMaster{
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
