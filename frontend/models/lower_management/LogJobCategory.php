<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogJobCategoryMaster;
use yii\db\Expression;

/**
 * This is the model class for table "log_job_category".
 *
 * @property integer $id
 * @property integer $jobId
 * @property integer $jobStepId
 * @property integer $jobCategoryId
 * @property integer $oldTargetDate
 * @property integer $newTargetDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class LogJobCategory extends \frontend\models\lower_management\master\LogJobCategoryMaster
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
    public static function saveChangeCategoryTargetDate($jobId, $jobCategoryId, $oldTargetDate, $newTargetDate)
    {
        $log = LogJobCategory::find()->where(["jobId" => $jobId, "jobCategoryId" => $jobCategoryId, "status" => 1])->one();
        if (isset($log)) {
            $log->status = 2;
            $log->save(false);
        }
        $currentJobStep = JobStep::find()
            ->select('jobStepId')
            ->where(["jobId" => $jobId, "status" => JobStep::STATUS_INPROCESS])
            ->orderby('dueDate')
            ->asArray()
            ->one();
        if (isset($currentJobStep) && !empty($currentJobStep)) {
            $jobStepId = $currentJobStep["jobStepId"];
        } else { // complete all step
            $currentJobStep = JobStep::find()
                ->select('jobStepId')
                ->where(["jobId" => $jobId])
                ->orderby('jobStepId DESC')
                ->asArray()
                ->one();
            if (isset($currentJobStep) && !empty($currentJobStep)) {
                $jobStepId = $currentJobStep["jobStepId"];
            } else {
                $jobStepId = 0;
            }
        }
        $logCate = new LogJobCategory();
        $logCate->jobId = $jobId;
        $logCate->jobCategoryId = $jobCategoryId;
        $logCate->oldTargetDate = $oldTargetDate != null ? $oldTargetDate : new Expression('NOW()');
        $logCate->newTargetDate = $newTargetDate;
        $logCate->jobStepId = $jobStepId;
        $logCate->status = 1;
        $logCate->createDateTime = new Expression('NOW()');
        $logCate->updateDateTime = new Expression('NOW()');
        $logCate->save(false);
    }
}
