<?php

namespace frontend\models\lower_management;

use Exception;
use Yii;
use \frontend\models\lower_management\master\JobMaster;
use frontend\modules\job\job as JobJob;

/**
 * This is the model class for table "job".
 *
 * @property integer $jobId
 * @property string $jobNumber
 * @property integer $clientId
 * @property integer $categoryId
 * @property integer $fieldId
 * @property integer $jobTypeId
 * @property integer $teamId
 * @property integer $pIc1
 * @property integer $pIc2
 * @property string $fee
 * @property string $advanceReceivable
 * @property string $chargeMonth
 * @property string $outsourcingFee
 * @property string $startDate
 * @property string $targetDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Job extends \frontend\models\lower_management\master\JobMaster
{
    /**
     * @inheritdoc
     */
    public $jcTargetDate;
    public $completeDate;
    public $jobCategoryId;
    public $jsJobStepId;
    public $jsStepId;
    public $jobStepId;
    public $jsStatus;
    public $dueDate;
    public $minjs;
    public $maxjs;
    public $clientName;
    public $jsCompleteDate;
    const STATUS_INPROCESS = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECT = 3;
    const STATUS_COMPLETE = 4;
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
    public static function jobResponsibility($jobId, $res)
    {
        $text = '';
        $jobResponse = JobResponsibility::find()
            ->select('job_responsibility.percentage as percent,e.employeeNickName as nickname')
            ->JOIN("LEFT JOIN", "employee e", "e.employeeId=job_responsibility.employeeId")
            ->where(["job_responsibility.jobId" => $jobId, "job_responsibility.responsibility" => $res])
            ->asArray()
            ->all();
        if (count($jobResponse) > 0) {
            foreach ($jobResponse as $jr) :
                $text .= '<div class="col-12 text-left">' . $jr["nickname"] . '</div>';
            // <div class="col-5 text-right">' . $jr["percent"] . ' %</div>'
            endforeach;
        } else {
            $text = '<div class="col-12 text-center"> - </div>';
        }
        return $text;
    }
    public static function jobResponsibilityExcel($jobId, $res)
    {
        $text = '';
        $jobResponse = JobResponsibility::find()
            ->select('job_responsibility.percentage as percent,e.employeeNickName as nickname')
            ->JOIN("LEFT JOIN", "employee e", "e.employeeId=job_responsibility.employeeId")
            ->where(["job_responsibility.jobId" => $jobId, "job_responsibility.responsibility" => $res])
            ->asArray()
            ->all();
        $total = count($jobResponse);
        if (count($jobResponse) > 0) {
            $i = 1;
            foreach ($jobResponse as $jr) :
                $text .=   $jr["nickname"];
                // <div class="col-5 text-right">' . $jr["percent"] . ' %</div>'
                if ($total > 1 && $i < $total) {
                    $text .= ', ';
                }
                $i++;
            endforeach;
        } else {
            $text = '-';
        }
        return $text;
    }
    public static function getDateJobs($date)
    {
        $value = [];
        $employeeType = EmployeeType::findEmployeeType();
        $rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
        $fag = 0;
        $teamId = Employee::employeeTeam();
        if (count($employeeType) > 0) {
            foreach ($employeeType as $all) :
                if (in_array($all, $rightAll)) {
                    $fag = 1;
                }
            endforeach;
        }
        if ($fag == 1) {
            $jobCate = JobCategory::find()
                ->select('j.jobName,c.clientName,j.jobId,job_category.jobCategoryId as jobCateId,job_category.targetDate as target,job_category.status as jStatus')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
                ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                ->where(["job_category.targetDate" => $date])
                ->andWhere("job_category.status<=" . JobCategory::STATUS_COMPLETE)
                ->andWhere("j.status!=" . Job::STATUS_DELETED)
                ->asArray()
                ->all();
        } else {
            $jobCate = JobCategory::find()
                ->select('j.jobName,c.clientName,j.jobId,job_category.jobCategoryId as jobCateId,job_category.targetDate as target,job_category.status as jStatus')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
                ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                ->where(["job_category.targetDate" => $date, "j.teamId" => $teamId])
                ->andWhere("job_category.status<=" . JobCategory::STATUS_COMPLETE)
                ->andWhere("j.status!=" . Job::STATUS_DELETED)
                ->asArray()
                ->all();
        }
        if (isset($jobCate) && count($jobCate) > 0) {
            foreach ($jobCate as $job) :
                $value["target"][$job["jobCateId"]] = [
                    "targetDate" => $job["target"],
                    "jobName" => $job["jobName"],
                    "clientName" => $job["clientName"],
                    "jobId" => $job["jobId"],
                    "status" => $job["jStatus"]
                ];
            endforeach;
        }
        if ($fag == 1) {
            $jobStep = JobStep::find()
                ->select('j.jobName,c.clientName,j.jobId,job_step.jobStepId,job_step.dueDate,job_step.status as jStatus')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                ->where(["job_step.dueDate" => $date])
                ->andWhere("job_step.status<=" . JobStep::STATUS_COMPLETE)
                ->andWhere("j.status!=" . Job::STATUS_DELETED)
                ->asArray()
                ->all();
        } else {
            $jobStep = JobStep::find()
                ->select('j.jobName,c.clientName,j.jobId,job_step.jobStepId,job_step.dueDate,job_step.status as jStatus')
                ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                ->where(["job_step.dueDate" => $date, "j.teamId" => $teamId])
                ->andWhere("job_step.status<=" . JobStep::STATUS_COMPLETE)
                ->andWhere("j.status!=" . Job::STATUS_DELETED)
                ->asArray()
                ->all();
        }
        if (isset($jobStep) && count($jobStep) > 0) {
            foreach ($jobStep as $js) :
                $value["due"][$js["jobStepId"]] = [
                    "dueDate" => $js["dueDate"],
                    "jobName" => $js["jobName"],
                    "clientName" => $js["clientName"],
                    "jobId" => $js["jobId"],
                    "status" => $js["jStatus"],
                ];
            endforeach;
        }

        return $value;
        //throw new Exception(print_r($value, true));
    }
    public static function getDateJobsFilter($date, $filter, $stepCheck, $finalCheck)
    {
        $value = [];
        $employeeType = EmployeeType::findEmployeeType();
        $rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
        $fag = 0;
        $teamId = Employee::employeeTeam();
        if (count($rightAll) > 0) {
            foreach ($employeeType as $all) :
                if (in_array($all, $rightAll)) {
                    $fag = 1;
                }
            endforeach;
        }
        $jobId = '';
        if ($filter["status"] == 9 || $filter["status"] == 10) {
            $status = Job::STATUS_INPROCESS;
            $jobId = Job::findJobIdByStatus($filter["status"], $filter["branchId"]);
            if ($jobId != '') {
                $sql = "j.jobId in ($jobId) and j.status=" . Job::STATUS_INPROCESS;
            } else {
                $sql = "j.jobId=0 and j.status=" . Job::STATUS_INPROCESS;
            }
        } else {
            if ($filter["status"] == 1) {
                $status = Job::STATUS_INPROCESS;
                $jobId = Job::findJobIdByStatus($filter["status"], $filter["branchId"]);

                if ($jobId != '') {
                    $sql = "j.status=" . $status . " and j.jobId not in ($jobId)";
                } else {
                    $sql = "j.status=" . $status;
                }
            } else {
                $sql = "j.status=" . $filter["status"];
            }
        }

        if ($filter["status"] == Job::STATUS_COMPLETE) {
            $sql = "j.status=" . $filter["status"];
        }
        if ($filter["status"] == '') {
            $sql = '';
        }

        if (isset($filter["personId"]) && $filter["personId"] != '') {
            if ($finalCheck == 1) {
                if ($fag == 1) {
                    $jobCate = JobCategory::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_category.jobCategoryId as jobCateId,job_category.targetDate as target,job_category.status as jStatus')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
                        ->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=j.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->where(["job_category.targetDate" => $date, "jr.employeeId" => $filter["personId"]])
                        ->andWhere("job_category.status<=" . JobCategory::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->andFilterWhere(["j.teamId" => $filter["teamId"]])
                        ->orderBy('c.clientName,j.jobName')
                        ->asArray()
                        ->all();
                } else {
                    $jobCate = JobCategory::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_category.jobCategoryId as jobCateId,job_category.targetDate as target,job_category.status as jStatus')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
                        ->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=j.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->where(["job_category.targetDate" => $date, "jr.employeeId" => $filter["personId"], "j.teamId" => $teamId])
                        ->andWhere("job_category.status<=" . JobCategory::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->orderBy('c.clientName,j.jobName')
                        ->asArray()
                        ->all();
                }
            }
            if ($stepCheck == 1) {
                if ($fag == 1) {
                    $jobStep = JobStep::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_step.jobStepId,job_step.dueDate,job_step.status as jStatus,s.stepName,s.sort')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                        ->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=j.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                        ->where(["job_step.dueDate" => $date, "jr.employeeId" => $filter["personId"]])
                        ->andWhere("job_step.status<=" . JobStep::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->andFilterWhere(["j.teamId" => $filter["teamId"]])
                        ->orderBy('c.clientName,j.jobName,s.sort')
                        ->asArray()
                        ->all();
                } else {
                    $jobStep = JobStep::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_step.jobStepId,job_step.dueDate,job_step.status as jStatus,s.stepName,s.sort')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                        ->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=j.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                        ->where(["job_step.dueDate" => $date, "jr.employeeId" => $filter["personId"], "j.teamId" => $teamId])
                        ->andWhere("job_step.status<=" . JobStep::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->orderBy('c.clientName,j.jobName,s.sort')
                        ->asArray()
                        ->all();
                }
            }
        } else {
            if ($finalCheck == 1) {
                if ($fag == 1) {
                    $jobCate = JobCategory::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_category.jobCategoryId as jobCateId,job_category.targetDate as target,job_category.status as jStatus')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->where(["job_category.targetDate" => $date])
                        ->andWhere("job_category.status<=" . JobCategory::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->andFilterWhere(["j.teamId" => $filter["teamId"]])
                        ->orderBy('c.clientName,j.jobName')
                        ->asArray()
                        ->all();
                } else {
                    $jobCate = JobCategory::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_category.jobCategoryId as jobCateId,job_category.targetDate as target,job_category.status as jStatus')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->where(["job_category.targetDate" => $date, "j.teamId" => $teamId])
                        ->andWhere("job_category.status<=" . JobCategory::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->orderBy('c.clientName,j.jobName')
                        ->asArray()
                        ->all();
                }
            }
            if ($stepCheck == 1) {
                if ($fag == 1) {
                    $jobStep = JobStep::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_step.jobStepId,job_step.dueDate,job_step.status as jStatus,s.stepName,s.sort')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                        ->where(["job_step.dueDate" => $date])
                        ->andWhere("job_step.status<=" . JobStep::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->andFilterWhere(["j.teamId" => $filter["teamId"]])
                        ->orderBy('c.clientName,j.jobName,s.sort')
                        ->asArray()
                        ->all();
                } else {
                    $jobStep = JobStep::find()
                        ->select('j.jobName,c.clientName,j.jobId,job_step.jobStepId,job_step.dueDate,job_step.status as jStatus,s.stepName,s.sort')
                        ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                        ->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
                        ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                        ->where(["job_step.dueDate" => $date, "j.teamId" => $teamId])
                        ->andWhere("job_step.status<=" . JobStep::STATUS_COMPLETE)
                        ->andWhere("j.status!=" . Job::STATUS_DELETED)
                        ->andWhere($sql)
                        ->andFilterWhere(["j.branchId" => $filter["branchId"]])
                        ->andFilterWhere(["j.categoryId" => $filter["categoryId"]])
                        ->andFilterWhere(["j.fieldId" => $filter["fieldId"]])
                        ->orderBy('c.clientName,j.jobName,s.sort')
                        ->asArray()
                        ->all();
                }
            }
        }

        if (isset($jobCate) && count($jobCate) > 0) {
            foreach ($jobCate as $job) :
                $value["target"][$job["jobCateId"]] = [
                    "targetDate" => $job["target"],
                    "jobName" => $job["jobName"],
                    "clientName" => $job["clientName"],
                    "jobId" => $job["jobId"],
                    "status" => $job["jStatus"]
                ];
            endforeach;
        }
        if (isset($jobStep) && count($jobStep) > 0) {
            foreach ($jobStep as $js) :
                $value["due"][$js["jobStepId"]] = [
                    "dueDate" => $js["dueDate"],
                    "jobName" => $js["jobName"],
                    "clientName" => $js["clientName"],
                    "jobId" => $js["jobId"],
                    "status" => $js["jStatus"],
                    "stepName" => $js["stepName"],
                    "sort" => $js["sort"]
                ];
            endforeach;
        }

        return $value;
    }
    public static function statusText($jobId)
    {
        $text = "";
        $job = Job::find()->select("status")->where(["jobId" => $jobId])->asArray()->one();
        if ($job["status"] == Job::STATUS_INPROCESS) {
            $text = "Inprocess";
        }
        if ($job["status"] == Job::STATUS_COMPLETE) {
            $text = "Complete";
        }
        return $text;
    }
    public static function clientFee($clientId)
    {
        $total = 0;
        $jobs = Job::find()->select('fee,categoryId')->where(["clientId" => $clientId])->asArray()->all();
        if (isset($jobs) && count($jobs) > 0) {
            foreach ($jobs as $job) :
                $total +=  Category::muliplyfee($job["categoryId"]) * $job["fee"];
            endforeach;
        }
        return $total;
    }
    public static function clientFeeComplete($clientId)
    {
        $total = 0;
        $jobs = Job::find()->select('fee,categoryId')->where(["clientId" => $clientId, "status" => Job::STATUS_COMPLETE])->asArray()->all();
        if (isset($jobs) && count($jobs) > 0) {
            foreach ($jobs as $job) :
                $total +=  Category::muliplyfee($job["categoryId"]) * $job["fee"];
            endforeach;
        }
        return $total;
    }
    public static function calculateClientAmount($jobIdArr, $complete)
    {
        $total = 0;
        if (count($jobIdArr) > 0) {
            if ($complete == 0) {
                $jobs = Job::find()
                    ->select('fee,categoryId')
                    ->where(["in", "jobId", $jobIdArr])
                    ->asArray()
                    ->all();
            } else {
                $jobs = Job::find()
                    ->select('fee,categoryId')
                    ->where(["in", "jobId", $jobIdArr])
                    ->andWhere(["status" => Job::STATUS_COMPLETE])
                    ->asArray()
                    ->all();
            }
            if (isset($jobs) && count($jobs) > 0) {
                foreach ($jobs as $job) :
                    $total +=  Category::muliplyfee($job["categoryId"]) * $job["fee"];
                endforeach;
            }
        }
        return $total;
    }
    public static function IsHaveComplete($jobId)
    {
        $have = 0;
        $job = Job::find()
            ->select('c.categoryName')
            ->JOIN("LEFT JOIN", "category c", "c.categoryId=job.categoryId")
            ->where(["job.jobId" => $jobId])
            ->asArray()
            ->one();
        if ($job["categoryName"] == "Monthly" || $job["categoryName"] == "Yearly") {
            $jobCate = JobCategory::find()
                ->select('jobCategoryId')
                ->where(["jobId" => $jobId, "status" => JobCategory::STATUS_COMPLETE])
                ->one();
            if (isset($jobCate) && !empty($jobCate)) {
                $have = 1;
            }
        }
        return $have;
    }
    public static function jobName($jobId)
    {
        $job = Job::find()->select('jobName')->where(["jobId" => $jobId])->asArray()->one();
        return $job["jobName"];
    }
    public static function findJobIdByStatus($jobStatus, $branchId)
    {
        $jobIdNearly = '';
        $jobIdNeed = '';
        $jobIdNearly9 = '';
        $jobIdNeed10 = '';
        $jobIdOnprocess = '';
        $jobId = '';
        $jobIdNo = '';
        throw new Exception(print_r($jobStatus, true));
        if ($branchId != "") {
            $job = JobStep::find()
                ->select('job_step.jobId,job_step.dueDate')
                ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                ->where(["job_step.status" => JobStep::STATUS_INPROCESS, "j.branchId" => $branchId])
                ->andWhere("j.status=" . Job::STATUS_INPROCESS)
                ->orderBy("s.sort")
                ->groupBy("job_step.jobId")
                ->asArray()
                ->all();
        } else {
            $job = JobStep::find()
                ->select('job_step.jobId,job_step.dueDate')
                ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                ->where(["job_step.status" => JobStep::STATUS_INPROCESS])
                ->andWhere("j.status=" . Job::STATUS_INPROCESS)
                ->orderBy("s.sort")
                ->groupBy("job_step.jobId")
                ->asArray()
                ->all();
        }
        if (isset($job) && count($job) > 0) {
            foreach ($job as $j) :
                $targetDate = $j["dueDate"];
                $targetDate = explode(' ', $j["dueDate"]);
                $dueDate = $targetDate[0];
                date_default_timezone_set("Asia/Bangkok");
                $today = date("Y-m-d");
                $now = strtotime($today);
                $target = strtotime($dueDate);
                if ($jobStatus == 9) {
                    if ($target > $now) {
                        $diff = $target - $now;
                        $diffDate = floor($diff / 86400); //จำนวนวันที่ต่างกัน
                        if ($diffDate <= 14) {
                            $jobIdNearly .= $j["jobId"] . ",";
                        }
                    }
                }
                if ($jobStatus == 10) {
                    if ($now >= $target) {
                        $jobIdNeed .= $j["jobId"] . ",";
                    }
                }
                if ($jobStatus == 1) {
                    if ($target > $now) {
                        $diff = $target - $now;
                        $diffDate = floor($diff / 86400); //จำนวนวันที่ต่างกัน
                        if ($diffDate <= 14) {
                            $jobIdNearly9 .= $j["jobId"] . ",";
                        }
                    }
                    if ($now >= $target) {
                        $jobIdNeed10 .= $j["jobId"] . ",";
                    }
                }
            endforeach;
        }
        if ($jobIdNearly != '') {
            $jobId = substr($jobIdNearly, 0, -1);
        }
        if ($jobIdNeed != '') {
            $jobId = substr($jobIdNeed, 0, -1);
        }
        //===========================================for status onprocess=============================
        if ($jobIdNearly9 != '') {
            $jobIdNearly9 = substr($jobIdNearly9, 0, -1);
        }
        if ($jobIdNeed10 != '') {
            $jobIdNeed10 = substr($jobIdNeed10, 0, -1);
        }
        if ($jobIdNearly9 != '' && $jobIdNeed10 != '') {
            $jobIdNo = $jobIdNearly9 . ',' . $jobIdNeed10;
        }
        if ($jobIdNearly9 != '' && $jobIdNeed10 == '') {
            $jobIdNo = $jobIdNearly9;
        }
        if ($jobIdNearly9 == '' && $jobIdNeed10 != '') {
            $jobIdNo = $jobIdNeed10;
        }
        if ($jobIdNearly9 == '' && $jobIdNeed10 == '') {
            $jobIdNo = '';
        }
        //==========================================================================================
        if ($jobStatus == 1) {
            return $jobIdNo;
        } else {
            return $jobId;
        }
    }
    public static function findJobIdByStatus2($jobStatus, $branchId)
    {
        $jobIdNearly = '';
        $jobIdNeed = '';
        $jobIdOnprocess = '';
        $jobId = '';
        $jobIdComplete = '';
        $jobs = [];
        $returnJobId = '';
        foreach ($jobStatus as $status) :
            if ($status == 1 || $status == 9 || $status == 10) {
                $job = JobStep::find()
                    ->select('job_step.jobId,job_step.dueDate,j.status as jStatus')
                    ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                    ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                    ->where(["job_step.status" => JobStep::STATUS_INPROCESS])
                    ->andWhere("j.status=" . Job::STATUS_INPROCESS)
                    ->andFilterWhere(["j.branchId" => $branchId])
                    ->orderBy("s.sort")
                    ->groupBy("job_step.jobId")
                    ->asArray()
                    ->all();
            } else {
                $job = JobStep::find()
                    ->select('job_step.jobId,job_step.dueDate,j.status as jStatus')
                    ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                    ->JOIN("LEFT JOIN", "job j", "j.jobId=job_step.jobId")
                    ->where(["job_step.status" => JobStep::STATUS_COMPLETE])
                    ->andWhere("j.status=" . Job::STATUS_COMPLETE)
                    ->andFilterWhere(["j.branchId" => $branchId])
                    ->orderBy("s.sort DESC")
                    ->groupBy("job_step.jobId")
                    ->asArray()
                    ->all();
            }
            if (isset($job) && count($job) > 0) {
                foreach ($job as $j) :
                    $jobs[$j["jobId"]] = [
                        "dueDate" => $j["dueDate"],
                        "jStatus" => $j["jStatus"],
                    ];
                endforeach;
            }
        endforeach;
        if (isset($jobs) && count($jobs) > 0) {
            foreach ($jobs as $jobId => $j) :
                $targetDate = $j["dueDate"];
                $targetDate = explode(' ', $j["dueDate"]);
                $dueDate = $targetDate[0];
                date_default_timezone_set("Asia/Bangkok");
                $today = date("Y-m-d");
                $now = strtotime($today);
                $target = strtotime($dueDate);

                if ($target > $now) {
                    $diff = $target - $now;
                    $diffDate = floor($diff / 86400); //จำนวนวันที่ต่างกัน
                    if ($diffDate <= 14) { //nearly
                        if (in_array(9, $jobStatus)) {
                            $jobIdNearly .= $jobId . ",";
                        }
                    } else {
                        if (in_array(1, $jobStatus)) {
                            $jobIdOnprocess .= $jobId . ",";
                        }
                    }
                }
                if ($now >= $target) { //need
                    if (in_array(10, $jobStatus)) {
                        $jobIdNeed .= $jobId . ",";
                    }
                }
                if ($j["jStatus"] == Job::STATUS_COMPLETE) {
                    $jobIdComplete .= $jobId . ",";
                }

            endforeach;
        }

        if ($jobIdNearly != '') {
            $returnJobId .= $jobIdNearly;
        }
        if ($jobIdNeed != '') {
            $returnJobId .= $jobIdNeed;
        }
        if ($jobIdOnprocess != '') {
            $returnJobId .= $jobIdOnprocess;
        }
        if ($jobIdComplete != '') {
            $returnJobId .= $jobIdComplete;
        }
        if ($returnJobId != '') {
            $returnJobId = substr($returnJobId, 0, -1);
        }
        return $returnJobId;
    }
    public static function checkStatus($statusArray, $check)
    {
        if (count($statusArray) > 0) {
            foreach ($statusArray as $status) :
                if ($status == $check) {
                    return 1;
                }
            endforeach;
        }
        return 0;
    }
    public static function clientName($jobId)
    {
        $client = Client::find()
            ->select('client.clientName')
            ->JOIN("LEFT JOIN", "job j", "j.clientId=client.clientId")
            ->where(["j.jobId" => $jobId])
            ->asArray()
            ->one();
        if (isset($client) && !empty($client)) {
            return $client["clientName"];
        } else {
            return 'not found';
        }
    }
    public static function totalJob($branchId, $year)
    {
        $job = Job::find()
            ->select('job.jobId')
            ->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
            ->where(["jc.status" => [1, 4], "job.status" => [1, 4], "job.branchId" => $branchId, "jc.fiscalYear" => $year])
            ->asArray()
            ->groupBy("job.jobId")
            ->all();
        return count($job);
    }
    public static function CalculateFee($branchId, $year)
    {
        $jobs = Job::find()
            ->select('job.fee,job.categoryId')
            ->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
            ->where(["jc.status" => [1, 4], "job.status" => [1, 4], "job.branchId" => $branchId, "jc.fiscalYear" => $year])
            ->asArray()
            ->all();
        $total = 0;
        if (isset($jobs) && count($jobs) > 0) {
            foreach ($jobs as $job) :
                $total += Category::muliplyfee($job["categoryId"]) * $job["fee"];
            endforeach;
        }
        return $total;
    }
    public static function checkList($branchId)
    {
        $job = Job::find()
            ->where(["branchId" => $branchId])
            ->andWhere("checkListPath is not null")
            ->all();
        return count($job);
    }
    public static function manual($branchId)
    {
        $job = Job::find()
            ->where(["branchId" => $branchId])
            ->andWhere("trim(url)!=''")
            ->all();
        return count($job);
    }
    public static function countJobTypeJob($branchId, $jobTypeId, $status, $fiscalYears, $currentMonthValue, $clientId, $fieldId, $categoryId, $teamId)
    {
        /*$job = Job::find()
            ->select('job.jobId')
            ->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
            ->where([
                "job.branchId" => $branchId,
                "job.jobTypeId" => $jobTypeId,
                "job.status" => $status,
                "jc.status" => [1, 4],
                //"jc.status" => $status,
                //"job.status" => [1, 4],
                "jc.fiscalYear" => $fiscalYears
            ])
            ->andFilterWhere(
                [
                    "job.fieldId" => $fieldId,
                    "job.categoryId" => $categoryId,
                    "job.clientId" => $clientId,
                    "job.teamId" => $teamId,
                    "jc.startMonth" => $currentMonthValue
                ]

            )
            ->all();
        return count($job);*/
        $job = Job::find()->select('MAX(jc.jobCategoryId),MIN(jc.status) as jcstatus,jc.jobId')
            ->JOIN("RIGHT JOIN", "job_category jc", "jc.jobId=job.jobId")
            ->where([
                "job.status" => [1, 4],
                "job.branchId" => $branchId,
                "job.jobTypeId" => $jobTypeId,
                "jc.fiscalYear" => $fiscalYears
            ])
            ->andFilterWhere(
                [
                    "job.fieldId" => $fieldId,
                    "job.categoryId" => $categoryId,
                    "job.clientId" => $clientId,
                    "job.teamId" => $teamId,
                    "jc.startMonth" => $currentMonthValue
                ]
            )
            ->groupBy('jc.jobId')
            ->orderBy('jc.createDateTime DESC')
            ->asArray()
            ->all();
        //throw new exception(print_r($job, true));
        $count = 0;
        if (isset($job) && count($job) > 0) {
            foreach ($job as $j) :
                if ($j["jcstatus"] == $status) {
                    $count++;
                }
            endforeach;
        }
        return $count;
    }
    public static function countJobTypeJobSearch($branchId, $jobTypeId, $status, $fiscalYears, $currentMonthValue, $clientId, $fieldId, $categoryId, $teamId)
    {
        $job = Job::find()->select('MAX(jc.jobCategoryId),MIN(jc.status) as jcstatus,jc.jobId')
            ->JOIN("RIGHT JOIN", "job_category jc", "jc.jobId=job.jobId")
            ->where([
                "job.status" => [1, 4],
                "job.branchId" => $branchId,
                "job.jobTypeId" => $jobTypeId,
                "jc.fiscalYear" => $fiscalYears
            ])
            ->andFilterWhere(
                [
                    "job.fieldId" => $fieldId,
                    "job.categoryId" => $categoryId,
                    "job.clientId" => $clientId,
                    "job.teamId" => $teamId,
                    "jc.startMonth" => $currentMonthValue
                ]
            )
            ->groupBy('jc.jobId')
            ->orderBy('jc.createDateTime DESC')
            ->asArray()
            ->all();
        //throw new exception(print_r($job, true));
        $jobId = [];
        if (isset($job) && count($job) > 0) {
            $index = 0;
            foreach ($job as $j) :
                if ($j["jcstatus"] == $status) {
                    $jobId[$index] = $j["jobId"];
                    $index++;
                }
            endforeach;
        }
        return $jobId;
    }
    public static function checkAdditional($jobId)
    {
        $job = Job::find()->where(["jobId" => $jobId])->one();
        $point = 0;
        if ($job->checkListPath != null) {
            $point = 1;
        }
        if ($job->url != null) {
            $point = 2;
        }
        if ($job->url != null && $job->checkListPath != null) {
            $point = 3;
        }
        return $point;
    }
}
