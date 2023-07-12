<?php

namespace frontend\modules\job\controllers;

use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\SubFieldGroup;
use frontend\models\lower_management\SubmitReport;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\Type;
use Yii;
use yii\web\Controller;

class JobSummarizeController extends Controller
{
	public function actionIndex()
	{
		$year = date('Y');
		$branches = Branch::find()->where(["status" => 1])->asArray()->orderBy('branchName')->all();
		$data = [];
		$allJob = 0;
		$allClient = 0;
		if (isset($branches) && count($branches) > 0) {
			foreach ($branches as $b) :
				$totalJob = Job::totalJob($b["branchId"], $year);
				$totalClient = Branch::totalClient($b["branchId"], $year);
				$totalEmployee = Employee::totalEmployee($b["branchId"]);
				//$fee = Job::CalculateFee($b["branchId"], $year);
				$data[$b["branchId"]] = [
					"totalJob" => $totalJob,
					"totalClient" => $totalClient,
					//"fee" => $fee,
					"branchName" => $b["branchName"],
					"flag" => $b["flag"],
					"totalEmployee" => $totalEmployee,
					"totalChecklists" => Job::checkList($b["branchId"]),
					"totalManuals" => Job::manual($b["branchId"]),
					"totalNeedReport" => SubmitReport::totalNeed($b["branchId"])
				];
				$allJob += $totalJob;
				$allClient += $totalClient;

			endforeach;
		}
		// throw new Exception(print_r($data, true));
		return $this->render('index', [
			"data" => $data,
			"allJob" => $allJob,
			"allClient" => $allClient,
			"year" => $year
		]);
	}
	public function actionIndex2()
	{
		$jobs = [];
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_GM . ',' . Type::TYPE_STAFF;
		//$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$isManager = Type::checkType($right);

		//$currentMonthValue = date('m');
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranch = Employee::employeeBranch();
		$currentMonthValue = null;
		$currentMonthInt = null;
		$currentYear = date('Y');
		$branchId = null;
		$subFieldGroupId = null;
		$fieldPostId = null;
		$jobTypeId = null;
		$fieldId = null;
		$checkDate = null;
		$groupFields = [];
		$fields = [];
		// if (isset($_POST["year"])) {
		// 	$currentYear = $_POST["year"];
		// 	$currentMonthValue = $_POST["month"];
		// 	$branchId = $_POST["branch"];
		// 	$checkDate = $_POST["checkDate"];
		// 	$jobTypeId = $_POST["jobType"];
		// 	$subFieldGroupId = $_POST["subFieldGroup"];
		// 	$fieldPostId = $_POST["field"];
		// 	$fieldId = SubFieldGroup::fieldName($_POST["subFieldGroup"]);
		// 	if ($subFieldGroupId != "") {
		// 		$fields = Field::find()->select('fieldId,fieldName')
		// 			->where(["subFieldGroupId" => $subFieldGroupId, "status" => 1])
		// 			->asArray()
		// 			->all();
		// 	}
		// }
		if ($currentMonthValue != null) {
			$currentMonthInt = (int)$currentMonthValue;

			$currentMonthName = date('M', mktime(0, 0, 0, $currentMonthValue, 10));
			$currentMonth = [
				"value" => $currentMonthValue,
				"name" => $currentMonthName
			];
		} else {
			$currentMonth = [
				"value" => "",
				"name" => "Month"
			];
		}
		//$groups = FieldGroup::find()->select('fieldGroupId,fieldGroupName')->where(["status" => 1])->asArray()->all();$groupFields = [];
		$groups = SubFieldGroup::find()
			->select('sub_field_group.subFieldGroupId,sub_field_group.subFieldGroupName,fg.fieldGroupId,fg.fieldGroupName')
			->JOIN("LEFT JOIN", "field_group fg", "fg.fieldGroupId=sub_field_group.fieldGroupId")
			->where(["sub_field_group.status" => 1, "fg.status" => 1])
			->orderBy('fg.fieldGroupId,sub_field_group.subFieldGroupId')
			->asArray()
			->all();
		if (isset($groups) && count($groups) > 0) {
			foreach ($groups as $group) :
				$groupFields[$group["fieldGroupId"]][$group["subFieldGroupId"]] = [
					"name" => $group["subFieldGroupName"]
				];
			endforeach;
		}
		if ($isAdmin == 1) {
			$jobTypes = JobType::find()
				->select('job_type.jobTypeId,job_type.jobTypeName')
				->JOIN("LEFT JOIN", "job j", "j.jobTypeId=job_type.jobTypeId")
				->where(["job_type.status" => JobType::STATUS_ACTIVE])
				->andWhere("j.status!=99")
				->orderBy('job_type.jobTypeName')
				->all();
			$jobTypeStep = JobStep::find()
				->select('jt.jobTypeName,jt.jobTypeId,s.stepName,
			job_step.stepId,job_step.jobId,job_step.jobcategoryId,
			job_step.status as jsstatus,j.status as jstatus')
				->JOIN("LEFT JOIN", "job_category jc", "job_step.jobCategoryId=jc.jobCategoryId")
				->JOIN("LEFT JOIN", "step s", "job_step.stepId=s.stepId")
				->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=s.jobTypeId")
				->JOIN("LEFT JOIN", "job j", "j.jobTypeId=jt.jobTypeId")
				->where("job_step.status!=99")
				->andWhere(["jc.status" => [1, 4], "j.status" => [1, 4]])
				->andFilterWhere([
					"jc.fiscalYear" => $currentYear,
					"jc.startMonth" => $currentMonthInt,
					"j.jobTypeId" => $jobTypeId,
					"j.fieldId" => $fieldPostId,
					"job_step.dueDate" => $checkDate != null ? $checkDate . ' 00:00:00' : null
				])
				->groupBy('job_step.jobId,job_step.stepId')
				->orderBy('jt.jobTypeName,job_step.jobcategoryId DESC,job_step.stepId')
				->asArray()
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])->asArray()
				->orderBy('branchName')
				->all();
		} else {
			$jobTypes = JobType::find()
				->select('job_type.jobTypeId,job_type.jobTypeName')
				->JOIN("LEFT JOIN", "job j", "j.jobTypeId=job_type.jobTypeId")
				->where(["job_type.status" => JobType::STATUS_ACTIVE])
				->andWhere("j.status!=99")
				->andFilterWhere(["job_type.branchId" => $employeeBranch])
				->orderBy('job_type.jobTypeName')
				->all();
			$jobTypeStep = JobStep::find()
				->select('jt.jobTypeName,jt.jobTypeId,s.stepName,
			job_step.stepId,job_step.jobId,job_step.jobcategoryId,
			job_step.status as jsstatus,j.status as jstatus')
				->JOIN("LEFT JOIN", "job_category jc", "job_step.jobCategoryId=jc.jobCategoryId")
				->JOIN("LEFT JOIN", "step s", "job_step.stepId=s.stepId")
				->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=s.jobTypeId")
				->JOIN("LEFT JOIN", "job j", "j.jobTypeId=jt.jobTypeId")
				->where("job_step.status!=99")
				->andWhere(["jc.status" => [1, 4], "j.status" => [1, 4]])
				->andFilterWhere([
					"jc.fiscalYear" => $currentYear,
					"jc.startMonth" => $currentMonthInt,
					"j.branchId" => $employeeBranch,
					"j.jobTypeId" => $jobTypeId,
					"j.fieldId" => $fieldPostId,
					"job_step.dueDate" => $checkDate != null ? $checkDate . ' 00:00:00' : null
				])
				->groupBy('job_step.jobId,job_step.stepId')
				->orderBy('jt.jobTypeName,job_step.jobcategoryId DESC,job_step.stepId')
				->asArray()
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranch])->asArray()
				->all();
		}


		//throw new exception(print_r($jobTypeStep, true));
		$jobs = [];
		$a = [];
		if (isset($jobTypeStep) && count($jobTypeStep) > 0) {
			foreach ($jobTypeStep as $jobType) :
				if ($jobType["jsstatus"] == JobStep::STATUS_COMPLETE) {
					if (isset($jobs[$jobType["jobTypeId"]][$jobType["stepId"]]["finished"])) {
						$jobs[$jobType["jobTypeId"]][$jobType["stepId"]]["finished"]++;
					} else {
						$jobs[$jobType["jobTypeId"]][$jobType["stepId"]]["finished"] = 1;
					}
				} else {
					if (isset($jobs[$jobType["jobTypeId"]][$jobType["stepId"]]["onProcess"])) {
						$jobs[$jobType["jobTypeId"]][$jobType["stepId"]]["onProcess"]++;
					} else {
						$jobs[$jobType["jobTypeId"]][$jobType["stepId"]]["onProcess"] = 1;
					}
				}
			endforeach;
		}
		//throw new exception(print_r($a, true));
		return $this->render('index2', [
			"branch" => $branch,
			"isManager" => $isManager,
			"isAdmin" => $isAdmin,
			"employeeBranch" => $employeeBranch,
			"jobs" => $jobs,
			"currentMonth" => $currentMonth,
			"currentYear" => $currentYear,
			"branchId" => $branchId,
			"subFieldGroupId" => $subFieldGroupId,
			"groups" => $groups,
			"jobTypes" => $jobTypes,
			"jobTypeId" => $jobTypeId,
			"groupFields" => $groupFields,
			"fields" => $fields,
			"fieldPostId" => $fieldPostId,
			"checkDate" => $checkDate,
			"fieldId" => $fieldId
		]);
	}
	public function actionListJobType($hash)
	{
		$params = ModelMaster::decodeParams($hash);
		$jobTypeId = $params["jobTypeId"];
		$fiscalYear = $params["fiscalYear"];
		$clientId = $params["clientId"];
		$fieldId = $params["fieldId"];
		$clientId = $params["clientId"];
		$categoryId = $params["categoryId"];
		$status = $params["status"];
		$teamId = $params["teamId"];
		$currentMonthValue = $params["currentMonthValue"];
		$branchId = $params["branchId"];

		if ($status == JobCategory::STATUS_INPROCESS) {
			$textStatus = "On process";
		} else {
			$textStatus = "Fiinshed";
		}
		$jobList = [];
		$jobTypeName = JobType::jobTypeName($jobTypeId);
		$jobId = Job::countJobTypeJobSearch($branchId, $jobTypeId, $status, $fiscalYear, $currentMonthValue, $clientId, $fieldId, $categoryId, $teamId);
		$jobs = Job::find()
			->select('job.*,s.stepName,jc.targetDate,js.status as jsStatus,jc.status as jcStatus,jc.jobCategoryId')
			->JOIN("LEFT JOIN", "job_step js", "js.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
			->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
			->where([
				//"job.jobTypeId" => $jobTypeId,
				//"jc.fiscalYear" => $fiscalYear,
				//"job.status" => $status,
				"job.jobId" => $jobId
				//"jc.status" => $status

			])
			/*->andFilterWhere(
				[
					"job.fieldId" => $fieldId,
					"job.categoryId" => $categoryId,
					"job.clientId" => $clientId,
					"job.teamId" => $teamId,
					"jc.startMonth" => $currentMonthValue
				]

			)*/
			->asArray()
			->orderBy('jc.targetDate')
			->all();
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $job) :
				$lastTargetDate = JobCategory::CurrentTargetDate($job["jobId"]);
				$jobList[$job["jobId"]] = [
					"status" => $job["status"],
					"jobName" => $job["jobName"],
					"client" => Client::clientName($job["clientId"]),
					"currentStep" => JobStep::CurrentStepList($job["jobId"]),
					"targetDate" => $lastTargetDate,
					"current" => JobCategory::CurrrentCompleteDate($job["jobId"]),
					"previous" => JobCategory::PreviousCompleteDate($job["jobId"])
				];
			endforeach;
		}

		return $this->render('type_list', [
			"jobs" => $jobList,
			"status" => $textStatus,
			"jobTypeName" => $jobTypeName
		]);
	}
	public function actionUpdateJobCategory()
	{
		$jobCate = JobCategory::find()->where(["targetDate" => null, "status" => [1, 4, 10]])->all();
		if (isset($jobCate) && count($jobCate) > 0) {
			foreach ($jobCate as $jc) :
				$jobStep = JobStep::find()->where(["jobCategoryId" => $jc->jobCategoryId])->orderBy("jobStepId DESC")->one();
				if (isset($jobStep) && !empty($jobStep)) {
					if ($jobStep->dueDate != null) {
						$jc->targetDate = $jobStep->dueDate;
						$jc->save(false);
					}
				}

			endforeach;
		}
		$jobCate = JobCategory::find()->where(["startMonth" => null, "status" => [1, 4, 10]])->all();
		if (isset($jobCate) && count($jobCate) > 0) {
			foreach ($jobCate as $jc) :
				$jobStep = JobStep::find()->where(["jobCategoryId" => $jc->jobCategoryId])->orderBy("jobStepId")->one();
				if (isset($jobStep) && !empty($jobStep)) {
					if ($jobStep->dueDate != null) {
						$dateArr = explode(' ', $jobStep->dueDate);
						$date = explode('-', $dateArr[0]);
						$jc->startMonth = (int)$date[1];
					}
				} else {
					if ($jc->targetDate != null) {
						$dateArr = explode(' ', $jc->targetDate);
						$date = explode('-', $dateArr[0]);
						$jc->startMonth = (int)$date[1];
					}
				}
				$jc->save(false);
			endforeach;
		}
	}
	public function actionSearchSummarize()
	{

		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$dataIn = [
			"fiscalYear" => $_POST["fiscalYear"],
			"month" => $_POST["month"],
			"branchId" => $_POST["branchId"],
			"fieldId" => $_POST["fieldId"],
			"categoryId" => $_POST["categoryId"],
			"teamId" => $_POST["teamId"],
			"clientId" => $_POST["clientId"],
		];
		return $this->redirect(Yii::$app->homeUrl . 'job/job-summarize/show-result/' . ModelMaster::encodeParams(["filter" => $dataIn]));
	}
	public function actionShowResult($hash)
	{

		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_GM . "," . Type::TYPE_STAFF;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$currentMonth = [];
		$currentMonthValue = null;
		$currentMonthInt = null;
		$fieldId = null;
		$categoryId = null;
		$clientId = null;
		$teamId = null;
		$fields = [];
		$params = ModelMaster::decodeParams($hash);
		$jobTypeBranch = [];
		$fiscalYear = $params["filter"]["fiscalYear"];
		$branchId = $params["filter"]["branchId"];
		$branchName = Branch::branchName($branchId);
		$branchFlag = Branch::branchFlag($branchId);
		$currentMonthValue = $params["filter"]["month"];
		$fieldId = $params["filter"]["fieldId"];
		$clientId = $params["filter"]["clientId"];
		$categoryId = $params["filter"]["categoryId"];
		$teamId = $params["filter"]["teamId"];
		// $checkDate = $params["filter"]["checkDate"];
		// $jobTypeId = $params["filter"]["jobType"];
		// $subFieldGroupId = $params["filter"]["fieldGroup"];

		// if ($fieldPostId == null && $subFieldGroupId != null) {
		// 	$fieldPostId = SubFieldGroup::fieldName($params["filter"]["fieldGroup"]);
		// } else {
		// 	$fieldId = $fieldPostId;
		// }

		// if ($subFieldGroupId != "") {
		// 	$fields = Field::find()->select('fieldId,fieldName')
		// 		->where(["subFieldGroupId" => $subFieldGroupId, "status" => 1])
		// 		->asArray()
		// 		->all();
		// }
		if ($currentMonthValue != null) {
			$currentMonthInt = (int)$currentMonthValue;

			$currentMonthName = date('M', mktime(0, 0, 0, $currentMonthValue, 10));
			$currentMonth = [
				"value" => $currentMonthValue,
				"name" => $currentMonthName
			];
		} else {
			$currentMonth = [
				"value" => "",
				"name" => "Month"
			];
		}
		$month = ModelMaster::month();
		$jobType = JobType::find()
			->select('job_type.jobTypeName,job_type.jobTypeId')
			->JOIN("LEFT JOIN", "job j", "j.jobTypeId=job_type.jobTypeId")
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=j.jobId")
			->where([
				"job_type.status" => JobType::STATUS_ACTIVE,
				"job_type.branchId" => $branchId,
				"jc.fiscalYear" => $fiscalYear,
				"jc.status" => [1, 4],
				"j.status" => [1, 4]
			])
			->andFilterWhere(
				[
					"j.fieldId" => $fieldId,
					"j.categoryId" => $categoryId,
					"j.clientId" => $clientId,
					"j.teamId" => $teamId,
					"jc.startMonth" => $currentMonthValue
				]
			)
			->asArray()
			->orderBy('job_type.jobTypeName')
			->all();
		if (isset($jobType) && count($jobType) > 0) {
			foreach ($jobType as $j) :
				$jobTypeBranch[$j["jobTypeId"]] = [
					"jobTypeName" => $j["jobTypeName"],
					"totalFinished" => Job::countJobTypeJob($branchId, $j["jobTypeId"], Job::STATUS_COMPLETE, $fiscalYear, $currentMonthValue, $clientId, $fieldId, $categoryId, $teamId),
					"totalInprocess" => Job::countJobTypeJob($branchId, $j["jobTypeId"], Job::STATUS_INPROCESS, $fiscalYear, $currentMonthValue, $clientId, $fieldId, $categoryId, $teamId),
				];
			endforeach;
		}
		$category = Category::find()
			->select('categoryId,categoryName')
			->where(["status" => Category::STATUS_ACTIVE])
			->orderBy('categoryName')
			->asArray()->all();
		$fields = Field::find()
			->select('fieldId,fieldName')
			->where(["status" => Field::STATUS_ACTIVE, "branchId" => $branchId])
			->orderBy('fieldName')
			->asArray()->all();
		$teams = Team::find()
			->select('teamId,teamName')
			->where(["status" => Team::STATUS_ACTIVE, "branchId" => $branchId])
			->orderBy("teamName")
			->asArray()->all();
		$clients = Client::find()
			->select('client.clientId,client.clientName')
			->JOIN("LEFT JOIN", "job j", "j.clientId=client.clientId")
			->where(["client.status" => 1, "j.status" => [1, 4], "client.branchId" => $branchId])
			->orderBy('clientName')
			->asArray()
			->all();
		//$groups = FieldGroup::find()->select('fieldGroupId,fieldGroupName')->where(["status" => 1])->asArray()->all();$groupFields = [];

		return $this->render('branch_summary', [
			"jobTypeBranch" => $jobTypeBranch,
			"branchName" => $branchName,
			"branchFlag" => $branchFlag,
			"year" => $fiscalYear,
			"currentMonth" => $currentMonth,
			"category" => $category,
			"fields" => $fields,
			"teams" => $teams,
			"month" => $month,
			"clients" => $clients,
			"branchId" => $branchId,
			"fieldId" => $fieldId,
			"categoryId" => $categoryId,
			"clientId" => $clientId,
			"teamId" => $teamId,
			"currentMonthValue" => $currentMonthValue
		]);
	}
	public function actionBranchSummary($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$branchName = Branch::branchName($branchId);
		$branchFlag = Branch::branchFlag($branchId);
		$jobTypeBranch = [];
		$fiscalYear = date('Y');
		// $currentMonth = [
		// 	"value" => date('m'),
		// 	"name" => date('M'),
		// ];
		$currentMonth = [];
		$currentMonthValue = null;
		$fieldId = null;
		$categoryId = null;
		$clientId = null;
		$teamId = null;
		$month = ModelMaster::month();
		$jobType = JobType::find()
			->select('job_type.jobTypeName,job_type.jobTypeId')
			->JOIN("LEFT JOIN", "job j", "j.jobTypeId=job_type.jobTypeId")
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=j.jobId")
			->where([
				"job_type.status" => JobType::STATUS_ACTIVE,
				"job_type.branchId" => $branchId,
				"jc.fiscalYear" => $fiscalYear,
				"jc.status" => [1, 4],
				"j.status" => [1, 4]

			])
			->asArray()
			->orderBy('job_type.jobTypeName')
			// ->groupBy('job_type.jobTypeId')
			->all();
		if (isset($jobType) && count($jobType) > 0) {
			foreach ($jobType as $j) :
				$jobTypeBranch[$j["jobTypeId"]] = [
					"jobTypeName" => $j["jobTypeName"],
					"totalFinished" => Job::countJobTypeJob($branchId, $j["jobTypeId"], Job::STATUS_COMPLETE, $fiscalYear, $currentMonthValue, $clientId, $fieldId, $categoryId, $teamId),
					"totalInprocess" => Job::countJobTypeJob($branchId, $j["jobTypeId"], Job::STATUS_INPROCESS, $fiscalYear, $currentMonthValue, $clientId, $fieldId, $categoryId, $teamId),
				];
			endforeach;
		}
		$category = Category::find()
			->select('categoryId,categoryName')
			->where(["status" => Category::STATUS_ACTIVE])
			->orderBy('categoryName')
			->asArray()->all();
		$fields = Field::find()
			->select('fieldId,fieldName')
			->where(["status" => Field::STATUS_ACTIVE, "branchId" => $branchId])
			->orderBy('fieldName')
			->asArray()->all();
		$teams = Team::find()
			->select('teamId,teamName')
			->where(["status" => Team::STATUS_ACTIVE, "branchId" => $branchId])
			->orderBy("teamName")
			->asArray()->all();
		$clients = Client::find()
			->select('client.clientId,client.clientName')
			->JOIN("LEFT JOIN", "job j", "j.clientId=client.clientId")
			->where(["client.status" => 1, "j.status" => [1, 4], "client.branchId" => $branchId])
			->orderBy('clientName')
			->asArray()
			->all();
		return $this->render('branch_summary', [
			"jobTypeBranch" => $jobTypeBranch,
			"branchName" => $branchName,
			"branchFlag" => $branchFlag,
			"year" => $fiscalYear,
			"category" => $category,
			"fields" => $fields,
			"teams" => $teams,
			"month" => $month,
			"clients" => $clients,
			"branchId" => $branchId,
			"currentMonth" => $currentMonth,
			"fieldId" => $fieldId,
			"categoryId" => $categoryId,
			"clientId" => $clientId,
			"teamId" => $teamId,
			"currentMonthValue" => $currentMonthValue
		]);
	}
}
