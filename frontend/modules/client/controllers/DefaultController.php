<?php

namespace frontend\modules\client\controllers;

use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\Type;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yii;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `client` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $jobs = [];
        $client = [];
        $jobComplete = [];
        $jobProcess = [];
        $branch = Branch::find()
            ->select('branchName,branchId')
            ->where(["status" => 1])->asArray()
            ->orderBy('branchName')
            ->asArray()
            ->all();
        $clients = Client::find()
            ->select('client.clientName,j.clientId,j.categoryId')
            ->JOIN("LEFT JOIN", "job j", "client.clientId=j.clientId")
            ->where("j.status!=" . Job::STATUS_DELETED)
            ->andWhere("client.status!=" . Client::STATUS_DELETED)
            ->groupBy('j.clientId')
            ->orderBy("client.clientName ASC")
            ->asArray()
            ->all();
        $firstClient = Job::find()
            ->select('job.clientId,job.branchId,currencyId')
            ->JOIN('LEFT JOIN', 'client c', 'c.clientId=job.clientId')
            ->where(1)
            ->orderby('c.clientName')
            ->asArray()
            ->one();
        if (isset($firstClient) && !empty($firstClient)) {
            $client = Client::find()->select('clientName,remark,clientId')->where(["clientId" => $firstClient["clientId"]])->asArray()->one();
            $jobComplete = Job::find()
                ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                ->where(["job.clientId" => $firstClient["clientId"], "job.status" => Job::STATUS_COMPLETE])
                ->orderBy('job.jobName ASC')
                ->asArray()
                ->all();
            $jobProcess = Job::find()
                ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                ->where(["job.clientId" => $firstClient["clientId"], "job.status" => Job::STATUS_INPROCESS])
                ->orderBy('job.jobName ASC')
                ->asArray()
                ->all();
        }

        return $this->render('index', [
            "branch" => $branch,
            "clients" => $clients,
            "jobs" => $jobs,
            "client" => $client,
            "firstClient" => $firstClient,
            "jobComplete" => $jobComplete,
            "jobProcess" => $jobProcess,
        ]);
    }
    public function actionClientJob()
    {
        $clientId = $_POST["clientId"];
        $res = [];
        $text = "";
        $jobs = Job::find()
            ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
            ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
            ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
            ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
            ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
            ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
            ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
            ->where(["job.clientId" => $clientId])
            ->asArray()
            ->all();
        $client = Client::find()->select('clientName,clientId')->where(["clientId" => $clientId])->asArray()->one();
        $firstJob = Job::find()->select('clientId,branchId')->where(["clientId" => $clientId])->asArray()->one();
        $text = $this->renderAjax('client-job', [
            "jobs" => $jobs,
            "client" => $client,
            "firstJob" => $firstJob
        ]);
        if ($text != "") {
            $res["status"] = true;
            $res["text"] = $text;
        } else {
            $res["status"] = false;
        }
        return json_encode($res);
        //throw new Exception(print_r($jobs, true));
    }

    public function actionClientSelectYear()
    {
        $textJob = "";
        $res = [];
        $jobs = [];
        $clientId = $_POST["clientId"];
        $year = $_POST["year"];
        $jobId = [];
        $allJob = Job::find()->select('createDateTime,jobId')
            ->where(["clientId" => $clientId])
            ->asArray()
            ->all();
        $startDay = strtotime($year . '-01-01 00:00:00');
        $endDay = strtotime($year . '-12-31 24:59:59');
        if (isset($allJob) && count($allJob) > 0) {
            $i = 0;
            foreach ($allJob as $j) :
                $createDate = strtotime($j["createDateTime"]);
                if ($year == "") {
                    $jobId[$i] = $j["jobId"];
                } else  if ($createDate >= $startDay && $createDate <= $endDay) {
                    $jobId[$i] = $j["jobId"];
                }
                $i++;
            endforeach;
            if (count($jobId) > 0) {
                $jobs = Job::find()
                    ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                    ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                    ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                    ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                    ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                    ->where(["IN", "job.jobId", $jobId])
                    ->asArray()
                    ->all();
            }
        }
        $amount = Job::calculateClientAmount($jobId, 0);
        $textJob = $this->renderAjax('search_year', [
            "jobs" => count($jobs) > 0 ? $jobs : null,
        ]);
        $res["status"] = true;
        $res["textJob"] = $textJob;
        $res["amount"] =  number_format($amount, 2);
        return json_encode($res);
    }
    public function actionClientSelectYearComplete()
    {
        $textComplete = "";
        $res = [];
        $jobComplete = [];
        $clientId = $_POST["clientId"];
        $year = $_POST["year"];
        $jobId = [];
        $allJob = Job::find()->select('createDateTime,jobId')
            ->where(["clientId" => $clientId])
            ->asArray()
            ->all();
        $startDay = strtotime($year . '-01-01 00:00:00');
        $endDay = strtotime($year . '-12-31 24:59:59');
        if (isset($allJob) && count($allJob) > 0) {
            $i = 0;
            foreach ($allJob as $j) :
                $createDate = strtotime($j["createDateTime"]);
                if ($year == "") {
                    $jobId[$i] = $j["jobId"];
                } else  if ($createDate >= $startDay && $createDate <= $endDay) {
                    $jobId[$i] = $j["jobId"];
                }
                $i++;
            endforeach;
            if (count($jobId) > 0) {
                $status[0] = Job::STATUS_COMPLETE;
                $status[1] = Job::STATUS_INPROCESS;
                $jobComplete = Job::find()
                    ->select('job.jobId,job.jobName,job.status,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                    ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                    ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                    ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                    ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                    ->where(["IN", "job.jobId", $jobId])
                    ->andWhere(["IN", "job.status", $status])
                    //->andWhere("job.status=".Job::STATUS_COMPLETE)
                    ->asArray()
                    ->all();
            }
        }
        //throw new Exception(print_r($jobComplete, true));
        $textComplete = $this->renderAjax('search_year_complete', [
            "jobs" => count($jobComplete) > 0 ? $jobComplete : null
        ]);
        $amount = Job::calculateClientAmount($jobId, 1);
        $res["status"] = true;
        $res["textComplete"] = $textComplete;
        $res["amount"] = number_format($amount, 2);
        return json_encode($res);
    }
    public function actionClientList()
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $clients = Client::find()
            ->where(["status" => Client::STATUS_ACTIVE])
            ->andWhere("branchId!=0 and branchId is not null")
            ->asArray()
            ->orderBy('clientName')
            ->all();
        $branches = Branch::find()
            ->select('branchId,branchName')
            ->where(["status" => Branch::STATUS_ACTIVE])
            ->asarray()->orderBy('branchName')
            ->all();
        return $this->render('client', [
            "clients" => $clients,
            "branches" => $branches
        ]);
    }
    public function actionClientDetail($hash)
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $param = ModelMaster::decodeParams($hash);
        $clientId = $param["clientId"];
        $client = Client::find()->where(["clientId" => $clientId])->asArray()->one();
        return $this->render('detail', [
            "client" => $client
        ]);
    }
    public function actionCreateClient()
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        if (isset($_POST["clientName"]) && trim($_POST["clientName"] != "")) {
            $client = new Client();
            $client->clientName = $_POST["clientName"];
            $client->branchId = $_POST["branch"];
            $client->email = $_POST["email"];
            $client->clientTel1 = $_POST["tel1"];
            $client->clientTel2 = $_POST["tel2"];
            $client->taxId = $_POST["taxId"];
            $client->clientAddress = $_POST["address"];
            $client->remark = $_POST["remark"];
            if ($client->save(false)) {
                return $this->redirect('client-list');
            } else {
                return $this->redirect('create-client');
            }
        }
        $branches = Branch::find()->select('branchId,branchName')->where(["status" => 1])->orderBy('branchName')->asArray()->all();
        return $this->render('create', [
            "branches" => $branches
        ]);
    }
    public function actionUpdateClient($hash)
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $param = ModelMaster::decodeParams($hash);
        $clientId = $param["clientId"];
        $client = Client::find()->where(["clientId" => $clientId])->asArray()->one();
        $branches = Branch::find()->select('branchId,branchName')->where(["status" => 1])->orderBy('branchName')->asArray()->all();
        return $this->render('update', [
            "client" => $client,
            "branches" => $branches
        ]);
    }

    public function actionSaveUpdateClient()
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        if (isset($_POST["clientName"]) && trim($_POST["clientName"] != "")) {
            $client = Client::find()->where(["clientId" => $_POST["clientId"]])->one();
            $client->clientName = $_POST["clientName"];
            $client->branchId = $_POST["branch"];
            $client->email = $_POST["email"];
            $client->clientTel1 = $_POST["tel1"];
            $client->clientTel2 = $_POST["tel2"];
            $client->taxId = $_POST["taxId"];
            $client->clientAddress = $_POST["address"];
            $client->remark = $_POST["remark"];
            if ($client->save(false)) {
                return $this->redirect('client-list');
            } else {
                return $this->redirect('update-client/' . ModelMaster::encodeParams(["clientId" => $_POST["clientId"]]));
            }
        }
    }
    public function actionDisableClient()
    {
        $clientId = $_POST["clientId"];
        Client::updateAll(["status" => Client::STATUS_DELETED], ["clientId" => $clientId]);
        Job::updateAll(["status" => Job::STATUS_DELETED], ["clientId" => $clientId]);
        $res["status"] = true;
        return json_encode($res);
    }

    public function actionImportClient()
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $count = 0;
        $new = [];
        $update = [];
        if (isset($_POST["branch"])) {
            $branchId = $_POST["branch"];
            $imageObj = UploadedFile::getInstanceByName("clientFile");
            if (isset($imageObj) && !empty($imageObj)) {
                $urlFolder = Path::getHost() . 'file/client/';
                if (!file_exists($urlFolder)) {
                    mkdir($urlFolder, 0777, true);
                }
                $file = $imageObj->name;
                $filenameArray = explode('.', $file);
                $countArrayFile = count($filenameArray);
                $fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[$countArrayFile - 1];
                $pathSave = $urlFolder . $fileName;
                if ($imageObj->saveAs($pathSave)) {
                    $reader = new Xlsx();
                    $spreadsheet = $reader->load($pathSave);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray();
                    unset($sheetData[0]);
                    $i = 0;

                    foreach ($sheetData as $data) :
                        if ($i >= 1 &&  $data[0] != "") {
                            //$employeeNo = $data[0];
                            $clientName = $data[0];
                            $isNewClient = Client::checkNewClient($branchId, $clientName);
                            if ($isNewClient == 1) { //have this name
                                $client = Client::find()
                                    ->where(["clientName" => $clientName, "branchId" => $branchId])
                                    ->one();
                                $update[$i] = [
                                    "cleintName" => $clientName,
                                ];
                            } else {
                                $client = new Client();
                                $client->createDateTime = new Expression('NOW()');
                                $client->updateDateTime = new Expression('NOW()');
                                $new[$i] = [
                                    "cleintName" => $clientName,
                                ];
                            }
                            $client->clientName = $clientName;
                            $client->branchId = $branchId;
                            if ($client->save(false)) {
                                $count++;
                            }
                        }
                        $i++;
                    endforeach;
                }
                unlink($pathSave);
            }
        }
        $branch = Branch::find()
            ->select('branchName,branchId')
            ->where(["status" => 1])->asArray()
            ->orderBy('branchName')
            ->all();
        return $this->render('import', [
            "branch" => $branch,
            "count" => $count,
            "new" => $new,
            "update" => $update
        ]);
    }
    public function actionSearchClient()
    {
        $text = "";
        if (trim($_POST["searchName"] != "")) {
            $clients = Client::find()
                ->where(["status" => Client::STATUS_ACTIVE])
                ->andWhere("clientName LIKE '" . $_POST["searchName"] . "%'")
                ->andWhere("branchId!=0 and branchId is not null")
                ->andFilterWhere(["branchId" => $_POST["branchId"]])
                ->orderBy('clientName ASC')
                ->asArray()
                ->all();
        } else {
            $clients = Client::find()
                ->where(["status" => Client::STATUS_ACTIVE])
                ->andFilterWhere(["branchId" => $_POST["branchId"]])
                ->andWhere("branchId!=0 and branchId is not null")
                ->orderBy('clientName ASC')
                ->asArray()
                ->all();
        }
        if (isset($clients) && !empty($clients)) {
            $text = $this->renderAjax('filter', ["clients" => $clients]);
        }
        $res["text"] = $text;
        return json_encode($res);
    }
    public function actionSearchJobBranch()
    {
        $dataPost["branchId"] = $_POST["branchId"];
        $dataPost["sort"] = $_POST["sort"];
        $dataPost["clientId"] = isset($_POST["clientId"]) ? $_POST["clientId"] : '';
        $dataPost["yearOnProcess"] = isset($_POST["yearOnProcess"]) ? $_POST["yearOnProcess"] : null;
        $dataPost["yearComplete"] = isset($_POST["yearComplete"]) ? $_POST["yearComplete"] : null;
        return $this->redirect(Yii::$app->homeUrl . 'client/default/search-job/' . ModelMaster::encodeParams(["dataPost" => $dataPost]));
    }
    public function actionSearchJob($hash)
    {
        $right = "all";
        $access = Type::checkType($right);
        if ($access == 0) {
            return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
        }
        $params = ModelMaster::decodeParams($hash);
        $branchId = $params["dataPost"]["branchId"];
        $postsort = $params["dataPost"]["sort"];
        $clientId = $params["dataPost"]["clientId"];
        $yearOnProcess = $params["dataPost"]["yearOnProcess"];
        $yearComplete = $params["dataPost"]["yearComplete"];
        $jobComplete = [];
        $jobProcess = [];
        $jobs = [];
        $clients = [];
        $firstClient = [];
        $firstClientId = "";
        if ($postsort == 1) {
            $sort = "ASC";
        } else {
            $sort = "DESC";
        }

        $branch = Branch::find()
            ->select('branchName,branchId')
            ->where(["status" => 1])->asArray()
            ->orderBy('branchName')
            ->asArray()
            ->all();

        if ($branchId != '') {
            $clients = Client::find()
                ->select('client.clientName,j.clientId,j.categoryId')
                ->JOIN("LEFT JOIN", "job j", "client.clientId=j.clientId")
                ->where("j.status!=" . Job::STATUS_DELETED)
                ->andWhere("client.status!=" . Client::STATUS_DELETED)
                ->andWhere("client.branchId=" . $branchId)
                ->orderBy("client.clientName $sort")
                ->asArray()
                ->all();
        } else {
            $clients = Client::find()
                ->select('client.clientName,j.clientId,j.categoryId')
                ->JOIN("LEFT JOIN", "job j", "client.clientId=j.clientId")
                ->where("j.status!=" . Job::STATUS_DELETED)
                ->andWhere("client.status!=" . Client::STATUS_DELETED)
                ->orderBy("client.clientName $sort")
                ->asArray()
                ->all();
        }
        if (isset($clients) && count($clients) > 0) {
            $firstClientId = $clients[0]["clientId"];
            if ($clientId) {
                $firstClientId = $clientId;
            }
            if ($yearOnProcess != '') {
                $jobId = [];
                $allJob = Job::find()->select('createDateTime,jobId')
                    ->where(["clientId" =>   $firstClientId])
                    ->asArray()
                    ->all();
                $startDay = strtotime($yearOnProcess . '-01-01 00:00:00');
                $endDay = strtotime($yearOnProcess . '-12-31 24:59:59');
                if (isset($allJob) && count($allJob) > 0) {
                    $i = 0;
                    foreach ($allJob as $j) :
                        $createDate = strtotime($j["createDateTime"]);
                        if ($yearOnProcess == "") {
                            $jobId[$i] = $j["jobId"];
                        } else  if ($createDate >= $startDay && $createDate <= $endDay) {
                            $jobId[$i] = $j["jobId"];
                        }
                        $i++;
                    endforeach;
                }
            }
            $firstClient = Client::find()->select('clientName,branchId,clientId,remark')->where(["clientId" => $firstClientId])->asArray()->one();
            if ($yearOnProcess != '') {
                $jobProcess = Job::find()
                    ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId,YEAR(job.createDateTime)')
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                    ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                    ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                    ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                    ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                    ->where(["job.clientId" => $firstClientId, "job.status" => Job::STATUS_INPROCESS])
                    ->andFilterWhere(["YEAR(job.createDateTime)" => $yearOnProcess])
                    ->orderBy('job.jobName ASC')
                    ->asArray()
                    ->all();
                //throw new Exception(print_r($jobProcess, true));
            } else {
                $jobProcess = Job::find()
                    ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                    ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                    ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                    ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                    ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                    ->where(["job.clientId" => $firstClientId, "job.status" => Job::STATUS_INPROCESS])
                    ->orderBy('job.jobName ASC')
                    ->asArray()
                    ->all();
            }
            if ($yearComplete != '') {
                $jobComplete = Job::find()
                    ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId,YEAR(job.createDateTime)')
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                    ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                    ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                    ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                    ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                    ->where(["job.clientId" => $firstClientId, "job.status" => Job::STATUS_COMPLETE])
                    ->andFilterWhere(["YEAR(job.createDateTime)" => $yearComplete])
                    ->orderBy('job.jobName ASC')
                    ->asArray()
                    ->all();
            } else {
                $jobComplete = Job::find()
                    ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                    ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                    ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                    ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                    ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                    ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                    ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                    ->where(["job.clientId" => $firstClientId, "job.status" => Job::STATUS_COMPLETE])
                    ->orderBy('job.jobName ASC')
                    ->asArray()
                    ->all();
            }
            /*$jobs = Job::find()
                ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                ->where(["job.clientId" => $firstClientId])
                ->orderBy('job.jobName ASC')
                ->asArray()
                ->all();*/
        }
        return $this->render('search_client_job', [
            "branch" => $branch,
            "clients" => $clients,
            "branchId" => $branchId,
            "sort" => $postsort,
            "jobs" => $jobs,
            "firstClient" => $firstClient,
            "firstClientId" => $firstClientId,
            "jobComplete" => $jobComplete,
            "jobProcess" => $jobProcess,
            "yearComplete" => $yearComplete,
            "yearOnProcess" => $yearOnProcess
        ]);
    }
    public function actionClientBranch()
    {
        $branchId = $_POST["branchId"];
        if ($_POST["sort"] == 1) {
            $sort = "ASC";
        } else {
            $sort = "DESC";
        }
        $res = [];
        $clients = [];
        $text = "";
        $textJob = "";
        $jobs = Client::find()
            ->select('client.clientName,j.clientId,j.categoryId')
            ->JOIN("LEFT JOIN", "job j", "client.clientId=j.clientId")
            ->where("j.status!=" . Job::STATUS_DELETED)
            ->where(["j.branchId" => $branchId])
            ->andWhere("client.status!=" . Client::STATUS_DELETED)
            ->groupBy('j.clientId')
            ->orderBy("client.clientName ASC")
            ->orderBy("client.clientName $sort")
            ->asArray()
            ->all();
        if ($branchId == "") {
            $jobs = Client::find()
                ->select('clientName,clientId')
                ->where(["status" => 1])
                ->orderBy("clientName $sort")
                ->asArray()
                ->all();
        }
        if (isset($jobs) && count($jobs) > 0) {
            $i = 0;
            foreach ($jobs as $job) :
                $clients[$i] = [
                    "clientId" => $job["clientId"],
                    "clientName" => $job["clientName"]
                ];
                $i++;
            endforeach;
        }
        if (count($clients) > 0) {
            $text = $this->renderAjax('client-search', [
                "clients" => $clients
            ]);
            $firstClientId = $clients[0]["clientId"];
            $client = Client::find()->select('clientName,clientId')->where(["clientId" => $firstClientId])->asArray()->one();
            $firstJob = Job::find()->select('clientId,branchId,status,currencyId')->where(["clientId" => $firstClientId])->asArray()->one();
            $jobs = Job::find()
                ->select('job.jobId,job.jobName,job.branchId,job.clientId,job.teamId,job.fee,job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ct.categoryName,f.fieldName,job.categoryId')
                ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
                ->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
                ->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
                ->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
                ->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
                ->JOIN("LEFT JOIN", "category ct", 'ct.categoryId=job.categoryId')
                ->where(["job.clientId" => $firstClientId])
                ->asArray()
                ->all();
            $textJob = $this->renderAjax('client-job', [
                "jobs" => $jobs,
                "client" => $client,
                "firstJob" => $firstJob
            ]);
        }
        if ($text != false) {
            $res["status"] = true;
            $res["text"] = $text;
            $res["textJob"] = $textJob;
        } else {
            $res["status"] = false;
        }
        return json_encode($res);
    }
    public function actionDeleteClient()
    {
        $clients = Client::find()->where(["branchId" => 1])->all();
        if (isset($clients) && count($clients) > 0) {
            foreach ($clients as $c) :
                $job = Job::find()->where(["clientId" => $c->clientId])->one();
                if (!isset($job) || empty($job)) {
                    $c->status = 99;
                    $c->save(false);
                }
            endforeach;
        }
    }
    public function actionSaveRemark()
    {
        $clientId = $_POST["cleintId"];
        $remark = $_POST["remark"];
        $res["status"] = false;
        $client = Client::find()->where(["clientId" => $clientId])->one();
        if (isset($client) && !empty($client)) {
            $client->remark = $remark;
            if ($client->save(false)) {
                $res["status"] = true;
            }
        }
        return json_encode($res);
    }
}
