<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\LogJobPicMaster;
use yii\db\Expression;

/**
 * This is the model class for table "log_job_pic".
 *
 * @property integer $id
 * @property integer $jobId
 * @property integer $userId
 * @property integer $typeId
 * @property integer $jobStepId
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class LogJobPic extends \frontend\models\lower_management\master\LogJobPicMaster
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
    public static function saveLogPic($jobId, $typeId)
    {
        $logJob = LogJobPic::find()->where(["jobId" => $jobId, "typeId" => $typeId])->all();
        if (isset($logJob) && count($logJob) > 0) {
            foreach ($logJob as $job) :
                $job->status = 2;
                $job->updateDateTime = new Expression('NOW()');
                $job->save(false);
            endforeach;
        }
        $jobResponse = JobResponsibility::find()->where(["jobId" => $jobId, "responsibility" => $typeId])->all();
        if (isset($jobResponse) && count($jobResponse) > 0) {
            $currentJobStep = JobStep::find()
                ->select('jobStepId')
                ->where(["jobId" => $jobId, "status" => JobStep::STATUS_INPROCESS])
                ->orderby('dueDate ASC')
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
            foreach ($jobResponse as $job) :
                $log = new LogJobPic();
                $log->jobId = $jobId;
                $log->userId = $job->employeeId;
                $log->typeId = $typeId;
                $log->percentage = $job->percentage;
                $log->jobStepId = $jobStepId;
                $log->status = 1;
                $job->createDateTime = new Expression('NOW()');
                $log->updateDateTime = new Expression('NOW()');
                $log->save(false);
            endforeach;
        }
    }
}
