<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\AddjustDuedateAdditionalMaster;

/**
 * This is the model class for table "addjust_duedate_additional".
 *
 * @property integer $id
 * @property integer $additionalStepId
 * @property string $lmsDate
 * @property string $newDate
 * @property string $createDateTime
 */

class AddjustDuedateAdditional extends \frontend\models\lower_management\master\AddjustDuedateAdditionalMaster
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
    public static function hasAdjust($additionalStepId)
    {
        $additionalAddjust = AddjustDuedateAdditional::find()->where(["additionalStepId" => $additionalStepId])->one();
        if (isset($additionalAddjust) && !empty($additionalAddjust)) {
            return 1;
        } else {
            return 0;
        }
    }
}
