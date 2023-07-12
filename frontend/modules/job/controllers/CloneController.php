<?php

namespace frontend\modules\job\controllers;

use common\models\ModelMaster;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\Step;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

class CloneController extends Controller
{
	public function actionCreate($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$jobId = $param["jobId"];
		$job = Job::find()->where(["jobId" => $jobId])->asArray()->one();
		$clients = Client::find()
			->where(["branchId" => $job["branchId"], "status" => 1])
			->andWhere("clientId!=" . $job['clientId'])
			->asArray()
			->orderBy('clientName')
			->all();
		return $this->render('create', ["clients" => $clients, "job" => $job]);
	}
	public function actionSaveCopy()
	{
		$jobId = $_POST["jobId"];
		$jobMaster = Job::find()->where(["jobId" => $jobId])->asArray()->one();
		$branchId = $jobMaster["branchId"];
		if (isset($_POST["jobName"]) && count($_POST["jobName"]) > 0) {
			foreach ($_POST["jobName"] as $id => $name) :
				$clientId = $_POST["client"][$id];
				$fieldId = $jobMaster["fieldId"];
				$jobTypeId = $jobMaster["jobTypeId"];
				$categoryId = $jobMaster["categoryId"];
				$teamId = $jobMaster["teamId"];
				$currencyId = $jobMaster["currencyId"];
				$fee = $jobMaster["fee"];
				$job = new Job();
				$job->jobName = $name;
				$job->clientId = $clientId;
				$job->branchId = $branchId;
				$job->fieldId = $fieldId;
				$job->jobTypeId = $jobTypeId; //==>save job type step in job step
				$job->categoryId = $categoryId; //==>save in jobCategoryRound
				$job->teamId = $teamId;
				$job->fee = $fee;
				$job->currencyId = $currencyId;
				$job->status = 1;
				$job->createDateTime = new Expression('NOW()');
				$job->updateDateTime = new Expression('NOW()');
				if ($job->save(false)) {
					$targetDate = null;
					$startMonth = (int)date('m');
					$fiscalYear = date('Y');
					$jobId = Yii::$app->db->getLastInsertID();
					$jobCategoryId = $this->saveJobCategoryRound($jobId, $categoryId, $startMonth, $targetDate, $fiscalYear);
					$this->saveJobStep($jobId, $jobTypeId, $jobCategoryId);
				}
			endforeach;
			return $this->redirect(Yii::$app->homeUrl . 'job/detail/index');
		}
	}
	public function saveJobCategoryRound($jobId, $categoryId, $startMonth, $targetDate, $fiscalYear)
	{
		$category = Category::find()->where(["categoryId" => $categoryId])->asArray()->one();
		$i = 0;
		$firstId = 0;
		$currentYear = date('Y');
		while ($i < $category["totalRound"]) {
			$jobCategory = new JobCategory();
			$jobCategory->jobId = $jobId;
			$jobCategory->categoryId = $categoryId;
			if ($i == 0) {
				$jobCategory->startMonth = $startMonth;
			}

			$jobCategory->targetDate = isset($targetDate[$i]) ? $targetDate[$i] : null;
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
	public function saveJobStep($jobId, $jobTypeId, $jobCategoryId)
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
				$jobStep->dueDate = null;
				$jobStep->status = 1;
				$jobStep->jobCategoryId = $jobCategoryId;
				$jobStep->createDateTime = new Expression('NOW()');
				$jobStep->updateDateTime = new Expression('NOW()');
				$jobStep->save(false);
				$i++;
			endforeach;
		}
	}
	public function actionAddMoreClient()
	{
		$jobId = $_POST["jobId"];
		$number = $_POST["number"];
		$job = Job::find()->where(["jobId" => $jobId])->asArray()->one();
		$clients = Client::find()
			->where(["branchId" => $job["branchId"], "status" => 1])
			->andWhere("clientId!=" . $job['clientId'])
			->asArray()
			->orderBy('clientName')
			->all();
		$text = $this->renderAjax('add_more', ["clients" => $clients, "job" => $job, "number" => $number]);
		$res["status"] = true;
		$res["textMore"] = $text;
		return json_encode($res);
	}
}
