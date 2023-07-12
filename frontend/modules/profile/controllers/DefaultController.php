<?php

namespace frontend\modules\profile\controllers;

use Exception;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\Kpi;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `profile` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        $employee = Employee::find()->where(["employeeId" => Yii::$app->user->id])->asArray()->one();
        $needs = [];
        $nearlies = [];
        $inprocess = [];
        $completes = [];
        $jobs = Job::find()
            ->select('job.jobId,job.jobName,job.status,c.clientName')
            ->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
            ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
            ->where([
                "job.status" => [1, 4],
                "jr.responsibility" => [2, 3, 4, 5],
                "jr.employeeId" => Yii::$app->user->id
            ])
            ->orderBy('job.jobName')
            ->asArray()
            ->all();
        if (isset($jobs) && count($jobs) > 0) {
            foreach ($jobs as $job) :
                if ($job["status"] == Job::STATUS_COMPLETE) {
                    $completes[$job["jobId"]] = [
                        "jobName" => $job["jobName"],
                        "clientName" =>  $job["clientName"],
                    ];
                } else {
                    $currrentStep = JobStep::CurrentStepStatusProfile($job["jobId"]);
                    if (isset($currrentStep["status"]) && $currrentStep["status"] == "need") {
                        $needs[$job["jobId"]] = [
                            "jobName" => $job["jobName"],
                            "clientName" =>  $job["clientName"],
                            "dueDate" => $currrentStep["dueDate"]
                        ];
                    }
                    if (isset($currrentStep["status"]) && $currrentStep["status"] == "nearly") {
                        $nearlies[$job["jobId"]] = [
                            "jobName" => $job["jobName"],
                            "clientName" =>  $job["clientName"],
                            "dueDate" => $currrentStep["dueDate"]
                        ];
                    }
                    if (isset($currrentStep["status"]) && $currrentStep["status"] == "inprocess") {
                        $inprocess[$job["jobId"]] = [
                            "jobName" => $job["jobName"],
                            "clientName" =>  $job["clientName"],
                            "dueDate" => $currrentStep["dueDate"]
                        ];
                    }
                }
            endforeach;
        }
        // $kpi = Kpi::personalKpi(Yii::$app->user->id);
        $inHand = count($jobs);
        return $this->render('index', [
            "employee" => $employee,
            "needs" => $needs,
            "nearlies" => $nearlies,
            "inprocess" => $inprocess,
            "completes" => $completes,
            "inHand" => $inHand,
            // "kpi" => $kpi
        ]);
    }
    public function actionChangePassword()
    {
        $employee = Employee::find()->where(["employeeId" => Yii::$app->user->id])->asArray()->one();

        if (isset($_POST["oldPassword"]) && trim($_POST["oldPassword"]) != '') {
            $res["status"] = false;
            $res["text"] = '';
            $oldPass = md5($_POST["oldPassword"]);
            if ($_POST["conFirmPassword"] == $_POST["newPassword"] && $employee["password_hash"] == $oldPass) {
                $changePass = Employee::find()->where(["employeeId" => Yii::$app->user->id])->one();
                $changePass->password_hash = md5($_POST["newPassword"]);
                if ($changePass->save(false)) {
                    $res["status"] = true;
                }
            } else {
                if ($employee["password_hash"] != $oldPass) {
                    $res["text"] = 'The old password is incorrect';
                }
                if ($_POST["newPassword"] != $_POST["conFirmPassword"]) {
                    $res["text"] = "Confirm password doesn't match";
                }
            }
            return json_encode($res);
        }
        return $this->render('change_password', [
            "employee" => $employee
        ]);
    }
}
