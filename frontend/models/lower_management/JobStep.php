<?php

namespace frontend\models\lower_management;

use common\models\ModelMaster;
use Exception;
use Yii;
use \frontend\models\lower_management\master\JobStepMaster;
use frontend\modules\job\job;

/**
 * This is the model class for table "job_step".
 *
 * @property integer $jobStepId
 * @property integer $jobId
 * @property integer $stepId
 * @property string $content
 * @property string $targetDate
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class JobStep extends \frontend\models\lower_management\master\JobStepMaster
{
    /**
     * @inheritdoc
     */
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
    public static function CurrentStep($jobId)
    {
        $text = '';
        $jobStep = JobStep::find()
            ->select('job_step.dueDate as dueDate,s.stepName as name,s.sort,job_step.jobStepId,job_step.firstDueDate')
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_INPROCESS])
            // ->orderBy("s.sort")
            ->orderBy("job_step.jobStepId")
            ->asArray()
            ->one();
        //if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != $jobStep["dueDate"]) {
        if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != null) {
            if ($jobStep["firstDueDate"] != null) {
                $firstDueDate = ModelMaster::engDate($jobStep["firstDueDate"], 2);
            } else {
                $firstDueDate = '';
            }
            $text .= "<div class='row'><div class='col-12 text-left mb-10'>" . $jobStep["sort"] . '. ' . $jobStep["name"] . "</div>";
            $text .= "<div class='col-6 text-left text-primary'>" .  $firstDueDate . "</div>";
            $text .= "<div class='col-6 text-right'>" . ModelMaster::engDate($jobStep["dueDate"], 2) . "</div></div>";
        } else {
            $jobStep = JobStep::find()
                ->select('job_step.dueDate as dueDate,s.stepName as name,s.sort,job_step.jobStepId,job_step.firstDueDate')
                ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_COMPLETE])
                ->orderBy("job_step.jobStepId DESC")
                ->asArray()
                ->one();
            if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != null) {
                if ($jobStep["firstDueDate"] != null) {
                    $firstDueDate = ModelMaster::engDate($jobStep["firstDueDate"], 2);
                } else {
                    $firstDueDate = '';
                }
                $text .= "<div class='row'><div class='col-12 text-left mb-10'>" . $jobStep["sort"] . '. ' . $jobStep["name"] . "</div>";
                $text .= "<div class='col-6 text-left text-primary'>" .  $firstDueDate . "</div>";
                $text .= "<div class='col-6 text-right'>" . ModelMaster::engDate($jobStep["dueDate"], 2) . "</div></div>";
            }
        }
        return $text;
    }
    public static function CurrentStepExport($jobId, $flag)
    {
        $text = '';
        $jobStep = JobStep::find()
            ->select('job_step.dueDate as dueDate,s.stepName as name,s.sort,job_step.jobStepId,job_step.firstDueDate')
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_INPROCESS])
            // ->orderBy("s.sort")
            ->orderBy("job_step.jobStepId")
            ->asArray()
            ->one();
        //if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != $jobStep["dueDate"]) {
        if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != null) {
            if ($jobStep["firstDueDate"] != null) {
                $firstDueDate = $jobStep["firstDueDate"];
            } else {
                $firstDueDate = '';
            }
            $text .= "<div class='row'><div class='col-12 text-left mb-10'>" . $jobStep["sort"] . '. ' . $jobStep["name"] . "</div>";
        } else {
            $jobStep = JobStep::find()
                ->select('job_step.dueDate as dueDate,s.stepName as name,s.sort,job_step.jobStepId,job_step.firstDueDate')
                ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_COMPLETE])
                ->orderBy("job_step.jobStepId DESC")
                ->asArray()
                ->one();
            if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != null) {
                if ($jobStep["firstDueDate"] != null) {
                    $firstDueDate = $jobStep["firstDueDate"];
                } else {
                    $firstDueDate = '';
                }
                $text .= "<div class='row'><div class='col-12 text-left mb-10'>" . $jobStep["sort"] . '. ' . $jobStep["name"] . "</div>";
            }
        }
        if ($flag == 1) {
            return $text;
        }
        if ($flag == 2) {
            return ModelMaster::dateExcel($firstDueDate);
        }
        if ($flag == 3) {
            return ModelMaster::dateExcel($jobStep["dueDate"]);
        }
    }
    public static function CurrentStepStatus($jobId, $status)
    {
        $text = "<div class='col-12 text-center text-primary'>On process</div>";

        $jobStep = JobStep::find()
            ->select('job_step.dueDate as dueDate,s.stepName as name')
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobId" => $jobId, "job_step.status" => $status])
            ->orderBy("s.sort")
            ->asArray()
            ->one();
        if (isset($jobStep) && !empty($jobStep)) {
            $targetDate = $jobStep["dueDate"];
            $targetDate = explode(' ', $jobStep["dueDate"]);
            $dueDate = $targetDate[0];

            date_default_timezone_set("Asia/Bangkok");
            $today = date("Y-m-d");
            $now = strtotime($today);
            $target = strtotime($dueDate);
            if ($now >= $target) {
                $text = "<div class='col-12 text-center text-danger'>Need to update !!!</div>";
            } else {
                if ($target > $now) {
                    $diff = $target - $now;
                    $diffDate = floor($diff / 86400); //จำนวนวันที่ต่างกัน
                    if ($diffDate <= 14) {
                        $text = "<div class='col-12 text-center text-warning'>Nearly due date !</div>";
                    }
                }
            }
            if ($status == 4) {
                $text = "<div class='col-12 text-center text-success'>Complete</div>";
            }
            if ($jobStep["dueDate"] == null) {
                $text = "<div class='col-12 text-center text-secondary'>Not set due date</div>";
            }
            //$time_diff = $now - $target;
            //$time_diff_hour = floor($time_diff % 3600);
            //$time_diff_h = floor($time_diff / 3600); // จำนวนชั่วโมงที่ต่างกัน
            //$time_diff_m = floor(($time_diff % 3600) / 60); // จำวนวนนาทีที่ต่างกัน
            //$time_diff_s = ($time_diff % 3600) % 60; // จำนวนวินาทีที่ต่างกัน
        }

        return  $text;
    }
    public static function CurrentStepList($jobId)
    {
        $text = '';
        $jobStep = JobStep::find()
            ->select('job_step.dueDate as dueDate,s.stepName as name,s.sort')
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_INPROCESS])
            ->orderBy("s.sort")
            ->asArray()
            ->one();
        //if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != $jobStep["dueDate"]) {
        if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != null) {
            $text .= "<div class='row'><div class='col-12 text-left'>" . $jobStep["sort"] . '. ' . $jobStep["name"] . "</div>";
            $text .= "<div class='col-12 text-right'>" . ModelMaster::engDate($jobStep["dueDate"], 2) . "</div></div>";
        } else {
            $jobStep = JobStep::find()
                ->select('job_step.dueDate as dueDate,s.stepName as name,s.sort')
                ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_COMPLETE])
                ->orderBy("job_step.jobStepId DESC")
                ->asArray()
                ->one();
            if (isset($jobStep) && !empty($jobStep) && $jobStep["dueDate"] != null) {
                $text .= "<div class='row'><div class='col-12 text-left'>" . $jobStep["sort"] . '. ' . $jobStep["name"] . "</div>";
                $text .= "<div class='col-12 text-right'>" . ModelMaster::engDate($jobStep["dueDate"], 2) . "</div></div>";
            }
        }
        return $text;
    }
    public static function createClass($status, $dueDate)
    {
        $class = [];
        $today = strtotime(date('Y-m-d 00:00:00'));
        $due = strtotime($dueDate);
        $over = 0;
        if ($today > $due) {
            $over = 1;
        }
        if ($dueDate == null) {
            $over = 0;
        }
        $class["over"] = $over;
        $class["class"] = "job-date";
        if ($status == JobStep::STATUS_COMPLETE && $over == 0) {
            $class["class"] = "complete-due job-date";
        }
        if ($status < JobStep::STATUS_COMPLETE && $over == 0) {
            $class["class"] = "step-due job-date";
        }
        if ($status == JobStep::STATUS_COMPLETE && $over == 1) {
            $class["class"] = "complete-due job-date";
        }
        if ($status < JobStep::STATUS_COMPLETE && $over == 1) {
            $class["class"] = "over-due job-date";
        }
        if ($dueDate == null) {
            $class["class"] = "not-set-date";
        }
        return $class;
    }
    public static function createClassText($status, $dueDate)
    {
        $today = strtotime(date('Y-m-d 00:00:00'));
        $due = strtotime($dueDate);
        $class = "";
        if ($dueDate == null) {
            return 'text-default';
        }
        if ($today > $due) {
            $class = 'text-danger';
        }
        if ($status == JobStep::STATUS_COMPLETE) {
            $class = "text-success";
        }
        if ($status == JobStep::STATUS_INPROCESS) {
            if ($due > $today) {
                $diff = $due - $today;
                $diffDate = floor($diff / 86400); //จำนวนวันที่ต่างกัน
                if ($diffDate <= 14) {
                    $class = "text-warning";
                } else {
                    $class = "text-primary";
                }
            } else {
                $class = "text-danger";
            }
        }
        return $class;
    }
    public static function statusText($status)
    {
        $text = '';
        if ($status == JobStep::STATUS_INPROCESS) {
            $text = 'Inprocess';
        }
        if ($status == JobStep::STATUS_COMPLETE) {
            $text = 'Complete';
        }
        return $text;
    }
    public static function stepComplete($jobId)
    {
        $jobStep = JobStep::find()
            ->select("job_step.status")
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_INPROCESS])
            ->andWhere("s.status!=" . Step::STATUS_DISABLE)
            ->asArray()
            ->one();
        //$jobCate = JobCategory::find()->where(["jobId" => $jobId, "status" => JobCategory::STATUS_INPROCESS])->one();
        if (isset($jobStep) && !empty($jobStep)) {
            return 0;
        } else {
            $additionalStep = AdditionalStep::find()->where(["status" => 1, "jobId" => $jobId])->one();
            if (isset($additionalStep) && !empty($additionalStep)) {
                return 0;
            } else {
                return 1;
            }
        }
    }
    public static function CurrentStepEmail($jobId)
    {
        $text = '';
        $jobStep = JobStep::find()
            ->select('job_step.dueDate as dueDate,s.stepName as name')
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_INPROCESS])
            ->orderBy("s.sort")
            ->asArray()
            ->one();
        if (isset($jobStep) && !empty($jobStep)) {
            $dueDate = explode(" ", $jobStep["dueDate"]);
            $text =  $dueDate[0] . " (" . $jobStep["name"] . ")";
        } else {
            $text = 'Complete';
        }
        return $text;
    }
    public static function isShowNextStep($jobStepId)
    {
        $jobStep = JobStep::find()->where(["jobStepId" => $jobStepId])->one(); //17171
        $step = Step::find()->where(["stepId" => $jobStep->stepId])->one(); //stepId=3483
        if ($step->sort != '') {
            $isFirstStep = Step::find()
                ->where("jobTypeId=" . $step->jobTypeId . " and sort<" . $step->sort . " and status=1")
                ->orderby('sort DESC')
                ->one(); //stepId=3482
        }
        if (isset($isFirstStep) && !empty($isFirstStep)) {
            $lastJobStep = JobStep::find()
                ->select('job_step.status,job_step.jobCategoryId,job_step.stepId')
                ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
                ->where(["job_step.jobCategoryId" => $jobStep->jobCategoryId])
                ->andWhere("s.sort<$step->sort and s.status!=99 and job_step.status!=99")
                //->andWhere("jobStepId<$jobStepId and status!=99")
                //->orderBy("jobStepId DESC")
                ->orderBy('s.sort DESC')
                ->one(); //17170
            if (isset($lastJobStep) && !empty($lastJobStep)) {
                if ($lastJobStep->status == 1) {
                    return 0;
                } else if ($lastJobStep->status == 4) {
                    $additionalStep = AdditionalStep::find()
                        ->where(["status" => 1, "jobCategoryId" => $lastJobStep->jobCategoryId, "stepId" => $lastJobStep->stepId])
                        ->one();
                    if (isset($additionalStep) && !empty($additionalStep)) {
                        return 0; //not show next step check box
                    } else {
                        return 1;
                    }
                }
            } else {
                return 1;
            }
        } else {
            return 1; //first step--> show
        }
    }
    public static function CurrentStepStatusProfile($jobId)
    {
        $jobStep = JobStep::find()
            ->select('job_step.dueDate as dueDate,s.stepName as name,s.sort,job_step.jobStepId')
            ->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
            ->where(["job_step.jobId" => $jobId, "job_step.status" => JobStep::STATUS_INPROCESS])
            // ->orderBy("s.sort")
            ->orderBy("job_step.jobStepId")
            ->asArray()
            ->one();
        $result = [];
        if (isset($jobStep) && !empty($jobStep)) {
            $dueDate = date("Y-m-d");
            if ($jobStep["dueDate"] != '') {
                $dueDateArr = explode(' ', $jobStep["dueDate"]);
                $dueDate = $dueDateArr[0];
            }
            date_default_timezone_set("Asia/Bangkok");
            $today = date("Y-m-d");
            $now = strtotime($today);
            $target = strtotime($dueDate);
            if ($now >= $target) {
                $result = [
                    "status" => "need",
                    "dueDate" => ModelMaster::engDate($jobStep["dueDate"], 2)
                ];
            } else {
                if ($target > $now) {
                    $diff = $target - $now;
                    $diffDate = floor($diff / 86400); //จำนวนวันที่ต่างกัน
                    if ($diffDate <= 14) {
                        $result = [
                            "status" => "nearly",
                            "dueDate" => ModelMaster::engDate($jobStep["dueDate"], 2)
                        ];
                    } else {
                        $result = [
                            "status" => "inprocess",
                            "dueDate" => ModelMaster::engDate($jobStep["dueDate"], 2)
                        ];
                    }
                }
            }
        }
        return $result;
    }
    public static function currentCompleteStep($jobId, $selectMonth, $selectYear)
    {
        if ($selectYear != null) {
            $year = $selectYear;
        } else {
            $year = FiscalYear::currentFiscalYear();
        }
        $jobCategory = JobCategory::find()
            ->where(["jobId" => $jobId, "fiscalYear" => $year])
            ->andFilterWhere(["startMonth" => $selectMonth])
            ->orderBy('jobCategoryId DESC')
            ->asArray()
            ->one();
        $current = [];
        if (isset($jobCategory) && !empty($jobCategory)) {
            $jobStep = JobStep::find()
                ->where(["jobCategoryId" => $jobCategory["jobCategoryId"], "status" => JobStep::STATUS_COMPLETE])
                ->asArray()
                ->one();
            if (isset($jobStep) && !empty($jobStep)) {
                $currentCompleteStep = JobStep::find()
                    ->where(["jobCategoryId" => $jobCategory["jobCategoryId"], "status" => JobStep::STATUS_COMPLETE])
                    ->orderBy('jobStepId DESC')
                    ->asArray()
                    ->one();
            } else {
                $jobStep = JobStep::find()
                    ->where(["jobCategoryId" => $jobCategory["jobCategoryId"], "status" => JobStep::STATUS_INPROCESS])
                    ->asArray()
                    ->one();
                if (isset($jobStep) && !empty($jobStep)) {
                    $currentCompleteStep = JobStep::find()
                        ->where(["jobCategoryId" => $jobCategory["jobCategoryId"], "status" => JobStep::STATUS_INPROCESS])
                        ->orderBy('jobStepId ASC')
                        ->asArray()
                        ->one();
                }
            }
        }
        if (isset($currentCompleteStep) && !empty($currentCompleteStep)) {
            // throw new exception(print_r($currentCompleteStep, true));
            $current = [
                "stepId" => $currentCompleteStep["stepId"],
                "status" => $currentCompleteStep["status"]
            ];
        }
        return $current;
    }
}
