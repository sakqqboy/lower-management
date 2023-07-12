<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogCancelMaster;

/**
 * This is the model class for table "log_cancel".
 *
 * @property integer $id
 * @property integer $jobStepId
 * @property string $reason
 * @property string $createDateTime
 */

class LogCancel extends \frontend\models\lower_management\master\LogCancelMaster
{
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
    public static function isCancel($jobStepId)
    {
        $log = LogCancel::find()->where(["jobStepId" => $jobStepId, "status" => 1])->one();
        if (isset($log) && !empty($log)) {
            return 1;
        } else {
            return 0;
        }
    }
}
