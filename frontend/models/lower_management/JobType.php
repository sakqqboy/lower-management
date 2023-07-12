<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\JobTypeMaster;

/**
 * This is the model class for table "job_type".
 *
 * @property integer $jobTypeId
 * @property string $jobTypeName
 * @property string $jobTypeDetail
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class JobType extends \frontend\models\lower_management\master\JobTypeMaster
{
    /**
     * @inheritdoc
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 99;
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
    public static function jobTypeName($jobTypeId)
    {
        $jobType = JobType::find()->select('jobTypeName')->where(["jobTypeId" => $jobTypeId])->asArray()->one();
        if (isset($jobType) && !empty($jobType)) {
            return $jobType["jobTypeName"];
        } else {
            return 'Job Type not set';
        }
    }
    public static function checkNewJobType($branchId, $jobTypeName)
    {
        $jobType = JobType::find()
            ->select('jobTypeId')
            ->where(["branchId" => $branchId, "jobTypeName" => $jobTypeName])
            ->asArray()
            ->one();
        if (isset($jobType) && !empty($jobType)) {
            return 1;
        } else {
            return 0;
        }
    }
    public static function jobTypeAmount($jobTypeId)
    {
        $jobs = Job::find()
            ->select('job.*')
            ->where(["job.jobTypeId" => $jobTypeId])
            ->andWhere("job.status!=" . Job::STATUS_DELETED)
            ->asArray()
            ->all();
        $total = 0;
        if (isset($jobs) && count($jobs) > 0) {
            foreach ($jobs as $job) :
                $total +=  Category::muliplyfee($job["categoryId"]) * $job["fee"];
            endforeach;
        }
        return $total;
    }
    public static function jobTypeStep($jobTypeId)
    {
        $step = [];
        $step = Step::find()
            ->where(["jobTypeId" => $jobTypeId, "status" => JobStep::STATUS_ACTIVE])
            ->orderBy('sort')->asArray()->all();
        return $step;
    }
    public static function jobTypeBranch($branchId)
    {
        $jobTypes = JobType::find()
            ->where(["branchId" => $branchId, "status" => JobType::STATUS_ACTIVE])
            ->asArray()->orderBy('jobTypeName')
            ->all();
        return  $jobTypes;
    }
    public static function jobTypeId($jobTypeName, $branchId)
    {
        $jobType = JobType::find()->where(["branchId" => $branchId, "jobTypeName" => $jobTypeName, "status" => 1])->one();
        if (isset($jobType) && !empty($jobType)) {
            return $jobType->jobTypeId;
        } else {
            return '';
        }
    }
}
