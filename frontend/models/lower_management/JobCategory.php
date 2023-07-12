<?php

namespace frontend\models\lower_management;

use common\models\ModelMaster;
use Exception;
use Yii;
use \frontend\models\lower_management\master\JobCategoryMaster;

/**
 * This is the model class for table "job_category".
 *
 * @property integer $jobCategoryId
 * @property integer $jobId
 * @property integer $categoryId
 * @property string $startMonth
 * @property string $targetDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class JobCategory extends \frontend\models\lower_management\master\JobCategoryMaster
{
    /**
     * @inheritdoc
     */
    const STATUS_INPROCESS = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECT = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_WAITPROCESS = 10;
    const STATUS_DELETED = 99; //Resign
    const STATUS_DRAF = 100;
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
    public static function CurrentJobCategory($jobId)
    {
        $text = '';
        $currentJobCategory = JobCategory::find()
            ->select('targetDate')
            ->where(["jobId" => $jobId, "status" => JobCategory::STATUS_INPROCESS])
            ->orderBy("targetDate")
            ->asArray()
            ->one();
        if (isset($currentJobCategory) && !empty($currentJobCategory) && $currentJobCategory["targetDate"] != null) {
            $text .= "<div class='col-12 text-center mt-10'>" . ModelMaster::engDate($currentJobCategory["targetDate"], 2) . "</div>";
        } else {
            $currentJobCategory = JobCategory::find()
                ->select('targetDate')
                ->where(["jobId" => $jobId, "status" => JobCategory::STATUS_COMPLETE])
                ->orderBy("targetDate")
                ->orderBy('createDateTime DESC')
                ->asArray()
                ->one();
            if (isset($currentJobCategory) && !empty($currentJobCategory) && $currentJobCategory["targetDate"] != null) {
                $text .= "<div class='col-12 text-center mt-10'>" . ModelMaster::engDate($currentJobCategory["targetDate"], 2) . "</div>";
            }
        }
        return $text;
    }
    public static function CurrentJobCategoryEmail($jobId)
    {
        $text = '';
        $currentJobCategory = JobCategory::find()
            ->select('targetDate')
            ->where(["jobId" => $jobId, "status" => JobCategory::STATUS_INPROCESS])
            ->orderBy("targetDate")
            ->asArray()
            ->one();
        if (isset($currentJobCategory) && !empty($currentJobCategory)) {
            $target = explode(" ", $currentJobCategory["targetDate"]);
            $text = $target[0];
        } else {
            $text = "-";
        }
        return $text;
    }
    public static function createClass($status, $dueDate)
    {
        $class = [];

        $today = strtotime(date('Y-m-d 00:00:00'));
        $due = strtotime($dueDate);
        $over = 0; //not over
        if ($today > $due) {
            $over = 1; //over due date
        }
        $class["over"] = $over;
        $class["class"] = "job-date";
        if ($status == JobCategory::STATUS_COMPLETE && $over == 0) {
            $class["class"] = "complete-due job-date";
        }
        if ($status < JobCategory::STATUS_COMPLETE && $over == 0) {
            $class["class"] = "final-due job-date";
        }
        if ($status == JobCategory::STATUS_COMPLETE && $over == 1) {
            $class["class"] = "complete-due job-date";
        }
        if ($status < JobCategory::STATUS_COMPLETE && $over == 1) {
            $class["class"] = "over-due job-date";
        }
        return $class;
    }
    public static function statusText($status)
    {
        $text = '';
        if ($status == JobCategory::STATUS_INPROCESS) {
            $text = 'Inprocess';
        }
        if ($status == JobCategory::STATUS_COMPLETE) {
            $text = 'Complete';
        }
        return $text;
    }
    public static function currentTargetNo($jobId)
    {
        $jobCategory = JobCategory::find()->where(["status" => JobCategory::STATUS_COMPLETE, "jobId" => $jobId])->all();
        return count($jobCategory) + 1;
    }
    public static function clearEmtyJobCategoryStep($jobCategoryId)
    {
        $jobStep = JobStep::find()->where(["jobCategoryId" => $jobCategoryId])->all();
        if (isset($jobStep) && count($jobStep) > 0) {
            return 1;
        } else {
            JobCategory::deleteAll(["jobCategoryId" => $jobCategoryId]);
            return 0;
        }
    }
    public static function CurrrentCompleteDate($jobId)
    {
        $jobCategory = JobCategory::find()
            ->where(["jobId" => $jobId, "status" => [1, 4]])
            ->orderBy('jobCategoryId DESC')
            ->asArray()
            ->one();
        $completeDate = 'Next month already';
        if (isset($jobCategory) && !empty($jobCategory)) {
            if ($jobCategory["completeDate"] != null) {  // status = 4
                $completeDate = ModelMaster::engDate($jobCategory["completeDate"], 2);
            }
        }
        return $completeDate;
    }
    public static function PreviousCompleteDate($jobId)
    {
        $jobCategory = JobCategory::find()
            ->where(["jobId" => $jobId, "status" => [1, 4]])
            ->orderBy('jobCategoryId DESC')
            ->asArray()
            ->one();
        $completeDate = '-';
        if (isset($jobCategory) && !empty($jobCategory)) {
            if ($jobCategory["completeDate"] != null) {  // status = 4
                $completeDate = ModelMaster::engDate($jobCategory["completeDate"], 2);
            }
            $currentJobCateId = $jobCategory["jobCategoryId"];
            $previous = $jobCategory = JobCategory::find()
                ->where(["jobId" => $jobId, "status" => 4])
                ->andWhere("jobCategoryId!=$currentJobCateId")
                ->orderBy('jobCategoryId DESC')
                ->asArray()
                ->one();
            if (isset($previous) && !empty($previous)) {
                if ($previous["completeDate"] != null) {
                    $previousDate = ModelMaster::engDate($previous["completeDate"], 2);
                    return $previousDate;
                }
            } else {
                return  '-';
            }

            // }
        }
        return $completeDate;
    }
    public static function CurrentTargetDate($jobId)
    {
        $targetDate = '-';
        $currentJobCategory = JobCategory::find()
            ->select('targetDate')
            ->where(["jobId" => $jobId, "status" => JobCategory::STATUS_INPROCESS])
            ->orderBy("jobCategoryId DESC")
            ->asArray()
            ->one();
        if (isset($currentJobCategory) && !empty($currentJobCategory) && $currentJobCategory["targetDate"] != null) {
            $targetDate = ModelMaster::engDate($currentJobCategory["targetDate"], 2);
        } else {
            $currentJobCategory = JobCategory::find()
                ->select('targetDate')
                ->where(["jobId" => $jobId, "status" => JobCategory::STATUS_COMPLETE])
                ->orderBy("jobCategoryId DESC")
                ->asArray()
                ->one();
            if (isset($currentJobCategory) && !empty($currentJobCategory) && $currentJobCategory["targetDate"] != null) {
                $targetDate = ModelMaster::engDate($currentJobCategory["targetDate"], 2);
            }
        }
        return $targetDate;
    }
    public static function allFiscalYear()
    {
        $year = [];
        $thisYear = date("Y");
        $start = JobCategory::find()
            ->select('MIN(fiscalYear) as fiscalYear')
            ->where("status!=99 and fiscalYear!='' and fiscalYear>2000")
            ->asArray()
            ->one();
        if (isset($start) && !empty($start)) {
            $startYear = $start["fiscalYear"];
        } else {
            $startYear = 2015;
        }
        $lastest = JobCategory::find()
            ->select('MAX(fiscalYear)  as fiscalYear')
            ->where("status!=99 and fiscalYear!='' and fiscalYear<=$thisYear")
            ->one();
        if (isset($lastest) && !empty($lastest)) {
            $lastestYear = $lastest["fiscalYear"];
        } else {
            $lastestYear = date('Y');
        }
        $i = 0;
        while ($lastestYear >= $startYear) {
            $year[$i] = $lastestYear;
            $lastestYear--;
            $i++;
        }
        return $year;
    }
    public static function TargetMonth($targetDate)
    {
        $textMonth = "";
        if ($targetDate != '' && $targetDate <= 12) {
            // $dateTimeArr = explode(" ", $targetDate);
            // $dateArr = explode("-", $dateTimeArr[0]);
            // $month = $dateArr[1];
            // $textMonth = ModelMaster::monthEng($month, 1);
            $textMonth = ModelMaster::monthEng($targetDate, 1);
        }
        return $textMonth;
    }
    public static function lastCompletDate($jobId, $jobCategoryId, $stepId, $limit)
    {
        $jobCategory = JobCategory::find()
            ->where(["jobId" => $jobId, "status" => Job::STATUS_COMPLETE])
            ->andWhere("jobCategoryId<$jobCategoryId")

            ->asArray()
            ->limit($limit)
            ->orderBy("jobCategoryId DESC")
            ->all();
        $completeDate = [];
        if (isset($jobCategory) && count($jobCategory) > 0) {
            $i = 0;
            foreach ($jobCategory as $jc) :
                $jobStep = JobStep::find()
                    ->select('completeDate')
                    ->where(["jobCategoryId" => $jc["jobCategoryId"], "stepId" => $stepId, "status" => JobStep::STATUS_COMPLETE])
                    ->asArray()
                    ->one();
                if (isset($jobStep) && !empty($jobStep)) {
                    if ($jobStep["completeDate"] != '') {
                        $completeDate[$i] = ModelMaster::dateNumber($jobStep["completeDate"]);
                    }
                }
                $i++;
            endforeach;
        }
        return $completeDate;
    }
}
