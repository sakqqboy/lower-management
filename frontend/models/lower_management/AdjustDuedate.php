<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\AdjustDuedateMaster;

/**
 * This is the model class for table "adjust_duedate".
 *
 * @property integer $id
 * @property integer $jobStepId
 * @property string $lmsDate
 * @property string $newDate
 * @property string $createDateTime
 */

class AdjustDuedate extends \frontend\models\lower_management\master\AdjustDuedateMaster
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
    public static function hasAddjust($jobStepId)
    {
        $adjustDate = AdjustDuedate::find()->where(["jobStepId" => $jobStepId])->one();
        if (isset($adjustDate) && !empty($adjustDate)) {
            return 1;
        } else {
            return 0;
        }
    }
}
