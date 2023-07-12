<?php

namespace frontend\modules\job\controllers;

use common\email\Email;
use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\AdditionalStep;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Currency;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobAlert;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobResponsibility;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\JobTypeStep;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use frontend\models\lower_management\Type;
use Yii;
use yii\db\Expression;
use yii\helpers\FileHelper;
use yii\web\Controller;

/**
 * Default controller for the `job` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate()
    {
        $right = 'all';
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        if (isset($_POST["jobName"])) {
            $job = new Job();
            $job->jobName = $_POST["jobName"];
            $job->clientId = isset($_POST["clientId"]) && $_POST["clientId"] != '' && $_POST["clientId"] != null ? $_POST["clientId"] : null;
            $job->branchId = $_POST["branch"];
            $job->fieldId = $_POST["field"];
            $job->jobTypeId = $_POST["jobType"]; //==>save job type step in job step
            $job->categoryId = $_POST["category"]; //==>save in jobCategoryRound
            $job->teamId = $_POST["dreamteam"];
            $job->jobName = $_POST["jobName"];
            $job->memo = $_POST["memo"];
            $job->url = $_POST["url"];
            $job->report = isset($_POST["report"]) ? 1 : 0;
            $job->fee = $_POST["fee"];
            $job->currencyId = $_POST["currency"];
            $job->feeChargeDate = $_POST["feeChargeDate"];
            $job->advanceReceivable = $_POST["advanceRec"];
            $job->advancedChargeDate = $_POST["advancedChargeDate"];
            $job->outsourcingFee = $_POST["outsourcingFee"];
            $job->estimateTime = $_POST["estimate"];
            $job->startDate = $_POST["trueDate"];
            $job->status = 1;
            $job->createDateTime = new Expression('NOW()');
            $job->updateDateTime = new Expression('NOW()');
            if ($job->save(false)) {
                $startMonth = [];
                $email = [];
                $pIc1 = [];
                $pIc2 = [];
                $percentagePic1 = [];
                $percentagePic2 = [];
                $subStepName = [];
                $subStepDueDate = [];
                $fiscalYear = null;
                if (isset($_POST["startMonth"])) {
                    $startMonth = $_POST["startMonth"];
                }
                if (isset($_POST["email"])) {
                    $email = $_POST["email"];
                }
                if (isset($_POST["pIc1"])) {
                    $pIc1 = $_POST["pIc1"];
                }
                if (isset($_POST["pIc2"])) {
                    $pIc2 = $_POST["pIc2"];
                }
                if (isset($_POST["percentagePic1"])) {
                    $percentagePic1 = $_POST["percentagePic1"];
                }
                if (isset($_POST["percentagePic2"])) {
                    $percentagePic2 = $_POST["percentagePic2"];
                }
                if (isset($_POST["subStepName"])) {
                    $subStepName = $_POST["subStepName"];
                }
                if (isset($_POST["subStepDueDate"])) {
                    $subStepDueDate = $_POST["subStepDueDate"];
                }
                if (isset($_POST["fiscalYear"])) {
                    $fiscalYear = $_POST["fiscalYear"];
                }
                $jobId = Yii::$app->db->getLastInsertID();
                $jobCategoryId = $this->saveJobCategoryRound($jobId, $_POST["category"], $startMonth, $_POST["targetDate"], $fiscalYear);
                $this->saveJobStep($jobId, $_POST["jobType"], $_POST["stepDueDate"], $jobCategoryId, $subStepName, $subStepDueDate);
                $this->saveJobResponsibility($jobId, $_POST["approver"], $pIc1, $percentagePic1, $pIc2, $percentagePic2);
                $this->saveEmailAlert($jobId, $email);
                //$this->saveNewClient($jobId, $_POST["clientName"]);
                $this->sendJobEmail($jobId);
                Yii::$app->getSession()->setFlash('create', [
                    'body' => '<b>Created</b> " ' . $_POST["jobName"] . ' "',
                ]);
                return $this->redirect(Yii::$app->homeUrl . 'job/detail/index');
            }
        }
        $employee = Employee::find()->where(["employeeId" => Yii::$app->user->id])->asArray()->one();
        $teamId = $employee["teamId"];
        $branchId = $employee["branchId"];
        $rightAll = [Type::TYPE_ADMIN, Type::TYPE_MANAGER, Type::TYPE_GM];
        $fag = 0;
        $employeeType = EmployeeType::findEmployeeType();
        if (count($rightAll) > 0) {
            foreach ($employeeType as $all) :
                if (in_array($all, $rightAll)) {
                    $fag = 1;
                }
            endforeach;
        }
        //if ($fag == 1) {
        $branch = Branch::find()->select('branchId,branchName')
            ->where(["status" => Branch::STATUS_ACTIVE])
            ->orderBy('branchName')
            ->asArray()
            ->all();
        // } else {
        //     $branch = Branch::find()->select('branchId,branchName')
        //         ->where(["branchId" => $branchId])
        //         ->asArray()
        //         ->all();
        // }
        $category = Category::find()->select('categoryName,categoryId')
            ->where(["status" => Category::STATUS_ACTIVE])
            ->asArray()
            ->orderBy('categoryName')
            ->all();
        // $field = Field::find()->select('fieldId,fieldName')
        //     ->where(["status" => Category::STATUS_ACTIVE])
        //     ->asArray()
        //     ->orderBy('fieldName')
        //     ->all();
        $employyeeEmail = Employee::find()->select('employee.employeeId as employeeId,employee.email as email,employee.employeeFirstName as firstName,employee.employeeLastName as lastName,employee.employeeNickName as nickName')
            ->JOIN("LEFT JOIN", "employee_type et", "et.employeeId=employee.employeeId")
            ->where("et.employeeId is not null")
            ->andWhere(["employee.status" => Employee::STATUS_CURRENT])
            ->orderBy('nickName')
            ->asArray()
            ->all();
        $currency = Currency::find()
            ->select('name,code,symbol,currencyId')
            ->where(["status" => 1])->asArray()
            ->orderBy('name')
            ->all();
        $email = [];
        if (isset($employyeeEmail) && count($employyeeEmail) > 0) {
            foreach ($employyeeEmail as $mail) :
                $email[$mail["employeeId"]] = $mail["nickName"] . '  ( ' . $mail["firstName"] . ' ' . $mail["lastName"] . ' ) ' . $mail["email"];
            endforeach;
        }

        return $this->render('create', [
            "branch" => $branch,
            "category" => $category,
            // "field" => $field,
            "email" => $email,
            "currency" => $currency
        ]);
    }
    public function sendJobEmail($jobId)
    {
        $data = [];
        $email = JobAlert::find()->select('userId')->where(["jobId" => $jobId])->asArray()->all();
        $subject = "Lower management update information";
        if (isset($email) && count($email) > 0) {
            foreach ($email as $mail) :
                $employee = Employee::find()
                    ->select('email,employeeNickName,employeeFirstName,employeeLastName')
                    ->where(["employeeId" => $mail["userId"]])->asArray()->one();
                $job = Job::find()
                    ->select('job.jobName,job.status as jstatus,c.clientName,b.branchName,job.jobTypeId,job.categoryId')
                    ->JOIN("LEFT JOIN", "branch b", "b.branchId=job.branchId")
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->where(["job.jobId" => $jobId])
                    ->asArray()
                    ->one();
                if (isset($job) && !empty($job)) {
                    $data["nickName"] = $employee["employeeFirstName"] . ' ' . $employee["employeeLastName"] . ' san';
                    $data["branch"] = $job["branchName"];
                    $data["clientName"] = $job["clientName"];
                    $data["jobName"] = $job["jobName"];
                    $data["pic1"] = JobResponsibility::jobResponseText($jobId, 'PIC 1');
                    $data["pic2"] = JobResponsibility::jobResponseText($jobId, 'PIC 2');
                    $data["currentStepDueDate"] = JobStep::CurrentStepEmail($jobId);
                    $data["currentTargetDate"] = JobCategory::CurrentJobCategoryEmail($jobId);
                    $data["jobType"] = JobType::jobTypeName($job["jobTypeId"]);
                    $data["category"] = Category::categoryName($job["categoryId"]);
                    $data["status"] = Job::statusText($jobId);
                    $data["link"] = Path::frontendUrl() . 'job/detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $jobId]);
                }
                Email::jobUpdate($employee["email"], $subject, $data);
            endforeach;
        }
    }
    public function actionExistingClient()
    {
        $clientName = $_POST["clientName"];
        $text = "";
        $branchId = Employee::employeeBranch();
        $employeeType = EmployeeType::findEmployeeType();
        $rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
        $fag = 0;
        if (count($rightAll) > 0) {
            foreach ($employeeType as $all) :
                if (in_array($all, $rightAll)) {
                    $fag = 1;
                }
            endforeach;
        }
        if ($fag == 1) {
            $client = Client::find()->select('clientName,clientId')
                ->where("clientName LIKE '%" . $clientName . "%'")
                ->andWhere(["status" => 1])
                ->orderBy('clientName')
                ->asArray()
                ->all();
        } else {
            $client = Client::find()->select('clientName,clientId')
                ->where("clientName LIKE '%" . $clientName . "%'")
                ->andWhere(["status" => 1, "branchId" => $branchId])
                ->orderBy('clientName')
                ->asArray()
                ->all();
        }
        if (isset($client) && count($client) > 0) {
            foreach ($client as $cli) :
                $name = $cli["clientName"];
                $text .= "<div class='client-select-box-item' id='client-" . $cli["clientId"] . "' onclick='javascript:existClient(" . $cli["clientId"] . ")'>" . $cli["clientName"] . "</div>";
            endforeach;
        }
        if ($text != "") {
            $res["status"] = true;
            $res["text"] = $text;
        } else {
            $res["status"] = false;
        }
        return json_encode($res);
    }
    public function actionJobTypeBranch()
    {
        $jobType = JobType::find()
            ->where(["branchId" => $_POST["branchId"], "status" => JobType::STATUS_ACTIVE])
            ->orderBy('jobTypeName')
            ->asArray()
            ->all();
        $fields = Field::find()->select('fieldId,fieldName')
            ->where(["status" => Field::STATUS_ACTIVE, "branchId" => $_POST["branchId"]])
            ->asArray()
            ->orderBy('fieldName')
            ->all();
        $text = "<option value=''>Job Type</option>";
        $textTeam = "<option value=''>Dream Team</option>";
        $textClient = "<option value=''>Client</option>";
        $textField = "<option value=''>Field</option>";
        $rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
        $fag = 0;
        $rightBranch = [Type::TYPE_MANAGER];
        $employeeType = EmployeeType::findEmployeeType();

        if (count($rightAll) > 0) {
            foreach ($employeeType as $all) :
                if (in_array($all, $rightAll)) {
                    $fag = 1;
                }
            endforeach;
        }
        if (isset($jobType) && count($jobType) > 0) {
            foreach ($jobType as $j) :
                $jobStep = Step::find()->where(["jobTypeId" => $j["jobTypeId"], "status" => 1])->one();
                if (isset($jobStep) && !empty($jobStep)) {
                    $text .= "<option value='" . $j["jobTypeId"] . "'>" . $j["jobTypeName"] . "</option>";
                }
            endforeach;
        }
        if (isset($fields) && count($fields) > 0) {
            foreach ($fields as $f) :
                $textField .= "<option value='" . $f["fieldId"] . "'>" . $f["fieldName"] . "</option>";
            endforeach;
        }
        if ($fag == 1) {
            $team = Team::find()
                ->where(["branchId" => $_POST["branchId"], "status" => Team::STATUS_ACTIVE])
                ->asArray()
                ->all();
        } else {
            $team = Team::find()
                ->where(["branchId" => $_POST["branchId"], "status" => Team::STATUS_ACTIVE])
                ->asArray()
                ->all();
        }
        if (isset($team) && count($team) > 0) {
            foreach ($team as $t) :
                $textTeam .= "<option value='" . $t["teamId"] . "'>" . $t["teamName"] . "</option>";
            endforeach;
        }
        $clients = Client::find()
            ->select('clientId,clientName')
            ->where(["status" => 1, "branchId" => $_POST["branchId"]])
            ->orderBy('clientName')
            ->asArray()
            ->all();
        if (isset($clients) && count($clients) > 0) {
            foreach ($clients as $client) :
                $textClient .= "<option value='" . $client["clientId"] . "'>" . $client["clientName"] . "</option>";
            endforeach;
        }
        $res["text"] = $text;
        $res["textTeam"] = $textTeam;
        $res["textField"] = $textField;
        $res["textClient"] = $textClient;
        $res["status"] = true;
        return json_encode($res);
    }
    public function actionJobCategoryLayout()
    {

        $category = Category::find()->where(["categoryId" => $_POST["categoryId"]])->asArray()->one();
        $text = $this->renderAjax('category_layout', ["category" => $category]);
        $res["text"] = $text;
        return json_encode($res);
    }

    public function actionJobTypeStep()
    {

        $step = Step::find()
            ->where(["jobTypeId" => $_POST["jobTypeId"], "status" => Step::STATUS_ACTIVE])
            ->asArray()
            ->orderBy('sort')
            ->all();
        $jobType = JobType::find()->select('jobTypeDetail,jobTypeId')->where(["jobTypeId" => $_POST["jobTypeId"]])->asArray()->one();
        $text = $this->renderAjax('job_type_step', [
            "step" => $step,
            "jobTypeDetail" => $jobType["jobTypeDetail"],
            "jobTypeId" => $jobType["jobTypeId"]
        ]);
        $res["text"] = $text;
        return json_encode($res);
    }
    public function actionPicTeam()
    {
        $teamId = $_POST["teamId"];
        $branchId = $_POST["branchId"];
        $res = [];
        $text1 = "<option value=''>PIC 1</option>";
        $text2 = "<option value=''>PIC 2</option>";
        $text3 = "<option value=''>Approver</option>";
        $userType = Employee::find()->select('employeeId,employeeFirstName as firstName,employeeLastName as lastName,employeeNickName as nickName')
            //->where(["status" => Employee::STATUS_CURRENT, "teamId" => $teamId, "branchId" => $branchId])
            ->where(["status" => Employee::STATUS_CURRENT, "branchId" => $branchId])
            ->asArray()
            ->orderBy('firstname')
            ->all();
        if (isset($userType) && count($userType) > 0) {
            foreach ($userType as $ut) :
                $text1 .= "<option value='" . $ut["employeeId"] . "'>" . $ut["firstName"] . "&nbsp;&nbsp;&nbsp;" . $ut["lastName"] . " (" . $ut["nickName"] . ")</option>";
                $text2 .= "<option value='" . $ut["employeeId"] . "'>" . $ut["firstName"] . "&nbsp;&nbsp;&nbsp;" . $ut["lastName"] . " (" . $ut["nickName"] . ")</option>";
            endforeach;
        } else {
            $userType = Employee::find()->select('employeeId,employeeFirstName as firstName,employeeLastName as lastName,employeeNickName as nickName')
                ->where(["status" => Employee::STATUS_CURRENT, "branchId" => $branchId])
                ->asArray()
                ->orderBy('firstname')
                ->all();
            if (isset($userType) && count($userType) > 0) {
                foreach ($userType as $ut) :
                    $text1 .= "<option value='" . $ut["employeeId"] . "'>" . $ut["firstName"] . "&nbsp;&nbsp;&nbsp;" . $ut["lastName"] . " (" . $ut["nickName"] . ")</option>";
                    $text2 .= "<option value='" . $ut["employeeId"] . "'>" . $ut["firstName"] . "&nbsp;&nbsp;&nbsp;" . $ut["lastName"] . " (" . $ut["nickName"] . ")</option>";
                endforeach;
            }
        }
        $approver = Employee::find()->select('employeeId,employeeFirstName as firstName,employeeLastName as lastName,employeeNickName as nickName')
            //->where(["status" => Employee::STATUS_CURRENT, "teamPositionId" => TeamPosition::LEADER, "teamId" => $teamId, "branchId" => $branchId])
            ->where(["status" => Employee::STATUS_CURRENT, "teamPositionId" => TeamPosition::LEADER, "branchId" => $branchId])
            ->orderBy('firstname')
            ->asArray()
            ->all();
        if (isset($approver) && count($approver) > 0) {
            foreach ($approver as $ap) :
                $text3 .= "<option value='" . $ap["employeeId"] . "'>" . $ap["firstName"] . "&nbsp;&nbsp;&nbsp;" . $ap["lastName"] . " (" . $ap["nickName"] . ")</option>";
            endforeach;
        } else {
            $approver = Employee::find()->select('employeeId,employeeFirstName as firstName,employeeLastName as lastName,employeeNickName as nickName')
                ->where(["status" => Employee::STATUS_CURRENT, "branchId" => $branchId])
                ->orderBy('firstname')
                ->asArray()
                ->all();
            if (isset($approver) && count($approver) > 0) {
                foreach ($approver as $ap) :
                    $text3 .= "<option value='" . $ap["employeeId"] . "'>" . $ap["firstName"] . "&nbsp;&nbsp;&nbsp;" . $ap["lastName"] . " (" . $ap["nickName"] . ")</option>";
                endforeach;
            }
        }
        $res["text1"] = $text1;
        $res["text2"] = $text2;
        $res["text3"] = $text3;
        return json_encode($res);
    }
    public function actionUserType()
    {
        $res = [];
        $text = "<option value=''>" . $_POST["typeName"] . "</option>";
        $teamId = $_POST["teamId"];
        $branchId = $_POST["branchId"];
        $userType = Employee::find()
            ->select('employeeId,employeeFirstName as firstName,employeeLastName as lastName,employeeNickName as nickName')
            ->where(["status" => Employee::STATUS_CURRENT, "teamId" => $teamId, "branchId" => $branchId])
            ->asArray()
            ->orderBy('firstname')
            ->all();

        if (isset($userType) && count($userType) > 0) {
            foreach ($userType as $ut) :
                $text .= "<option value='" . $ut["employeeId"] . "'>" . $ut["firstName"] . "&nbsp;&nbsp;&nbsp;" . $ut["lastName"] . " (" . $ut["nickName"] . ")</option>";
            endforeach;
        }
        $res["text"] = $text;
        return json_encode($res);
    }
    public function saveJobStep($jobId, $jobTypeId, $stepDueDate, $jobCategoryId, $subStepName, $subStepDueDate)
    {
        $step = [];
        $jobTypeStep = Step::find()->where(["jobTypeId" => $jobTypeId, "status" => 1])->asArray()->orderby('sort,stepId')->all();
        if (isset($jobTypeStep) && count($jobTypeStep) > 0) {
            $i = 0;
            foreach ($jobTypeStep as $jts) :
                $step[$i] = $jts["stepId"];
                $jobStep = new JobStep();
                $jobStep->jobId = $jobId;
                $jobStep->stepId = $step[$i];
                $jobStep->dueDate = isset($stepDueDate[$i]) ? $stepDueDate[$i] : null;
                $jobStep->firstDueDate = isset($stepDueDate[$i]) ? $stepDueDate[$i] : null;
                $jobStep->status = 1;
                $jobStep->jobCategoryId = $jobCategoryId;
                $jobStep->createDateTime = new Expression('NOW()');
                $jobStep->updateDateTime = new Expression('NOW()');
                $jobStep->save(false);
                $i++;
            endforeach;
        }

        if (count($subStepName) > 0) {
            foreach ($subStepName as $stepId => $steps) :
                if (count($steps) > 0) {
                    foreach ($steps as $sort => $stepName) :
                        $subStep = new AdditionalStep();
                        $subStep->jobId = $jobId;
                        $subStep->stepId = $stepId;
                        $subStep->jobCategoryId = $jobCategoryId;
                        $subStep->additionalStepName = $stepName;
                        $subStep->sort = $sort;
                        $subStep->dueDate = isset($subStepDueDate[$stepId][$sort]) ? $subStepDueDate[$stepId][$sort] : null;
                        $subStep->firstDueDate = isset($subStepDueDate[$stepId][$sort]) ? $subStepDueDate[$stepId][$sort] : null;
                        $subStep->completeDate = null;
                        $subStep->status = 1;
                        $subStep->createDateTime = new Expression('NOW()');
                        $subStep->updateDateTime = new Expression('NOW()');
                        $subStep->save(false);
                    endforeach;
                }
            endforeach;
        }
    }
    public function saveJobCategoryRound($jobId, $categoryId, $startMonth, $targetDate, $fiscalYear)
    {
        $category = Category::find()->where(["categoryId" => $categoryId])->asArray()->one();
        // if (isset($startMonth) && count($startMonth) > 0) {
        $i = 0;
        $firstId = 0;
        $currentYear = date('Y');
        while ($i < $category["totalRound"]) {
            $jobCategory = new JobCategory();
            $jobCategory->jobId = $jobId;
            $jobCategory->categoryId = $categoryId;
            if (isset($startMonth[$i]) && $startMonth[$i] != '') {
                $jobCategory->startMonth = ModelMaster::shotMonthValue($startMonth[$i]);
            } else {
                if (isset($targetDate[$i]) && $targetDate[$i] != null) {
                    $dateArr = explode('-', $targetDate[$i]);
                    $jobCategory->startMonth = (int)$dateArr[1];
                } else {
                    $jobCategory->startMonth = (int)date('m');
                }
            }
            $jobCategory->targetDate = isset($targetDate[$i]) ? $targetDate[$i] : null;
            $jobCategory->firstTargetDate = isset($targetDate[$i]) ? $targetDate[$i] : null;
            $jobCategory->status = $i == 0 ? 1 : JobCategory::STATUS_WAITPROCESS;
            $jobCategory->fiscalYear = $fiscalYear != null ? $fiscalYear : $currentYear;
            $jobCategory->createDateTime = new Expression('NOW()');
            $jobCategory->updateDateTime = new Expression('NOW()');
            $jobCategory->save(false);
            if ($i == 0) {
                $firstId = Yii::$app->db->lastInsertID;
            }
            $i++;
        }
        return $firstId;
    }
    //}
    public function saveJobResponsibility($jobId, $approverId, $pIc1, $percentagPic1, $pIc2, $percentagPic2)
    {
        if (isset($pIc1) && count($pIc1) > 0) {
            foreach ($pIc1 as $index => $pic1) :
                if ($pic1 != null) {
                    $res = new JobResponsibility();
                    $res->jobId = $jobId;
                    $res->employeeId = $pic1;
                    $res->responsibility = JobResponsibility::PIC1;
                    $res->percentage = isset($percentagPic1[$index]) ? $percentagPic1[$index] : null;
                    $res->status = 1;
                    $res->createDateTime = new Expression('NOW()');
                    $res->updateDateTime = new Expression('NOW()');
                    $res->save(false);
                }
            endforeach;
        }
        if (isset($pIc2) && count($pIc2) > 0) {
            foreach ($pIc2 as $index => $pic2) :
                if ($pic2 != null) {
                    $res = new JobResponsibility();
                    $res->jobId = $jobId;
                    $res->employeeId = $pic2;
                    $res->responsibility = JobResponsibility::PIC2;
                    $res->percentage = isset($percentagPic2[$index]) ? $percentagPic2[$index] : null;
                    $res->status = 1;
                    $res->createDateTime = new Expression('NOW()');
                    $res->updateDateTime = new Expression('NOW()');
                    $res->save(false);
                }
            endforeach;
        }
        if ($approverId != '' && $approverId != null) {
            $approver = new JobResponsibility();
            $approver->jobId = $jobId;
            $approver->employeeId = $approverId;
            $approver->responsibility = JobResponsibility::APPROVER;
            $approver->percentage =  null;
            $approver->status = 1;
            $approver->createDateTime = new Expression('NOW()');
            $approver->updateDateTime = new Expression('NOW()');
            $approver->save(false);
        }
        $creater = new JobResponsibility();
        $creater->jobId = $jobId;
        $creater->employeeId = Yii::$app->user->id;
        $creater->responsibility = JobResponsibility::CREATER;
        $creater->percentage =  null;
        $creater->status = 1;
        $creater->createDateTime = new Expression('NOW()');
        $creater->updateDateTime = new Expression('NOW()');
        $creater->save(false);
    }
    public function saveEmailAlert($jobId, $email)
    {
        if (isset($email) && count($email) > 0) {
            foreach ($email as $e) :
                $alert = new JobAlert();
                $alert->jobId = $jobId;
                $alert->userId = $e;
                $alert->status = 1;
                $alert->createDateTime = new Expression('NOW()');
                $alert->updateDateTime = new Expression('NOW()');
                $alert->save(false);
            endforeach;
        }
    }
    public function saveNewClient($jobId, $clientName)
    {
        $job = Job::find()->where(["jobId" => $jobId])->one();
        if ($job->clientId == null) {
            $client = Client::find()->select('clientName,clientId')->where(["clientName" => $clientName])->one();
            if (isset($client) && !empty($client)) {
                $job->clientId = $client->clientId;
            } else {
                $client = new Client();
                $client->clientName = $clientName;
                $client->branchId = $job->branchId;
                $client->status = 1;
                $client->createDateTime = new Expression('NOW()');
                $client->updateDateTime = new Expression('NOW()');
                $client->save(false);
                $newClientId = Yii::$app->db->lastInsertID;
                $job->clientId = $newClientId;
            }
            $job->save(false);
        }
    }
    public function actionUpdateClientBranch()
    {
        $clients = Client::find()->where(["branchId" => null, "status" => 1])->all();
        if (isset($clients) && count($clients) > 0) {
            foreach ($clients as $client) :
                $job = Job::find()->where(["clientId" => $client->clientId])->one();
                if (isset($job) && !empty($job)) {
                    $client->branchId = $job->branchId;
                    $client->save(false);
                }
            endforeach;
        }
    }

    public function actionAdditionalStep()
    {
        $text = "";
        $stepId = $_POST["stepId"];
        $sort = $_POST["sort"];
        $id = Yii::$app->security->generateRandomString(12);
        $text = $this->renderAjax('additional_step', [
            "stepId" => $stepId,
            "id" => $id,
            "sort" => $sort
        ]);
        $res["status"] = true;
        $res["text"] = $text;
        return json_encode($res);
    }
}
