<?php

namespace frontend\models\lower_management;

use Exception;
use Yii;
use \frontend\models\lower_management\master\LogSubStepMaster;
use yii\db\Expression;

/**
 * This is the model class for table "log_sub_step".
 *
 * @property integer $id
 * @property string $additionalStepId
 * @property string $oldDueDate
 * @property string $newDueDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class LogSubStep extends \frontend\models\lower_management\master\LogSubStepMaster
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
    public static function saveChangeSubDueDate($additionalStepId, $oldDueDate, $newDueDate)
    {
        $log = new LogSubStep();
        $log->additionalStepId = $additionalStepId;
        $log->oldDueDate = $oldDueDate == null ? $newDueDate : $oldDueDate;
        $log->newDueDate = $newDueDate;
        $log->status = 1;
        $log->createDateTime = new Expression('NOW()');
        $log->save(false);
    }
}
