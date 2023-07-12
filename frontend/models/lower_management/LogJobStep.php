<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogJobStepMaster;
use yii\db\Expression;

/**
 * This is the model class for table "log_job_step".
 *
 * @property integer $id
 * @property integer $jobId
 * @property integer $jobStepId
 * @property string $oldDueDate
 * @property string $newDueDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class LogJobStep extends \frontend\models\lower_management\master\LogJobStepMaster
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
    public static function saveChangeStepDueDate($jobId, $jobStepId, $oldDueDate, $newDuedate)
    {
        $old = LogJobStep::find()->where(["jobId" => $jobId, "jobStepId" => $jobStepId, "status" => 1])->one();
        if (isset($old) && !empty($old)) {
            $old->status = 2;
            $old->save(false);
        }
        $new = new LogJobStep();
        $new->jobId = $jobId;
        $new->jobStepId = $jobStepId;
        $new->oldDueDate = $oldDueDate;
        $new->newDueDate = $newDuedate;
        $new->employeeId = Yii::$app->user->id;
        $new->status = 1;
        $new->createDateTime = new Expression('NOW()');
        $new->updateDateTime = new Expression('NOW()');
        $new->save(false);
    }
    public static function hasLog($jobStepId)
    {
        $log = LogJobStep::find()->where(["jobStepId" => $jobStepId])->asArray()->one();
        if (isset($log) && !empty($log)) {
            return 1;
        } else {
            return 0;
        }
    }
}
