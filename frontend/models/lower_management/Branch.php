<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\BranchMaster;

/**
 * This is the model class for table "branch".
 *
 * @property integer $branchId
 * @property string $branchName
 * @property integer $countryId
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Branch extends \frontend\models\lower_management\master\BranchMaster
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
    public static function branchName($branchId)
    {
        if ($branchId != null) {
            $branch = Branch::find()->select('branchName')->where(["branchId" => $branchId])->asArray()->one();
            if (isset($branch)) {
                return $branch["branchName"];
            } else {

                return '-';
            }
        } else {
            return '-';
        }
    }
    public static function branchFlag($branchId)
    {
        if ($branchId != null) {
            $branch = Branch::find()->select('flag')->where(["branchId" => $branchId])->asArray()->one();
            if (isset($branch)) {
                return $branch["flag"];
            }
        }
        return '';
    }
    public static function countryName($branchId)
    {
        $branch = Branch::find()->select('countryId')->where(["branchId" => $branchId])->asArray()->one();
        $country = Country::find()->select('countryName')->where(["countryId" => $branch["countryId"]])->asArray()->one();
        if (isset($country)) {
            return $country["countryName"];
        } else {
            return 'country not set';
        }
    }
    public static function BranchNameFromJob($jobTypeId)
    {
        $jobType = JobType::find()
            ->select('b.branchName')
            ->JOIN("LEFT JOIN", "branch b", "job_type.branchId=b.branchId")
            ->where(["job_type.jobTypeId" => $jobTypeId])
            ->asArray()
            ->one();
        if (isset($jobType)) {
            return $jobType["branchName"];
        } else {
            return 'country not set';
        }
    }
    public static function BranchIdFromJob($jobTypeId)
    {
        $jobType = JobType::find()
            ->select('branchId')
            ->where(["jobTypeId" => $jobTypeId])
            ->asArray()
            ->one();
        if (isset($jobType)) {
            return $jobType["branchId"];
        }
    }
    public static function branchNameFilter($branchId)
    {
        if ($branchId != null) {
            $branch = Branch::find()->select('branchName')->where(["branchId" => $branchId])->asArray()->one();
            if (isset($branch)) {
                return $branch["branchName"];
            } else {
                return 'Branch';
            }
        } else {
            return 'Branch';
        }
    }
    public static function totalClient($branchId, $year)
    {
        $clients = Client::find()
            ->where(["status" => Client::STATUS_ACTIVE, "branchId" => $branchId])
            ->asArray()
            ->all();
        $total = 0;
        if (isset($clients) && count($clients) > 0) {
            foreach ($clients as $c) :
                $job = Job::find()
                    ->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
                    ->where(["job.branchId" => $branchId, "job.status" => [1, 4], "job.clientId" => $c["clientId"], "jc.status" => [1, 4], "jc.fiscalYear" => $year])
                    ->asArray()
                    ->one();
                if (isset($job) && !empty($job)) {
                    $total++;
                }
            endforeach;
        }
        return $total;
    }
}
