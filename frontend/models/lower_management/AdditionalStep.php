<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\AdditionalStepMaster;

/**
 * This is the model class for table "additional_step".
 *
 * @property integer $additionalStepId
 * @property integer $jobId
 * @property integer $stepId
 * @property integer $jobCategoryId
 * @property string $addtionalStepName
 * @property integer $sort
 * @property string $dueDate
 * @property string $completeDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTtime
 */

class AdditionalStep extends \frontend\models\lower_management\master\AdditionalStepMaster
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
    public static function AdditionalJobStep($jobId, $stepId, $jobCategoryId)
    {
        $additional = AdditionalStep::find()
            ->select('additionalStepName,dueDate,status,completeDate,additionalStepId,jobId,jobCategoryId,firstDueDate')
            ->where([
                "jobId" => $jobId,
                "stepId" => $stepId,
                "jobCategoryId" => $jobCategoryId
            ])
            ->andWhere("status!=99")
            ->asArray()
            ->orderBy('sort')
            ->all();
        return $additional;
    }
    public static function CountCompleteStep($jobId, $stepId, $jobCategoryId)
    {

        $additional = AdditionalStep::find()->where([
            "jobId" => $jobId,
            "stepId" => $stepId,
            "jobCategoryId" => $jobCategoryId
        ])
            ->asArray()
            ->all();
        return count($additional);
    }
    public static function isShowNextStep($jobStepId, $additionalStepId)
    {
        $jobStep = JobStep::find()->where(["jobStepId" => $jobStepId])->one();
        if ($jobStep->status == 1) {
            return 0;
        } else {
            $additionalStep = AdditionalStep::find()
                ->where(["additionalStepId" => $additionalStepId])
                ->one();
            if ($additionalStep->status == 1) {
                return 1;
            } else {
                $lastAdd = AdditionalStep::find()
                    ->where(["jobCategoryId" => $additionalStep->jobCategoryId, "stepId" => $additionalStep->stepId])
                    ->andWhere("additionalStepId<$additionalStepId")
                    ->orderBy('additionalStepId DESC')
                    ->one();
                if (isset($lastAdd) && !empty($lastAdd)) {
                    return 0;
                } else {
                    return 1;
                }
            }
        }
    }
    public static function canCancelComplete($jobCategoryId)
    {
        $can = [];
        $jobStep = JobStep::find()
            ->select('job_step.stepId,job_step.jobCategoryId,job_step.jobStepId')
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobCategoryId" => $jobCategoryId, "job_step.status" => 4])
            ->asArray()
            ->orderBy("s.sort DESC")
            ->one();
        if (isset($jobStep) && !empty($jobStep) > 0) {
            $additional = AdditionalStep::find()
                ->where(["stepId" => $jobStep["stepId"], "jobCategoryId" => $jobCategoryId, "status" => 4])
                ->orderby("sort DESC")
                ->asArray()
                ->one();
            if (isset($additional) && !empty($additional) > 0) {
                $can["add"] = $additional["additionalStepId"];
            } else {
                $can["jStep"] = $jobStep["jobStepId"];
            }
        }
        return $can;
    }
    public static function isCancel($additionalStepId)
    {
        $additional = AdditionalStep::find()
            ->where(["additionalStepId" => $additionalStepId])
            ->asArray()
            ->one();
        if (trim($additional["remark"]) != '') {
            return 1;
        } else {
            return 0;
        }
    }
}
