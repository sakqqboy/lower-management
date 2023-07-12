<?php

namespace frontend\modules\job\controllers;

use common\carlendar\Carlendar;
use common\email\Email;
use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\AdditionalStep;
use frontend\models\lower_management\AddjustDuedateAdditional;
use frontend\models\lower_management\AdjustDuedate;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Currency;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobAlert;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobComplain;
use frontend\models\lower_management\JobResponsibility;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\LogCancel;
use frontend\models\lower_management\LogFee;
use frontend\models\lower_management\LogFiscalYear;
use frontend\models\lower_management\LogJobCategory;
use frontend\models\lower_management\LogJobPic;
use frontend\models\lower_management\LogJobStep;
use frontend\models\lower_management\LogJobTeam;
use frontend\models\lower_management\LogSubStep;
use frontend\models\lower_management\LogTargetDate;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\SubFieldGroup;
use frontend\models\lower_management\SubmitReport;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use frontend\models\lower_management\Type;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\debug\LogTarget;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `job` module
 */
class DetailController extends Controller
{
	public function actionIndex()
	{
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$category = Category::find()->select('categoryName,categoryId')
			->where(["status" => Category::STATUS_ACTIVE])
			->asArray()
			->all();
		$branchId = "";
		$employeeType = EmployeeType::findEmployeeType();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		$fag = 0;
		$onlyManager = 0;
		$rightBranch = Type::TYPE_MANAGER;
		$teamId = Employee::employeeTeam();
		$teams = [];
		$persons = [];
		$report = [];
		if (count($employeeType) > 0) {
			foreach ($employeeType as $all) :
				if (in_array($all, $rightAll)) {
					$fag = 1;
				}
			endforeach;
		}
		if (count($employeeType) > 0) {
			if (in_array($rightBranch, $employeeType)) {
				if (count($employeeType) == 1) {
					$onlyManager = 1;
				} else {
					$onlyManager = 0;
				}
			}
		}
		if ($fag == 1) { //MANAGER GM ADMIN

			$query = Job::find()
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->where("job.status!=" . Job::STATUS_DELETED)
				->orderBy('c.clientName ASC');
			$report = Job::find()
				->select('jobId')
				->where("status!=" . Job::STATUS_DELETED . " and report=1")
				->asArray()
				->all();
			$branch = Branch::find()
				->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->orderBy('branchName')
				->asArray()
				->all();
			$jobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["status" => JobType::STATUS_ACTIVE])
				->asarray()
				->orderBy('jobTypeName')
				->all();
			$fields = Field::find()
				->select('fieldName,fieldId')
				->where(["status" => Field::STATUS_ACTIVE])
				->asArray()
				->orderBy('fieldName ASC')
				->all();
		} else { //NORMAL STAFF
			$branchId = Employee::employeeBranch();
			$query = Job::find()
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->where("job.status!=" . Job::STATUS_DELETED)
				->andWhere(["job.teamId" => $teamId])
				->orderBy('c.clientName ASC');
			$report = Job::find()
				->select('jobId')
				->where("status!=" . Job::STATUS_DELETED . " and report=1")
				->andWhere(["teamId" => $teamId])
				->asArray()
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $branchId])
				->orderBy('branchName')
				->asArray()
				->all();
			$jobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $branchId])
				->asarray()
				->orderBy('jobTypeName')
				->all();
			if ($onlyManager == 1) {
				$query = Job::find()
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->where("job.status!=" . Job::STATUS_DELETED)
					->andWhere(["job.branchId" => $branchId])
					->orderBy('c.clientName ASC');
				$report = Job::find()
					->select('jobId')
					->where("status!=" . Job::STATUS_DELETED . " and report=1")
					->andWhere(["branchId" => $branchId])
					->asArray()
					->all();
			}
			$teams = Team::find()->select('teamId,teamName')
				->where(["branchId" => $branchId, "status" => 1])
				->orderBy('teamName')
				->asarray()
				->all();
			$persons = Employee::find()
				->select('employeeNickName as nickName,employeeId as emId')
				->andWhere(["status" => Employee::STATUS_CURRENT])
				//->andWhere(["teamId" => $teamId])
				->andFilterWhere(["teamId" => $teamId])
				->asArray()
				->orderBy('nickName')
				->all();
			$fields = Field::find()
				->select('fieldName,fieldId')
				->where(["status" => Field::STATUS_ACTIVE, "branchId" => $branchId])
				->asArray()
				->orderBy('fieldName ASC')
				->all();
		}

		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			//'sort' => ['attributes' => ['targetDate', 'dueDate']],
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		$date = date('Y-m-d');
		$dateValue = Carlendar::currentMonth($date);
		$selectMonth = date('m');
		$selectDate = ModelMaster::engDate($date, 1);
		$jobStatus[Job::STATUS_INPROCESS] = "Inprocess";
		$jobStatus[Job::STATUS_COMPLETE] = "Complete";
		$groupFields = [];
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
		$submit = SubmitReport::countSubmit($report);
		$notSubmit = SubmitReport::countNotSubmit($report);

		return $this->render('index', [
			// "job" => $job,
			"branch" => $branch,
			"category" => $category,
			"dataProviderJob" => $dataProviderJob,
			"dateValue" => $dateValue,
			"selectMonth" => $selectMonth,
			"selectDate" => $selectDate,
			"jobStatus" => $jobStatus,
			"fields" => $fields,
			"branchId" => $branchId,
			"teams" => $teams,
			"teamId" => $teamId,
			"persons" => $persons,
			"client" => null,
			"groupFields" => $groupFields,
			"jobType" => $jobType,
			"submit" => $submit,
			"notSubmit" => $notSubmit

		]);
	}
	public function actionSearchJob()
	{
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$statusArr = [];
		$i = 0;
		if (isset($_POST["status"]) && count($_POST["status"]) > 0) {
			foreach ($_POST["status"] as $status) :
				if ($status != '') {
					$statusArr[$i] = $status;
					$i++;
				}
			endforeach;
		}
		if ($_POST["branchId"] == null && $_POST["categoryId"] == null && $_POST["fieldId"] == null && $_POST["fieldId"] == null && count($statusArr) == 0 && $_POST["personId"] == null && $_POST["groupFieldId"] == null && $_POST["jobTypeId"] == null && $_POST["sortStep"] == 0 && $_POST["sortFinal"] == 0 && $_POST["report"] == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'job/detail/index');
		}

		$dataIn = [
			"branchId" => $_POST["branchId"],
			"categoryId" => $_POST["categoryId"],
			"fieldId" => $_POST["fieldId"],
			"teamId" => $_POST["teamId"],
			//"status" => $_POST["status"],
			"status" => $statusArr,
			"personId" => $_POST["personId"],
			"clientId" => $_POST["clientId"],
			"groupFieldId" => $_POST["groupFieldId"],
			"jobTypeId" => $_POST["jobTypeId"],
			"sortStep" => $_POST["sortStep"],
			"sortFinal" => $_POST["sortFinal"],
			"report" => $_POST["report"]
		];

		if ($_POST["sortStep"] == 0 && $_POST["sortFinal"] == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'job/detail/show-result2/' . ModelMaster::encodeParams(["filter" => $dataIn]));
		}
		return $this->redirect(Yii::$app->homeUrl . 'job/detail/show-result/' . ModelMaster::encodeParams(["filter" => $dataIn]));
	}
	public function actionShowResult($hash)
	{

		$params = ModelMaster::decodeParams($hash);
		//throw new Exception(print_r($params, true));
		$branchId = $params["filter"]["branchId"];
		$categoryId = $params["filter"]["categoryId"];
		$fieldId = $params["filter"]["fieldId"];
		$postFieldId = $params["filter"]["fieldId"];
		$personId = $params["filter"]["personId"];
		$postTeamId = $params["filter"]["teamId"];
		$postStatus = $params["filter"]["status"];
		$postClientId = $params["filter"]["clientId"];
		$postGroupFieldId = $params["filter"]["groupFieldId"];
		$postJobTypeId = $params["filter"]["jobTypeId"];
		$postSortStep = $params["filter"]["sortStep"];
		$postSortFinal = $params["filter"]["sortFinal"];
		$report = $params["filter"]["report"];
		$showMaxminStep = '';
		$showMinmaxStep = '';
		$showMaxminFinal = '';
		$showMinmaxFinal = '';
		$sortStep = '';
		$sortFinal = '';
		$submit = 0;
		$notSubmit = 0;
		$employeeType = EmployeeType::findEmployeeType();
		$employeeBranchId = Employee::employeeBranch();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		$fag = 0;
		$status = '';
		$team = [];
		$teamId = Employee::employeeTeam();
		$person = [];
		$client = [];
		$reportJob = [];
		$onlyManager = 0;
		$orderBy = "c.clientName ASC";
		$rightBranch = Type::TYPE_MANAGER;
		if ($postSortStep == 0) {
			$showMaxminStep = '';
			$showMinmaxStep = 'none';
			$sortStep = "c.clientName ASC";
		}
		if ($postSortStep == 1) {
			$showMaxminStep = '';
			$showMinmaxStep = 'none';
			$sortStep = "jcTargetDate DESC";
		}
		if ($postSortStep == 2) {
			$showMaxminStep = 'none';
			$showMinmaxStep = '';
			$sortStep = "jcTargetDate ASC";
		}
		if ($postSortFinal == 0) {
			$showMaxminFinal = '';
			$showMinmaxFinal = 'none';
			$sortFinal = "c.clientName ASC";
		}
		if ($postSortFinal == 1) {
			$showMaxminFinal = '';
			$showMinmaxFinal = 'none';
			$sortFinal = "jcTargetDate DESC";
		}
		if ($postSortFinal == 2) {
			$showMaxminFinal = 'none';
			$showMinmaxFinal = '';
			$sortFinal = "jcTargetDate ASC";
		}
		if ($sortStep == 0 && $sortFinal == 0) {
			$orderBy = "c.clientName ASC";
		}
		if ($sortFinal != 0) {
			$orderBy = $sortFinal;
		}
		if ($sortStep != 0) {
			$orderBy = $sortStep;
		}
		if (count($rightAll) > 0) {
			foreach ($employeeType as $all) :
				if (in_array($all, $rightAll)) {
					$fag = 1;
				}
			endforeach;
		}
		if (count($employeeType) > 0) {
			if (in_array($rightBranch, $employeeType)) {
				if (count($employeeType) == 1) {
					$onlyManager = 1;
				} else {
					$onlyManager = 0;
				}
			}
		}
		if ($report == 0) {
			$report = null;
		}
		$jobId = null;
		if (count($postStatus) > 0) {
			$jobIds = Job::findJobIdByStatus2($postStatus, $branchId);
			if ($jobIds != '') {
				$jobId = explode(',', $jobIds);
			}
		}
		if ($jobId == '') {
			$jobId = null;
		}
		if ($fieldId == null && $postGroupFieldId != null) {

			$fieldId = SubFieldGroup::fieldName($postGroupFieldId);
		}
		if ($fieldId != null && $postGroupFieldId != null) {
			$fieldIdArr = SubFieldGroup::fieldName($postGroupFieldId);
			if (!in_array($fieldId, $fieldIdArr)) {
				$postFieldId = "";
			}
		}
		if ($fag == 1) { // GM SUPERVISOR MANAGER

			$query = Job::find()
				->select("job.*,MAX(jc.targetDate) as jcTargetDate,Max(jc.jobCategoryId) as jobCategoryId,js.jobCategoryId as jsJobCat,
				jc.jobId as jcJobId,js.jobId as jsJobId,MIN(js.status),
				CASE js.status WHEN 1 THEN MIN(js.jobStepId) WHEN 4 THEN MAX(js.jobStepId) ELSE 0 END AS jsJobStepId,c.clientName
				")
				->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
				->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
				//->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->where("job.status!=" . Job::STATUS_DELETED)
				->andWhere([
					"js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE],
					"jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE]
				])
				->andFilterWhere([
					"job.jobId" => $jobId,
					"jr.employeeId" => $personId,
					"job.branchId" => $branchId,
					"job.categoryId" => $categoryId,
					"job.fieldId" => $fieldId,
					"job.teamId" => $postTeamId,
					"job.clientId" => $postClientId,
					"job.jobTypeId" => $postJobTypeId,
					"report" => $report
				])
				->orderBy($orderBy)
				->groupBy('jc.jobId,js.jobId');
			$reportJob = Job::find()
				->select('job.jobId')
				->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
				->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
				//->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
				//->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->where("job.status!=" . Job::STATUS_DELETED)
				->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
				->andWhere(["jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE], "job.report" => 1])
				->andFilterWhere([
					"job.jobId" => $jobId,
					"jr.employeeId" => $personId,
					"job.branchId" => $branchId,
					"job.categoryId" => $categoryId,
					"job.fieldId" => $fieldId,
					"job.teamId" => $postTeamId,
					"job.clientId" => $postClientId,
					"job.jobTypeId" => $postJobTypeId,
					"report" => $report
				])
				//->orderBy($orderBy)
				->groupBy('jc.jobId,js.jobId')
				->asArray()
				->all();
			$team = Team::find()->select('teamId,teamName')
				->where(["branchId" => $branchId, "status" => Team::STATUS_ACTIVE])
				->orderBy('teamName')
				->asArray()
				->all();
			$client = Client::find()
				->where(["status" => Client::STATUS_ACTIVE, "branchId" => $branchId])
				->andWhere("branchId!=0")
				->asArray()
				->orderBy('clientName')
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->orderBy('branchName')
				->asArray()
				->all();
			if ($postTeamId != '') {
				$persons = Employee::find()
					->select('employeeNickName as nickName,employeeId as emId')
					->andWhere(["status" => Employee::STATUS_CURRENT])
					->andFilterWhere(["branchId" => $branchId, "teamId" => $postTeamId])
					->asArray()
					->orderBy('nickName')
					->all();
			} else {
				$persons = Employee::find()
					->select('employeeNickName as nickName,employeeId as emId')
					->andWhere(["status" => Employee::STATUS_CURRENT])
					->andFilterWhere(["branchId" => $branchId])
					->asArray()
					->orderBy('nickName')
					->all();
			}
			$jobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["status" => JobType::STATUS_ACTIVE])
				->andFilterWhere(["branchId" => $branchId])
				->asarray()
				->orderBy('jobTypeName')
				->all();
		} else { //NORMAL STAFF
			if ($onlyManager == 1) {
				$query = Job::find()
					->select("job.*,MAX(jc.targetDate) as jcTargetDate,Max(jc.jobCategoryId) as jobCategoryId,js.jobCategoryId as jsJobCat,
				jc.jobId as jcJobId,js.jobId as jsJobId,MIN(js.status),
				CASE js.status WHEN 1 THEN MIN(js.jobStepId) WHEN 4 THEN MAX(js.jobStepId) ELSE 0 END AS jsJobStepId,c.clientName
				")
					->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
					->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
					//->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where("job.status!=" . Job::STATUS_DELETED)
					->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
					->andWhere([
						"jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE],
						"job.branchId" => $employeeBranchId
					])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					->orderBy($orderBy)
					->groupBy('jc.jobId,js.jobId');
				$reportJob = Job::find()
					->select('job.jobId')
					->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
					->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
					//->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
					//->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where("job.status!=" . Job::STATUS_DELETED)
					->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
					->andWhere(["jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE]])
					->andWhere(["job.branchId" => $employeeBranchId, "job.report" => 1])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					//->orderBy($orderBy)
					->groupBy('jc.jobId,js.jobId')
					->asArray()
					->all();
			} else {
				$query = Job::find()
					->select("job.*,MAX(jc.targetDate) as jcTargetDate,Max(jc.jobCategoryId) as jobCategoryId,js.jobCategoryId as jsJobCat,
				jc.jobId as jcJobId,js.jobId as jsJobId,MIN(js.status),
				CASE js.status WHEN 1 THEN MIN(js.jobStepId) WHEN 4 THEN MAX(js.jobStepId) ELSE 0 END AS jsJobStepId,c.clientName
				")
					->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
					->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
					//->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where("job.status!=" . Job::STATUS_DELETED)
					->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
					->andWhere(["jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE]])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					->orderBy($orderBy)
					->groupBy('jc.jobId,js.jobId');
				$reportJob = Job::find()
					->select('job.jobId')
					->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
					->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
					// ->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
					// ->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where("job.status!=" . Job::STATUS_DELETED)
					->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
					->andWhere(["jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE], "job.report" => 1])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					//->orderBy($orderBy)
					->groupBy('jc.jobId,js.jobId')
					->asArray()
					->all();
			}
			$team = Team::find()->select('teamId,teamName')
				->where(["branchId" => $employeeBranchId, "status" => Team::STATUS_ACTIVE])
				->asArray()
				->all();
			$client = Client::find()
				->where(["branchId" => $employeeBranchId, "status" => Client::STATUS_ACTIVE])
				->andWhere("branchId!=0")
				->asArray()
				->orderBy('clientName')
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranchId])
				->orderBy('branchName')
				->asArray()
				->all();
			$persons = Employee::find()
				->select('employeeNickName as nickName,employeeId as emId')
				->andWhere(["status" => Employee::STATUS_CURRENT])
				->andFilterWhere(["teamId" => $postTeamId, "branchId" => $branchId])
				->asArray()
				->orderBy('nickName')
				->all();
			$jobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $employeeBranchId])
				->asarray()
				->orderBy('jobTypeName')
				->all();
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,

			'pagination' => [
				'pageSize' => 50,
			],
		]);

		$category = Category::find()->select('categoryName,categoryId')
			->where(["status" => Category::STATUS_ACTIVE])
			->asArray()
			->all();
		$groupFields = [];
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
		$fields = Field::find()
			->select('fieldName,fieldId')
			->where(["status" => Field::STATUS_ACTIVE])
			->andFilterWhere([
				"branchId" => $branchId,
				"subFieldGroupId" => $postGroupFieldId
			])
			->asArray()
			->orderBy('fieldName ASC')
			->all();
		$submit = SubmitReport::countSubmit($reportJob);
		$notSubmit = SubmitReport::countNotSubmit($reportJob);
		return $this->render('search_result2', [
			"dataProviderJob" => $dataProviderJob,
			"branch" => $branch,
			"category" => $category,
			//"dataProviderJob" => $dataProviderJob,
			"fields" => $fields,
			"branchId" => $branchId,
			"categoryId" => $categoryId,
			"fieldId" => $postFieldId,
			"personId" => $personId,
			"postTeamId" => $postTeamId,
			"postStatus" => $postStatus,
			"team" => $team,
			"persons" => $persons,
			"groupFields" => $groupFields,
			"client" => $client,
			"groupFieldId" => $postGroupFieldId,
			"clientId" => $postClientId,
			"jobType" => $jobType,
			"postJobTypeId" => $postJobTypeId,
			"postSortFinal" => $postSortFinal,
			"postSortStep" => $postSortStep,
			"showMaxminStep" => $showMaxminStep,
			"showMinmaxStep" => $showMinmaxStep,
			"showMaxminFinal" => $showMaxminFinal,
			"showMinmaxFinal" => $showMinmaxFinal,
			"report" => $report,
			"submit" => $submit,
			"notSubmit" => $notSubmit
		]);
	}
	public function actionShowResult2($hash)
	{

		$params = ModelMaster::decodeParams($hash);
		//throw new Exception(print_r($params, true));
		$branchId = $params["filter"]["branchId"];
		$categoryId = $params["filter"]["categoryId"];
		$fieldId = $params["filter"]["fieldId"];
		$postFieldId = $params["filter"]["fieldId"];
		$personId = $params["filter"]["personId"];
		$postTeamId = $params["filter"]["teamId"];
		$postStatus = $params["filter"]["status"];
		$postClientId = $params["filter"]["clientId"];
		$postGroupFieldId = $params["filter"]["groupFieldId"];
		$postJobTypeId = $params["filter"]["jobTypeId"];
		$postSortStep = $params["filter"]["sortStep"];
		$postSortFinal = $params["filter"]["sortFinal"];
		$report = $params["filter"]["report"];
		$showMaxminStep = '';
		$showMinmaxStep = '';
		$showMaxminFinal = '';
		$showMinmaxFinal = '';
		$sortStep = '';
		$sortFinal = '';
		$submit = 0;
		$notSubmit = 0;
		$employeeType = EmployeeType::findEmployeeType();
		$employeeBranchId = Employee::employeeBranch();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		$fag = 0;
		$status = '';
		$team = [];
		$teamId = Employee::employeeTeam();
		$person = [];
		$client = [];
		$reportJob = [];
		$onlyManager = 0;
		$orderBy = "c.clientName ASC";
		$showMaxminStep = '';
		$showMinmaxStep = 'none';
		$showMaxminFinal = '';
		$showMinmaxFinal = 'none';
		$rightBranch = Type::TYPE_MANAGER;
		if (count($rightAll) > 0) {
			foreach ($employeeType as $all) :
				if (in_array($all, $rightAll)) {
					$fag = 1;
				}
			endforeach;
		}
		if (count($employeeType) > 0) {
			if (in_array($rightBranch, $employeeType)) {
				if (count($employeeType) == 1) {
					$onlyManager = 1;
				} else {
					$onlyManager = 0;
				}
			}
		}
		if ($report == 0) {
			$report = null;
		}
		$jobId = null;
		if (count($postStatus) > 0) {
			$jobIds = Job::findJobIdByStatus2($postStatus, $branchId);
			if ($jobIds != '') {
				$jobId = explode(',', $jobIds);
			}
		}
		if ($jobId == '') {
			$jobId = null;
		}
		if ($fieldId == null && $postGroupFieldId != null) {

			$fieldId = SubFieldGroup::fieldName($postGroupFieldId);
		}
		if ($fieldId != null && $postGroupFieldId != null) {
			$fieldIdArr = SubFieldGroup::fieldName($postGroupFieldId);
			if (!in_array($fieldId, $fieldIdArr)) {
				$postFieldId = "";
			}
		}
		if ($fag == 1) { // GM SUPERVISOR MANAGER
			$query = Job::find()
				->select("job.*,c.clientName")
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->where(["job.status" => [Job::STATUS_INPROCESS, Job::STATUS_COMPLETE]])
				->andFilterWhere([
					"job.jobId" => $jobId,
					"jr.employeeId" => $personId,
					"job.branchId" => $branchId,
					"job.categoryId" => $categoryId,
					"job.fieldId" => $fieldId,
					"job.teamId" => $postTeamId,
					"job.clientId" => $postClientId,
					"job.jobTypeId" => $postJobTypeId,
					"job.report" => $report
				])
				->orderBy($orderBy)
				->groupBy('job.jobId');

			//throw new exception(print_r($query2, true));
			//->groupBy('jc.jobId,js.jobId');
			$reportJob = Job::find()
				->select('job.jobId')
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->where("job.status!=" . Job::STATUS_DELETED)
				->andWhere(["job.report" => 1])
				->andFilterWhere([
					"job.jobId" => $jobId,
					"jr.employeeId" => $personId,
					"job.branchId" => $branchId,
					"job.categoryId" => $categoryId,
					"job.fieldId" => $fieldId,
					"job.teamId" => $postTeamId,
					"job.clientId" => $postClientId,
					"job.jobTypeId" => $postJobTypeId,
					"report" => $report
				])
				->groupBy('job.jobId')
				->asArray()
				->all();
			$team = Team::find()->select('teamId,teamName')
				->where(["branchId" => $branchId, "status" => Team::STATUS_ACTIVE])
				->asArray()
				->all();
			$client = Client::find()
				->where(["status" => Client::STATUS_ACTIVE, "branchId" => $branchId])
				->andWhere("branchId!=0")
				->asArray()
				->orderBy('clientName')
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->orderBy('branchName')
				->asArray()
				->all();
			if ($postTeamId != '') {
				$persons = Employee::find()
					->select('employeeNickName as nickName,employeeId as emId')
					->andWhere(["status" => Employee::STATUS_CURRENT])
					->andFilterWhere(["branchId" => $branchId, "teamId" => $postTeamId])
					->asArray()
					->orderBy('nickName')
					->all();
			} else {
				$persons = Employee::find()
					->select('employeeNickName as nickName,employeeId as emId')
					->andWhere(["status" => Employee::STATUS_CURRENT])
					->andFilterWhere(["branchId" => $branchId])
					->asArray()
					->orderBy('nickName')
					->all();
			}
			$jobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["status" => JobType::STATUS_ACTIVE])
				->andFilterWhere(["branchId" => $branchId])
				->asarray()
				->orderBy('jobTypeName')
				->all();
		} else { //NORMAL STAFF
			if ($onlyManager == 1) {
				$query = Job::find()
					->select("job.*,c.clientName")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->where([
						"job.status" => [Job::STATUS_INPROCESS, Job::STATUS_COMPLETE],
						"job.branchId" => $employeeBranchId
					])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					->orderBy($orderBy)
					->groupBy('job.jobId');
				$reportJob = Job::find()
					->select('job.jobId')
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where([
						"job.status" => [Job::STATUS_INPROCESS, Job::STATUS_COMPLETE],
						"job.branchId" => $employeeBranchId,
						"job.report" => 1
					])->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					//->orderBy($orderBy)
					->groupBy('job.jobId')
					->asArray()
					->all();
			} else {
				$query = Job::find()
					->select("job.*,c.clientName")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where([
						"job.status" => [Job::STATUS_INPROCESS, Job::STATUS_COMPLETE]
					])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					->orderBy($orderBy)
					->groupBy('job.jobId');
				$reportJob = Job::find()
					->select('job.jobId')
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where([
						"job.status" => [Job::STATUS_INPROCESS, Job::STATUS_COMPLETE],
						"job.report" => 1
					])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $postClientId,
						"job.jobTypeId" => $postJobTypeId,
						"report" => $report
					])
					->groupBy('job.jobId')
					->asArray()
					->all();
			}
			$team = Team::find()->select('teamId,teamName')
				->where(["branchId" => $employeeBranchId, "status" => Team::STATUS_ACTIVE])
				->orderBy('teamName')
				->asArray()
				->all();
			$client = Client::find()
				->where(["branchId" => $employeeBranchId, "status" => Client::STATUS_ACTIVE])
				->andWhere("branchId!=0")
				->asArray()
				->orderBy('clientName')
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranchId])
				->orderBy('branchName')
				->asArray()
				->all();
			$persons = Employee::find()
				->select('employeeNickName as nickName,employeeId as emId')
				->andWhere(["status" => Employee::STATUS_CURRENT])
				->andFilterWhere(["teamId" => $postTeamId, "branchId" => $branchId])
				->asArray()
				->orderBy('nickName')
				->all();
			$jobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $employeeBranchId])
				->asarray()
				->orderBy('jobTypeName')
				->all();
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,

			'pagination' => [
				'pageSize' => 50,
			],
		]);

		$category = Category::find()->select('categoryName,categoryId')
			->where(["status" => Category::STATUS_ACTIVE])
			->asArray()
			->all();
		$groupFields = [];
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
		$fields = Field::find()
			->select('fieldName,fieldId')
			->where(["status" => Field::STATUS_ACTIVE])
			->andFilterWhere([
				"branchId" => $branchId,
				"subFieldGroupId" => $postGroupFieldId
			])
			->asArray()
			->orderBy('fieldName ASC')
			->all();
		$submit = SubmitReport::countSubmit($reportJob);
		$notSubmit = SubmitReport::countNotSubmit($reportJob);
		return $this->render('search_result2', [
			"dataProviderJob" => $dataProviderJob,
			"branch" => $branch,
			"category" => $category,
			//"dataProviderJob" => $dataProviderJob,
			"fields" => $fields,
			"branchId" => $branchId,
			"categoryId" => $categoryId,
			"fieldId" => $postFieldId,
			"personId" => $personId,
			"postTeamId" => $postTeamId,
			"postStatus" => $postStatus,
			"team" => $team,
			"persons" => $persons,
			"groupFields" => $groupFields,
			"client" => $client,
			"groupFieldId" => $postGroupFieldId,
			"clientId" => $postClientId,
			"jobType" => $jobType,
			"postJobTypeId" => $postJobTypeId,
			"postSortFinal" => $postSortFinal,
			"postSortStep" => $postSortStep,
			"showMaxminStep" => $showMaxminStep,
			"showMinmaxStep" => $showMinmaxStep,
			"showMaxminFinal" => $showMaxminFinal,
			"showMinmaxFinal" => $showMinmaxFinal,
			"report" => $report,
			"submit" => $submit,
			"notSubmit" => $notSubmit
		]);
	}
	public function actionSearchFilter()
	{
		$textTeam = '<option value="">Team</option>';
		$textClient = '<option value="">Client</option>';
		$textJobType = '<option value="">Jop Type</option>';
		//$textTeam = '';
		//$textPerson = '<option value="">Person</option>';
		//$employeeType = EmployeeType::findEmployeeType();
		//$rightAll = [Type::TYPE_ADMIN, Type::TYPE_MANAGER, Type::TYPE_GM];
		//$fag = 0;
		//$teamId = Employee::employeeTeam();
		//if (count($rightAll) > 0) {
		// foreach ($employeeType as $all) :
		// 		if (in_array($all, $rightAll)) {
		// 			$fag = 1;
		// 		}
		// 	endforeach;
		// }
		//if ($fag == 1) {
		$team = Team::find()
			->select('teamId,teamName')
			->where(["branchId" => $_POST["branchId"], "status" => Team::STATUS_ACTIVE])
			->orderBy("teamName")
			->asArray()
			->all();

		$clients = Client::find()
			->select('clientId,clientName')
			->where(["branchId" => $_POST["branchId"], "status" => 1])
			->orderBy("clientName")
			->asArray()
			->all();
		$jobTypes = JobType::find()
			->select('jobTypeId,jobTypeName')
			->where(["branchId" => $_POST["branchId"], "status" => 1])
			->orderBy("jobTypeName")
			->asArray()
			->all();
		/*$type = Employee::find()->select('t.typeId,t.typeName,employee.employeeNickName as nickName,employee.employeeId as emId')
				->JOIN("LEFT JOIN", "employee_type emt", "emt.employeeId=employee.employeeId")
				->JOIN("LEFT JOIN", "type t", "t.typeId=emt.typeId")
				->where(["t.typeName" => "PIC 1"])
				->orwhere(["t.typeName" => "PIC 2"])
				->andWhere(["employee.status" => Employee::STATUS_CURRENT, "employee.branchId" => $_POST["id"]])
				->asArray()
				->groupBy('emt.employeeId')
				->orderBy('nickName')
				->all();*/
		// } else {
		// 	$team = Team::find()
		// 		->select('teamId,teamName')
		// 		->where(["branchId" => $_POST["id"], "teamId" => $teamId, "status" => Team::STATUS_ACTIVE])
		// 		->orderBy("teamName")
		// 		->asArray()
		// 		->all();
		// 	$type = Employee::find()->select('t.typeId,t.typeName,employee.employeeNickName as nickName,employee.employeeId as emId')
		// 		->JOIN("LEFT JOIN", "employee_type emt", "emt.employeeId=employee.employeeId")
		// 		->JOIN("LEFT JOIN", "type t", "t.typeId=emt.typeId")
		// 		->where(["t.typeName" => "PIC 1"])
		// 		->orwhere(["t.typeName" => "PIC 2"])
		// 		->andWhere(["employee.status" => Employee::STATUS_CURRENT, "employee.branchId" => $_POST["id"]])
		// 		->andWhere(["employee.teamId" => $teamId])
		// 		->asArray()
		// 		->groupBy('emt.employeeId')
		// 		->orderBy('nickName')
		// 		->all();
		// }
		if (isset($team) && count($team) > 0) {
			foreach ($team as $t) :
				$textTeam .= '<option value="' . $t["teamId"] . '">' . $t["teamName"] . '</option>';
			endforeach;
		}
		if (isset($clients) && count($clients) > 0) {
			foreach ($clients as $client) :
				$textClient .= '<option value="' . $client["clientId"] . '">' . $client["clientName"] . '</option>';
			endforeach;
		}
		if (isset($jobTypes) && count($jobTypes) > 0) {
			foreach ($jobTypes as $jobType) :
				$textJobType .= '<option value="' . $jobType["jobTypeId"] . '">' . $jobType["jobTypeName"] . '</option>';
			endforeach;
		}


		// if (isset($type) && count($type) > 0) {
		// 	foreach ($type as $t) :
		// 		$textPerson .= '<option value="' . $t["emId"] . '">' . $t["nickName"] . '</option>';
		// 	endforeach;
		// }
		$res["status"] = true;
		$res["textTeam"] = $textTeam;
		$res["textClient"] = $textClient;
		$res["textJobType"] = $textJobType;
		// $res["person"] = $textPerson;

		return json_encode($res);
	}
	public function actionSearchFilterTeam()
	{
		$textPerson = '<option value="">Person</option>';
		$persons = Employee::find()
			->select('employeeId,employeeNickName')
			->where(["teamId" => $_POST["teamId"], "status" => Employee::STATUS_CURRENT])
			->orderBy("employeeNickName")
			->asArray()
			->all();
		if (isset($persons) && count($persons) > 0) {
			foreach ($persons as $person) :
				$textPerson .= '<option value="' . $person["employeeId"] . '">' . $person["employeeNickName"] . '</option>';
			endforeach;
		}
		$res["status"] = true;

		$res["textPerson"] = $textPerson;

		return json_encode($res);
	}
	public function actionPrepare()
	{
		$statusArr = [];
		$i = 0;
		if (isset($_POST["status"]) && count($_POST["status"]) > 0) {
			foreach ($_POST["status"] as $status) :
				if ($status != '') {
					$statusArr[$i] = $status;
					$i++;
				}
			endforeach;
		}
		return $this->redirect(Yii::$app->homeUrl . 'job/detail/export-job/' . ModelMaster::encodeParams([
			"branchId" => $_POST["branchId"],
			"categoryId" => $_POST["categoryId"],
			"fieldId" => $_POST["fieldId"],
			"postTeamId" => $_POST["teamId"],
			"status" => $statusArr,
			"personId" => $_POST["personId"],
			"clientId" => $_POST["clientId"],
			"groupFieldId" => $_POST["groupFieldId"],
			"jobTypeId" => $_POST["jobTypeId"],
			"sortStep" => $_POST["sortStep"],
			"sortFinal" => $_POST["sortFinal"],
			"needReport" => $_POST["needReport"]
		]));
	}
	public function actionExportJob($hash)
	{
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$categoryId = $param["categoryId"];
		$fieldId = $param["fieldId"];
		$postTeamId = $param["postTeamId"];
		$status = $param["status"];
		$personId = $param["personId"];
		$clientId = $param["clientId"];
		$groupFieldId = $param["groupFieldId"];
		$jobTypeId = $param["jobTypeId"];
		$sortStep = $param["sortStep"];
		$sortFinal = $param["sortFinal"];
		$needReport = $param["needReport"];
		$employeeType = EmployeeType::findEmployeeType();
		$employeeBranchId = Employee::employeeBranch();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		$fag = 0;
		//$status = '';
		$team = [];
		$teamId = Employee::employeeTeam();
		$client = [];
		$onlyManager = 0;
		$rightBranch = Type::TYPE_MANAGER;
		if ($sortStep == 0) {
			$sortStep = "c.clientName ASC";
		}
		if ($sortStep == 1) {
			$sortStep = "jcTargetDate DESC";
		}
		if ($sortStep == 2) {
			$sortStep = "jcTargetDate ASC";
		}
		if ($sortFinal == 0) {
			$sortFinal = "c.clientName ASC";
		}
		if ($sortFinal == 1) {
			$sortFinal = "jcTargetDate DESC";
		}
		if ($sortFinal == 2) {
			$sortFinal = "jcTargetDate ASC";
		}
		if (count($rightAll) > 0) {
			foreach ($employeeType as $all) :
				if (in_array($all, $rightAll)) {
					$fag = 1;
				}
			endforeach;
		}
		if (count($employeeType) > 0) {
			if (in_array($rightBranch, $employeeType)) {
				if (count($employeeType) == 1) {
					$onlyManager = 1;
				} else {
					$onlyManager = 0;
				}
			}
		}
		if ($sortStep == 0 && $sortFinal == 0) {
			$orderBy = "c.clientName ASC";
		}
		if ($sortFinal != 0) {
			$orderBy = $sortFinal;
		}
		if ($sortStep != 0) {
			$orderBy = $sortStep;
		}
		$jobId = null;
		//throw new exception(print_r($status, true));
		if (count($status) > 0) {
			$jobIds = Job::findJobIdByStatus2($status, $branchId);
			if ($jobIds != '') {
				$jobId = explode(',', $jobIds);
			}
		}
		//throw new exception($jobId);
		if ($jobId == '') {
			$jobId = null;
		}

		if ($fag == 1) { // GM SUPERVISOR MANAGER

			$query = Job::find()
				->select("job.*,MAX(jc.targetDate) as jcTargetDate,Max(jc.jobCategoryId) as jobCategoryId,js.jobCategoryId as jsJobCat,
				jc.jobId as jcJobId,js.jobId as jsJobId,MIN(js.status),
				CASE js.status WHEN 1 THEN MIN(js.jobStepId) WHEN 4 THEN MAX(js.jobStepId) ELSE 0 END AS jsJobStepId,
				")
				->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
				->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
				->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->where("job.status!=" . Job::STATUS_DELETED . " and s.status!=" . Step::STATUS_DISABLE)
				->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
				->andWhere(["jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE]])
				->andFilterWhere([
					"job.jobId" => $jobId,
					"jr.employeeId" => $personId,
					"job.branchId" => $branchId,
					"job.categoryId" => $categoryId,
					"job.fieldId" => $fieldId,
					"job.teamId" => $postTeamId,
					"job.clientId" => $clientId,
					"job.jobTypeId" => $jobTypeId,
					"job.report" => $needReport
				])
				->groupBy('jc.jobId,js.jobId')
				->orderBy($orderBy)
				->asArray()
				->all();
		} else { //NORMAL STAFF
			if ($onlyManager == 1) {
				$query = Job::find()
					->select("job.*,MAX(jc.targetDate) as jcTargetDate,Max(jc.jobCategoryId) as jobCategoryId,js.jobCategoryId as jsJobCat,
				jc.jobId as jcJobId,js.jobId as jsJobId,MIN(js.status),
				CASE js.status WHEN 1 THEN MIN(js.jobStepId) WHEN 4 THEN MAX(js.jobStepId) ELSE 0 END AS jsJobStepId,
				")
					->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
					->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
					->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where("job.status!=" . Job::STATUS_DELETED . " and s.status!=" . Step::STATUS_DISABLE)
					->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
					->andWhere(["jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE]])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.branchId" => $employeeBranchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.teamId" => $postTeamId,
						"job.clientId" => $clientId,
						"job.jobTypeId" => $jobTypeId,
						"job.report" => $needReport
					])
					->groupBy('jc.jobId,js.jobId')
					->orderBy($orderBy)
					->asArray()
					->all();
			} else {
				$query = Job::find()
					->select("job.*,MAX(jc.targetDate) as jcTargetDate,Max(jc.jobCategoryId) as jobCategoryId,js.jobCategoryId as jsJobCat,
				jc.jobId as jcJobId,js.jobId as jsJobId,MIN(js.status),
				CASE js.status WHEN 1 THEN MIN(js.jobStepId) WHEN 4 THEN MAX(js.jobStepId) ELSE 0 END AS jsJobStepId,
				")
					->JOIN("LEFT JOIN", "job_category jc", "job.jobId=jc.jobId")
					->JOIN("LEFT JOIN", "job_step js", "jc.jobCategoryId=js.jobCategoryId")
					->JOIN("LEFT JOIN", "step s", "s.stepId=js.stepId")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where("job.status!=" . Job::STATUS_DELETED . " and s.status!=" . Step::STATUS_DISABLE)
					->andWhere(["js.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]])
					->andWhere(["jc.status" => [JobCategory::STATUS_INPROCESS, JobCategory::STATUS_COMPLETE]])
					->andFilterWhere([
						"job.jobId" => $jobId,
						"jr.employeeId" => $personId,
						"job.teamId" => $postTeamId,
						"job.branchId" => $branchId,
						"job.categoryId" => $categoryId,
						"job.fieldId" => $fieldId,
						"job.clientId" => $clientId,
						"job.jobTypeId" => $jobTypeId,
						"job.report" => $needReport
					])
					->groupBy('jc.jobId,js.jobId')
					->orderBy($orderBy)
					->asArray()
					->all();
			}
		}

		$textExcel = $this->renderPartial('export_job', [
			"query" => $query,
			"branchId" => $branchId,
			"categoryId" => $categoryId,
			"personId" => $personId,
			"postTeamId" => $postTeamId,
			"postStatus" => $status,
			"client" => $client,
			"groupFieldId" => $groupFieldId,
			"clientId" => $clientId,
			"postJobTypeId" => $jobTypeId,
		]);
		$spreadsheet = new Spreadsheet;
		$reader = new Html();
		$spreadsheet = $reader->loadFromString($textExcel);
		$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

		$spreadsheet->getActiveSheet()
			->getStyle('A2:K2')
			->getBorders()
			->getAllBorders()
			->setBorderStyle(Border::BORDER_THIN);
		$spreadsheet->getActiveSheet()
			->getStyle('A2:K2')
			->getFill()
			->setFillType(Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('b2ebf2');

		//for ($i = 'B'; $i !=  'J'; $i++) {
		//$spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(false);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(50);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('k')->setWidth(20);
		$spreadsheet->getActiveSheet()->getRowDimension("2")->setRowHeight(30);
		//}

		$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

		for ($row = 3; $row <= $highestRow; $row++) {
			$spreadsheet->getActiveSheet()->getStyle("B$row")->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getRowDimension("$row")->setRowHeight(50);
		}
		$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
		for ($row = 3; $row <= $highestRow; $row++) {
			$spreadsheet->getActiveSheet()->getStyle("C$row")->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getRowDimension("$row")->setRowHeight(50);
		}
		for ($row = 3; $row <= $highestRow; $row++) {
			$spreadsheet->getActiveSheet()->getStyle("D$row")->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getRowDimension("$row")->setRowHeight(50);
		}
		for ($row = 3; $row <= $highestRow; $row++) {
			$spreadsheet->getActiveSheet()->getStyle("E$row")->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getRowDimension("$row")->setRowHeight(50);
		}
		for ($i = 3; $i <= $highestRow; $i++) {
			$spreadsheet->getActiveSheet()->getRowDimension("$i")->setRowHeight(-1);
		}
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$folderName = "export";
		$filename = Yii::$app->user->id . Yii::$app->security->generateRandomString(10) . '.xlsx';
		$urlFolder = Path::getHost() . 'file/' . $folderName . "/" . $filename;
		$folder_path = Path::getHost() . 'file/' . $folderName;
		$files = glob($folder_path . '/*');
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
		$date = date('Y-m-d');
		$writer->save($urlFolder);
		$fileNameDownLoad = 'JobExport' . $date . '.xlsx';
		$res["fileName"] = $fileNameDownLoad;
		$res["status"] = true;
		//return json_encode($res);
		return Yii::$app->response->sendFile($urlFolder, 'JobExport' . $date . '.xlsx');
	}
	public function actionJobDetail($hash)
	{
		// $right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$params = ModelMaster::decodeParams($hash);
		if (isset($params["previousUrl"])) {
			$previousUrl = $params["previousUrl"];
		} else {
			$previousUrl = Yii::$app->request->referrer;
		}
		$jobId = $params["jobId"];
		$jobStep = [];
		$jobCate = [];
		$response = [];
		$currentEmail = [];
		$totalStepComplete = 0;
		$hasMore = 0;
		$checkListFile = [];
		$fields = [];
		$defaultGroup = '';
		$groupFields = [];
		$submitDate = '';
		$isSubmitReport = SubmitReport::isSubmitReport($jobId);
		if ($isSubmitReport == 1) {
			$submitDate = SubmitReport::subDate($jobId);
		}
		JobCategory::deleteAll(["jobId" => $jobId, "status" => JobCategory::STATUS_WAITPROCESS]);
		$job = Job::find()
			->select('job.jobId,job.jobName,job.checkListPath,job.memo,job.branchId,job.clientId,job.teamId,job.fieldId,job.report,
			job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,job.p1Time,job.p2Time,
			job.fee,job.advanceReceivable,job.outSourcingFee,job.advancedChargeDate,job.feeChargeDate,
			job.estimateTime,job.startDate,job.url,job.currencyId,cu.name as currencyName,cu.code,cu.symbol,job.relatedJob')
			->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
			->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
			->JOIN("LEFT JOIN", "currency cu", 'cu.currencyId=job.currencyId')
			->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
			->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
			->where(["job.jobId" => $jobId,])
			->andWhere("job.status!=" . Job::STATUS_DELETED)
			->asArray()
			->one();
		$defaultGroup = SubFieldGroup::findSubFileGroup($job["fieldId"]);
		$groups = SubFieldGroup::find()
			->select('sub_field_group.subFieldGroupId,sub_field_group.subFieldGroupName,fg.fieldGroupId,fg.fieldGroupName')
			->JOIN("LEFT JOIN", "field_group fg", "fg.fieldGroupId=sub_field_group.fieldGroupId")
			->where(["sub_field_group.status" => 1, "fg.status" => 1])
			->orderBy('fg.fieldGroupId,sub_field_group.subFieldGroupId')
			->asArray()
			->all();
		$jobTypes = JobType::find()
			->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $job["branchId"]])
			->orderBy('jobTypeName')
			->asArray()
			->all();
		if (isset($groups) && count($groups) > 0) {
			foreach ($groups as $group) :
				$groupFields[$group["fieldGroupId"]][$group["subFieldGroupId"]] = [
					"name" => $group["subFieldGroupName"]
				];
			endforeach;
		}
		$fields = Field::find()->select('fieldId,fieldName')->where(["subFieldGroupId" => $defaultGroup])->asArray()->all();
		if (isset($job) && !empty($job)) {
			//$defaultGroup = FieldGroup::findGroup($job["fieldId"]);
			if ($job["status"] == Job::STATUS_COMPLETE) {
				$category = JobCategory::find()
					->select('job_category.jobCategoryId,job_category.fiscalYear,job_category.categoryId as jcategoryId,
					c.categoryName,job_category.startMonth,job_category.targetDate,job_category.status as jcStatus,
					c.totalRound,job_category.firstTargetDate')
					->JOIN("LEFT JOIN", "category c", "c.categoryId=job_category.categoryId")
					->where(["job_category.jobId" => $jobId])
					->orderBy('job_category.jobCategoryId DESC')
					->asArray()
					->one();
			} else {
				$category = JobCategory::find()
					->select('job_category.jobCategoryId,job_category.fiscalYear,job_category.categoryId as jcategoryId,
					c.categoryName,job_category.startMonth,job_category.targetDate,job_category.status as jcStatus,
					c.totalRound,job_category.firstTargetDate')
					->JOIN("LEFT JOIN", "category c", "c.categoryId=job_category.categoryId")
					->where(["job_category.jobId" => $jobId, "job_category.status" => JobCategory::STATUS_INPROCESS])
					->orderBy('job_category.targetDate')
					->asArray()
					->one();
			}
			$checkCategory = JobCategory::find()->select('jobCategoryId')
				->where(["jobId" => $jobId, "status" => JobCategory::STATUS_WAITPROCESS])
				->asArray()
				->one();
			if (isset($checkCategory) && !empty($checkCategory)) {
				$hasMore = 1;
			}
			//throw new exception(print_r($category, true));
			if (isset($category) && !empty($category) > 0) {
				$jobCate = [
					"jCategoryId" => $category["jcategoryId"],
					"categoryName" => $category["categoryName"],
					"startMonth" => $category["startMonth"],
					"startMonthText" => ModelMaster::shotMonthText($category["startMonth"]),
					"targetDate" => $category["targetDate"],
					"firstTargetDate" => $category["firstTargetDate"],
					"status" => $category["jcStatus"],
					"jobCateId" => $category["jobCategoryId"],
					"totalRound" => $category["totalRound"],
					"fiscalYear" => $category["fiscalYear"],
				];
				$isSetStep = JobCategory::clearEmtyJobCategoryStep($category["jobCategoryId"]);
				if ($isSetStep == 0) {
					return $this->redirect(['detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $jobId])]);
				}
				if ($category["jcStatus"] != JobCategory::STATUS_COMPLETE) {
					$jobStepCheck = JobStep::find()->where(["jobCategoryId" => $category["jobCategoryId"]])->all();
					if (isset($jobStepCheck) && count($jobStepCheck) > 0) { //ลบที่ไม่มีใน master step
						foreach ($jobStepCheck as $jt) :
							$step = Step::find()->where(["jobTypeId" => $job["jobTypeId"], "stepId" => $jt->stepId])->one();
							if (!isset($step) || empty($step)) {
								$jt->delete();
							}
						endforeach;
					}
					$steps = Step::find()->where(["jobTypeId" => $job["jobTypeId"], "status" => 1])->all();
					if (isset($steps) && count($steps) > 0) { //เพิ่มที่ไม่มีใน master step
						foreach ($steps as $step) :
							$jobStepCheck2 = JobStep::find()
								->where([
									"jobCategoryId" => $category["jobCategoryId"],
									"stepId" => $step->stepId
								])
								->one();
							if (!isset($jobStepCheck2) || empty($jobStepCheck2) > 0) {
								$addStep = new JobStep();
								$addStep->jobId = $job["jobId"];
								$addStep->jobCategoryId = $category["jobCategoryId"];
								$addStep->stepId = $step->stepId;
								$addStep->dueDate = null;
								$addStep->status = 1;
								$addStep->createDateTime = new Expression('NOW()');
								$addStep->updateDateTime = new expression('NOW()');
								$addStep->save(false);
							}
						endforeach;
					}
				}
			}

			$jobSteps = JobStep::find()
				->select('job_step.jobStepId,job_step.stepId as jStepId,s.stepName,
				job_step.dueDate as dueDate,job_step.status as jsStatus,job_step.jobCategoryId,
				job_step.remark,job_step.firstDueDate')
				->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
				->where(["job_step.jobId" => $jobId, "s.jobTypeId" => $job["jobTypeId"], "job_step.jobCategoryId" => $jobCate["jobCateId"]])
				->andWhere("s.status!=99")
				->orderBy('s.sort ASC,duedate ASC')
				->asArray()
				->all();
			// throw new exception(print_r($jobSteps, true));
			if (isset($jobSteps) && count($jobSteps) > 0) { //depend on jobType
				foreach ($jobSteps as $step) :
					$jobStep[$step["jobStepId"]] = [
						"stepId" => $step["jStepId"],
						"stepName" => $step["stepName"],
						"firstDueDate" => $step["firstDueDate"],
						"dueDate" => $step["dueDate"],
						"status" => $step["jsStatus"],
						"isCancel" => LogCancel::isCancel($step["jobStepId"]),
						"history" => LogJobStep::hasLog($step["jobStepId"]),
						// "comment" => $step["remark"] != null ? 1 : 0,
						"additionalStep" => AdditionalStep::AdditionalJobStep($jobId, $step["jStepId"], $step["jobCategoryId"])
					];
					if ($step["jsStatus"] == JobStep::STATUS_COMPLETE) {
						$totalStepComplete++;
					}
				endforeach;
			}
			$canCancel = AdditionalStep::canCancelComplete($jobCate["jobCateId"]);
			$jobResponsibility = JobResponsibility::find()
				->select('job_responsibility.employeeId,job_responsibility.percentage,job_responsibility.responsibility,job_responsibility.id as resId,job_responsibility.status,em.employeeId,em.employeeNickName')
				->JOIN("LEFT JOIN", "employee em", "job_responsibility.employeeId=em.employeeId")
				->where(["job_responsibility.jobId" => $jobId])
				->andWhere("job_responsibility.responsibility=" . JobResponsibility::PIC1 . " or job_responsibility.responsibility=" . JobResponsibility::PIC2)
				->asArray()
				->all();

			if (isset($jobResponsibility) && count($jobResponsibility) > 0) {
				foreach ($jobResponsibility as $responsibility) :
					$typeName = "";
					if ($responsibility["responsibility"] == JobResponsibility::PIC1) {
						$typeName = "PIC 1";
					}
					if ($responsibility["responsibility"] == JobResponsibility::PIC2) {
						$typeName = "PIC 2";
					}
					$response[$responsibility["resId"]] = [
						"resId" => $responsibility["resId"],
						"resType" => $responsibility["responsibility"],
						"resName" => $typeName,
						"percent" => $responsibility["percentage"],
						"status" => $responsibility["status"],
						"employeeId" => $responsibility["employeeId"],
						"nickName" => $responsibility["employeeNickName"],
					];

				endforeach;
			}
			//throw new Exception(print_r($response, true));
			$jobAlert = JobAlert::find()
				->select('job_alert.userId')
				->JOIN("LEFT JOIN", "employee em", "em.employeeId=job_alert.userId")
				->where(["jobId" => $jobId])
				->asArray()
				->all();
			if (isset($jobAlert) && count($jobAlert) > 0) {
				foreach ($jobAlert as $jb) :
					$currentEmail[$jb["userId"]] = $jb["userId"];
				endforeach;
			}
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
			if ($fag == 1) {

				$team = Team::find()->select('teamId,branchId,teamName')
					->where(["branchId" => $job["branchId"], "status" => Team::STATUS_ACTIVE])
					->all();
				$approver = Employee::find()->select('employeeId,employeeFirstName as firstName,employeeLastName as lastName,employeeNickName as nickName')
					->where(["status" => Employee::STATUS_CURRENT, "teamPositionId" => TeamPosition::LEADER, "branchId" => $job["branchId"]])
					->orderBy('firstname')
					->asArray()
					->all();
				$pic = Employee::find()->select('employee.employeeId as employeeId,employee.employeeFirstName as firstName,employee.employeeLastName as lastName,employee.employeeNickName as nickName')
					->where(["employee.branchId" => $job["branchId"], "status" => Employee::STATUS_CURRENT])
					->asArray()
					->all();
			} else {
				$team = Team::find()->select('teamId,branchId,teamName')
					->where(["branchId" => $job["branchId"], "status" => Team::STATUS_ACTIVE, "teamId" => $job["teamId"]])
					->all();
				$approver = Employee::find()->select('employeeId,employeeFirstName as firstName,employeeLastName as lastName,employeeNickName as nickName')
					->where(["status" => Employee::STATUS_CURRENT, "teamPositionId" => TeamPosition::LEADER, "branchId" => $job["branchId"], "teamId" => $job["teamId"]])
					->orderBy('firstname')
					->asArray()
					->all();
				$pic = Employee::find()->select('employee.employeeId as employeeId,employee.employeeFirstName as firstName,employee.employeeLastName as lastName,employee.employeeNickName as nickName')
					->where(["employee.branchId" => $job["branchId"], "teamId" => $job["teamId"], "status" => Employee::STATUS_CURRENT])
					->asArray()
					->all();
			}

			$jobApprover = JobResponsibility::find()
				->select('em.employeeId,em.employeeFirstName as firstName,em.employeeLastName as lastName,em.employeeNickName as nickName')
				->JOIN("LEFT JOIN", "employee em", "em.employeeId=job_responsibility.employeeId")
				->where(["job_responsibility.responsibility" => JobResponsibility::APPROVER, "job_responsibility.jobId" => $jobId])
				->asArray()
				->one();


			$employyeeEmail = Employee::find()->select('employee.employeeId as employeeId,employee.email as email,employee.employeeFirstName as firstName,employee.employeeLastName as lastName,employee.employeeNickName as nickName')
				->JOIN("LEFT JOIN", "employee_type et", "et.employeeId=employee.employeeId")
				->where("et.employeeId is not null")
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
			$textComplain = "";

			$allComplain = JobComplain::find()
				->where(["jobId" => $jobId])
				->asArray()
				->orderBy('createDateTime')
				->all();
			if (isset($allComplain) && count($allComplain) > 0) {
				foreach ($allComplain as $complain) :
					$textComplain .= "- " . ModelMaster::engDate($complain["createDateTime"], 2) . ': ' . $complain["complain"] . '<br>';
				endforeach;
			}
			$category = Category::find()->select('categoryName,categoryId')
				->where(["status" => Category::STATUS_ACTIVE])
				->asArray()
				->orderBy('categoryName')
				->all();
			$relateJob = $this->findJobByUrl($job["relatedJob"]);
			return $this->render('job_detail', [
				"job" => $job,
				"jobStep" => $jobStep,
				"jobCate" => $jobCate,
				"response" => $response,
				"email" => $email,
				"team" => $team,
				"pic" => $pic,
				"currentEmail" => $currentEmail,
				"textComplain" => $textComplain,
				"approver" => $approver,
				"jobApprover" => $jobApprover,
				"totalStepComplete" => $totalStepComplete,
				"hasMore" => $hasMore,
				"currency" => $currency,
				"defaultGroup" => $defaultGroup,
				"groupFields" => $groupFields,
				"fields" => $fields,
				"category" => $category,
				"jobTypes" => $jobTypes,
				"previousUrl" => $previousUrl,
				"canCancel" => $canCancel,
				"isSubmitReport" => $isSubmitReport,
				"submitDate" => $submitDate,
				"relateJob" => $relateJob
			]);
		} else {
			return $this->render('job_deleted');
		}
	}
	public function actionChangeJobType()
	{
		$jobId = $_POST["jobId"];
		$jobCategoryId = $_POST["jobCategoryId"];
		$jobTypeId = $_POST["jobTypeId"];
		//$jobStep=JobStep::find()->where()->all();
		JobStep::updateAll(["status" => 99], ["jobCategoryId" => $jobCategoryId]);
		$steps = Step::find()
			->where(["jobTypeId" => $jobTypeId, "status" => Step::STATUS_ACTIVE])
			->orderBy('sort')
			->all();
		if (isset($steps) && count($steps) > 0) {
			foreach ($steps as $step) :
				$jobStep = new JobStep();
				$jobStep->jobId = $jobId;
				$jobStep->jobCategoryId = $jobCategoryId;
				$jobStep->stepId = $step->stepId;
				$jobStep->dueDate = null;
				$jobStep->completeDate = null;
				$jobStep->status = JobStep::STATUS_INPROCESS;
				$jobStep->createDateTime = new Expression('NOW()');
				$jobStep->updateDateTime = new Expression('NOW()');
				$jobStep->save(false);
			endforeach;
		}
		Job::updateAll(["jobTypeId" => $jobTypeId], ["jobId" => $jobId]);
		$res["status"] = true;
		return json_encode($res);
	}

	public function actionCompleteJob($hash)
	{
		$right = "all";
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$params = ModelMaster::decodeParams($hash);
		$jobId = $params["jobId"];
		$alljobs = [];
		$jobCate = [];
		$response = [];
		$currentEmail = [];
		$totalStepComplete = 0;
		$hasMore = 0;
		$job = Job::find()
			->select('job.*,c.clientName,t.teamName,b.branchName,jt.jobTypeName,ca.categoryName,f.fieldName')
			->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
			->JOIN("LEFT JOIN", "team t", 't.teamId=job.teamId')
			->JOIN("LEFT JOIN", "branch b", 'b.branchId=job.branchId')
			->JOIN("LEFT JOIN", "field f", 'f.fieldId=job.fieldId')
			->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
			->JOIN("LEFT JOIN", "category ca", "ca.categoryId=job.categoryId")
			->where(["jobId" => $jobId])
			->asArray()
			->one();
		$jobs = JobStep::find()->select('s.stepName,jc.targetDate,jc.completeDate as tCompleteDate,
		job_step.dueDate,job_step.stepId,jc.jobCategoryId,job_step.status,job_step.completeDate,
		job_step.jobStepId,jc.startMonth,jc.fiscalYear,job_step.remark,job_step.firstDueDate')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobCategoryId=job_step.jobCategoryId")
			->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
			->where(["job_step.jobId" => $jobId])
			->andWhere("s.status!=99")
			->asArray()
			->orderBy('job_step.jobCategoryId DESC,s.sort')
			->all();
		$complain = JobComplain::find()
			->select('complain,createDateTime')
			->where(["jobId" => $jobId])
			->orderBy('createDateTime')
			->all();
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $j) :
				$monthName = '';
				if ($j["startMonth"] != null) {
					$monthName = date('M', mktime(0, 0, 0, $j["startMonth"], 10));
				}
				$alljobs[$j["jobCategoryId"]]["startMonth"] = $monthName;
				$alljobs[$j["jobCategoryId"]]["targetDate"] = ModelMaster::engDate($j["targetDate"], 2);
				$alljobs[$j["jobCategoryId"]]["fiscalYear"] = $j["fiscalYear"];
				$alljobs[$j["jobCategoryId"]]["startMonthInt"] = $j["startMonth"];
				$alljobs[$j["jobCategoryId"]]["tCompleteDate"] = $j["tCompleteDate"] != null ? ModelMaster::engDate($j["tCompleteDate"], 2) : '';
				$alljobs[$j["jobCategoryId"]][$j["stepId"]] = [
					"stepName" => $j["stepName"],
					"dueDate" => $j["dueDate"] != null ? ModelMaster::engDate($j["dueDate"], 2) : null,
					"firstDueDate" => $j["firstDueDate"] != null ? ModelMaster::engDate($j["firstDueDate"], 2) : null,
					"status" => JobStep::statusText($j["status"]),
					"completeDate" => $j["completeDate"] != null ? ModelMaster::engDate($j["completeDate"], 2) : '',
					"history" => LogJobStep::hasLog($j["jobStepId"]),
					"jobStepId" => $j["jobStepId"],
					"additionalStep" => AdditionalStep::AdditionalJobStep($jobId, $j["stepId"], $j["jobCategoryId"]),
					"comment" => $j["remark"] != null ? 1 : 0,
					"adjustComplete" => AdjustDuedate::hasAddjust($j["jobStepId"])


				];
			endforeach;
		}
		return $this->render('complete_job', [
			"job" => $job,
			"alljobs" => $alljobs,
			"complain" => $complain
		]);
	}
	public function actionUpdateJob()
	{
		if (isset($_POST["jId"])) {
			//throw new Exception($_POST["category"]);
			$jobId = $_POST["jId"];
			$job = Job::find()->where(["jobId" => $jobId])->one();
			$completeStep = isset($_POST["complete"]) ? $_POST["complete"] : null;
			$completeTarget = isset($_POST["completeTarget"]) ? $_POST["completeTarget"] : null;
			$currentJobStepId = isset($_POST["currrentStep"]) ? $_POST["currrentStep"] : null;
			$email = isset($_POST["email"]) ? $_POST["email"] : null;
			$dueDate = isset($_POST["dueDate"]) ? $_POST["dueDate"] : null;
			$subStepName = isset($_POST["additionalStep"]) ? $_POST["additionalStep"] : [];
			$subDueDate = isset($_POST["subDueDate"]) ? $_POST["subDueDate"] : [];
			$completeSubStep = isset($_POST["subComplete"]) ? $_POST["subComplete"] : [];
			$newSubName = isset($_POST["moreStep"]) ? $_POST["moreStep"] : [];
			$newDueDate = isset($_POST["subStepDueDate"]) ? $_POST["subStepDueDate"] : [];
			$newStartMonth = $_POST["currentMonth"];
			$fiscalYear = $_POST["fiscalYear"];
			$this->saveMoreSubStep($newSubName, $newDueDate, $jobId);
			$this->updateJobStep($jobId, $dueDate, $completeStep, $_POST["jcId"]);
			$this->updateFirstJobStepDueDate($dueDate, $_POST["jcId"]);
			$this->updateJobSubStep($subDueDate, $completeSubStep, $subStepName);
			$this->updateJobCategory($jobId, $_POST["targetDate"], $_POST["jcId"], $completeTarget, $newStartMonth, $fiscalYear);
			$this->updateFirstJobCategoryTargetDate($_POST["targetDate"], $_POST["jcId"]);
			$this->updateAlertEmail($jobId, $email);
			if (isset($_POST["category"]) && $_POST["category"] != null) {
				$job->categoryId = $_POST["category"];
				JobCategory::updateAll(["categoryId" => $_POST["category"]], ["jobId" => $jobId]);
				if (isset($_POST["startMonth"])) {
					$jobCategory = JobCategory::find()->where(["jobCategoryId" => $_POST["jcId"]])->one();
					$jobCategory->startMonth = isset($_POST["startMonth"]) && $_POST["startMonth"] != '' ?  ModelMaster::shotMonthValue($_POST["startMonth"]) : null;
					$jobCategory->save(false);
				}
			}
			if ($job->teamId != $_POST["team"]) {
				$oldTeamId = $job->teamId;
				$this->saveOldTeam($job->jobId, $oldTeamId);
				$job->teamId = $_POST["team"];
			}
			if (isset($_POST["pIc1"]) && count($_POST["pIc1"]) > 0) {
				$this->updatePIC1($jobId, $_POST["pIc1"], $_POST["percentagePic1"]);
			}
			if (isset($_POST["pIc2"]) && count($_POST["pIc2"]) > 0) {
				$this->updatePIC2($jobId, $_POST["pIc2"], $_POST["percentagePic2"]);
			}
			if (isset($_POST["p1Time"]) && trim($_POST["p1Time"]) != '') {
				$job->p1Time = $_POST["p1Time"];
			}
			if (isset($_POST["p2Time"]) && trim($_POST["p2Time"]) != '') {
				$job->p2Time = $_POST["p2Time"];
			}
			if (isset($_POST["approver"])) {
				$this->updateApprover($_POST["approver"], $jobId, $_POST["jcId"], $currentJobStepId);
			}
			$job->fieldId = $_POST["field"];
			$job->report = isset($_POST["report"]) ? 1 : 0;
			//if (isset($_POST["fee"])) {
			$this->updateFee($_POST["fee"], $_POST["feeChargeDate"], $_POST["advanceRec"],  $_POST["advancedChargeDate"], $_POST["outsourcingFee"], $_POST["estimate"], $currentJobStepId, $jobId, $_POST["jcId"]);
			//}
			if ($_POST["hasMore"] == 0 && $completeTarget != null) {
				$job->status = Job::STATUS_COMPLETE;
			} else {
				$job->status = Job::STATUS_INPROCESS;
			}
			$fileObj = UploadedFile::getInstanceByName("checkList");
			if (isset($fileObj) && !empty($fileObj)) {
				$path = Path::getHost() . 'file/checkList/';
				if (!file_exists($path)) {
					mkdir($path, 0777, true);
				}
				$file = $fileObj->name;
				$filenameArray = explode('.', $file);
				$countArrayFile = count($filenameArray);
				$fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[$countArrayFile - 1];
				$pathSave = $path . $fileName;
				$fileObj->saveAs($pathSave);
				if ($job->checkListPath != null) {
					unlink(Path::urlUpload() . $job->checkListPath);
				}
				$job->checkListPath = 'file/checkList/' . $fileName;
			}
			$job->fee = $_POST["fee"];
			$job->fieldId = $_POST["field"];
			$job->memo = $_POST["memo"];
			$job->url = $_POST["url"];
			$job->currencyId = $_POST["currency"];
			$job->feeChargeDate = $_POST["feeChargeDate"];
			$job->advanceReceivable = $_POST["advanceRec"];
			$job->advancedChargeDate = $_POST["advancedChargeDate"];
			$job->outsourcingFee = $_POST["outsourcingFee"];
			$job->estimateTime = $_POST["estimate"];
			$job->startDate = $_POST["trueDate"];
			if (isset($_POST["relatedJob"]) && $_POST["relatedJob"] != '') {
				$job->relatedJob = $_POST["relatedJob"];
			}

			$job->save(false);
			$this->sendJobEmail($jobId);
		}
		if (isset($_POST["pUrl"])) {
			return $this->redirect($_POST["pUrl"]);
		} else {
			return $this->redirect('index');
		}
	}
	public function updateJobStep($jobId, $dueDate, $completeStep, $jocategoId)
	{
		$jobStep = JobStep::find()->where(["jobId" => $jobId, "jobCategoryId" => $jocategoId])->all();
		$save = 0;
		if (isset($jobStep) && count($jobStep) > 0) {
			foreach ($jobStep as $step) :
				if (isset($dueDate[$step->jobStepId]) && $dueDate != null) {
					if (strtotime($step->dueDate) != strtotime($dueDate[$step->jobStepId])) {
						//save olds due step(jobStepId,old duedate , newDueDate)
						if ($step->dueDate != null) {
							LogJobStep::saveChangeStepDueDate($jobId, $step->jobStepId, $step->dueDate, $dueDate[$step->jobStepId]);
						}
						$step->dueDate = $dueDate[$step->jobStepId];
						$save = 1;
					}
				}
				if (isset($completeStep) && count($completeStep) > 0) {
					foreach ($completeStep as $complete) :
						if ($complete == $step->jobStepId) {
							$step->status = JobStep::STATUS_COMPLETE;
							$step->completeDate = new Expression('NOW()');
							$save = 1;
						}
					endforeach;
				}

				if ($save == 1) {
					$step->save(false);
				}
			endforeach;
		}
	}
	public function updateJobSubStep($subDueDate, $completeSubStep, $subStepName)
	{
		if (count($completeSubStep) > 0) {
			foreach ($completeSubStep as $additionalStepId => $add) {
				$addition = AdditionalStep::find()->where(["additionalStepId" => $additionalStepId])->one();
				$addition->status = JobStep::STATUS_COMPLETE;
				$addition->completeDate = new Expression('NOW()');
				$addition->save(false);
			}
		}
		if (count($subDueDate) > 0) {
			foreach ($subDueDate as $additionalStepId => $add) {
				$addition = AdditionalStep::find()->where(["additionalStepId" => $additionalStepId])->one();
				if (isset($subStepName[$additionalStepId])) {
					if ($subStepName[$additionalStepId] != $addition->additionalStepName) {
						$addition->additionalStepName = $subStepName[$additionalStepId];
						AdditionalStep::updateAll(["additionalStepName" => $subStepName[$additionalStepId]], [
							"jobId" => $addition->jobId,
							"stepId" => $addition->stepId,
							"sort" => $addition->sort
						]);
					}
				}
				$oldDueDate = $addition->dueDate;
				$newDueDate = $subDueDate[$additionalStepId] . ' 00:00:00';
				if ($oldDueDate != $newDueDate && $subDueDate[$additionalStepId] != null) {
					$addition->dueDate = $newDueDate;
					if ($addition->firstdueDate == null) {
						$addition->firstdueDate = $newDueDate;
					}
					LogSubStep::saveChangeSubDueDate($additionalStepId, $oldDueDate, $newDueDate);
				}
				$addition->save(false);
			}
		}
	}
	public function updateFirstJobStepDueDate($dueDate, $jocategoId)
	{
		$jobStep = JobStep::find()->where(["jobCategoryId" => $jocategoId])->all();
		$save = 0;
		if (isset($jobStep) && count($jobStep) > 0) {
			foreach ($jobStep as $step) :
				if (isset($dueDate[$step->jobStepId]) && $dueDate[$step->jobStepId] != null) {
					if ($step->firstDueDate == null) {
						$step->firstDueDate = $dueDate[$step->jobStepId];
						$step->save(false);
					}
				}
			endforeach;
		}
	}
	public function saveMoreSubStep($newSubName, $newDueDate, $jobId)
	{
		$jobCategory = JobCategory::find()->select('jobCategoryId')
			->where(["jobId" => $jobId, "status" => JobCategory::STATUS_INPROCESS])
			->asArray()
			->one();
		if (isset($jobCategory) && !empty($jobCategory)) {
			if (count($newSubName) > 0) {
				foreach ($newSubName as $stepId => $moreStep) :
					foreach ($moreStep as $sort => $name) :
						if ($name != null && trim($name) != '') {
							if (isset($newDueDate[$stepId][$sort]) && trim($newDueDate[$stepId][$sort]) != "") {
								$dueDate = $newDueDate[$stepId][$sort];
							} else {
								$dueDate = null;
							}
							$additional = new AdditionalStep();
							$additional->jobId = $jobId;
							$additional->stepId = $stepId;
							$additional->jobCategoryId = $jobCategory["jobCategoryId"];
							$additional->additionalStepName = $name;
							$additional->sort = $sort;
							$additional->dueDate = $dueDate;
							$additional->firstDueDate = $dueDate;
							$additional->status = 1;
							$additional->createDateTime = new Expression('NOW()');
							$additional->updateDateTime = new Expression('NOW()');
							$additional->save(false);
						}
					endforeach;
				endforeach;
			}
		}
	}
	public function actionDeleteAdditionalStep()
	{
		AdditionalStep::updateAll(["status" => 99], ["additionalStepId" => $_POST["additionalId"]]);
		$res["status"] = true;
		return json_encode($res);
	}
	public function updateJobCategory($jobId, $targetDate, $jobCateId, $completeTarget, $newStartMonth, $fiscalYear)
	{
		$save = 0;
		$jobCategory = JobCategory::find()->where(["jobCategoryId" => $jobCateId, "jobId" => $jobId])->one();
		if (isset($jobCategory) && !empty($jobCategory)) {
			if ($jobCategory->targetDate != $targetDate) {
				LogJobCategory::saveChangeCategoryTargetDate($jobId, $jobCateId, $jobCategory->targetDate, $targetDate);
				$jobCategory->targetDate = $targetDate;
				$save = 1;
				//$jobCategory->save(false);
			}
			if ($completeTarget != null) {
				$jobCategory->status = JobCategory::STATUS_COMPLETE;
				$jobCategory->completeDate = new Expression('NOW()');
				$save = 1;
			} else {
				$jobCategory->status = JobCategory::STATUS_INPROCESS;
				$jobCategory->completeDate = Null;
			}
			if ($newStartMonth != null) {
				$jobCategory->startMonth = ModelMaster::shotMonthValue($newStartMonth);
				$save = 1;
			} else {
				if ($jobCategory->startMonth == null && $targetDate != null) {
					$dateArr = explode('-', $targetDate);
					$jobCategory->startMonth = (int)$dateArr[1];
					$save = 1;
				}
			}
			if ($save == 1) {
				$jobCategory->fiscalYear = $fiscalYear;
				$jobCategory->save(false);
			}
		}
	}
	public function updateFirstJobCategoryTargetDate($firstTargetDate, $jobCategoryId)
	{
		$jobCategory = JobCategory::find()->where(["jobCategoryId" => $jobCategoryId])->one();
		if (isset($jobCategory) && !empty($jobCategory) && $firstTargetDate != null) {
			if ($jobCategory->firstTargetDate == null) {
				$jobCategory->firstTargetDate = $firstTargetDate;
				$jobCategory->save(false);
			}
		}
	}

	public function updateApprover($approverId, $jobId, $jobCategoryId, $jobStepId)
	{
		//$type = Type::find()->select('typeId')->where(["typeName" => 'Approver'])->asArray()->one();
		//if (isset($type) && !empty($type)) {
		$typeId = JobResponsibility::APPROVER;
		$res = JobResponsibility::find()->where(["jobId" => $jobId, "responsibility" => $typeId])->one();
		if (isset($res) && $approverId != $res->employeeId) {
			JobResponsibility::saveLogApprover($jobId, $approverId, $jobCategoryId, $jobStepId);
			$res->employeeId = $approverId;
			$res->save(false);
		}
		if (!isset($res) || empty($res)) {
			$res = new JobResponsibility();
			$res->employeeId = $approverId;
			$res->jobId = $jobId;
			$res->responsibility = JobResponsibility::APPROVER;
			$res->status = 1;
			$res->save(false);
		}
		//}
	}
	public function updateAlertEmail($jobId, $email)
	{
		JobAlert::deleteAll(["jobId" => $jobId]);
		if ($email != null && count($email) > 0) {
			foreach ($email as $userId) :
				$alert = new JobAlert();
				$alert->jobId = $jobId;
				$alert->userId = $userId;
				$alert->createDateTime = new Expression('NOW()');
				$alert->updateDateTime = new Expression('NOW()');
				$alert->status = 1;
				$alert->save(false);
			endforeach;
		}
		//$jobAlert = JobAlert::find()->where(["jobId" => $jobId])->all();
	}
	public function saveOldTeam($jobId, $teamId)
	{
		$currentJobStep = JobStep::find()
			->select('jobStepId')
			->where(["jobId" => $jobId, "status" => JobStep::STATUS_INPROCESS])
			->orderby('dueDate')
			->asArray()
			->one();
		if (isset($currentJobStep) && !empty($currentJobStep)) {
			$jobStepId = $currentJobStep["jobStepId"];
		} else { // complete all step
			$currentJobStep = JobStep::find()
				->select('jobStepId')
				->where(["jobId" => $jobId])
				->orderby('jobStepId DESC')
				->asArray()
				->one();
			if (isset($currentJobStep) && !empty($currentJobStep)) {
				$jobStepId = $currentJobStep["jobStepId"];
			} else {
				$jobStepId = 0;
			}
		}
		$log = new LogJobTeam();
		$log->jobId = $jobId;
		$log->teamId = $teamId;
		$log->currentStepId = $jobStepId;
		$log->createDateTime = new Expression('NOW()');
		$log->updateDateTime = new Expression('NOW()');
		$log->status = 1;
		$log->save(false);
	}
	public function updatePIC1($jobId, $pic1, $percentage1)
	{
		$change = 0;
		//$typeId = Type::userTypeId('PIC 1');
		$typeId = JobResponsibility::PIC1;
		$response = JobResponsibility::find()
			->select('jobId,employeeId,percentage')
			->where(["jobId" => $jobId, "responsibility" => $typeId])
			->all();

		if (isset($response) && count($response) > 0) {
			if (count($pic1) != count($response)) {
				$change = 1;
			} else {
				if (count($pic1) > 0) {
					$i = 0;
					foreach ($pic1 as $p1) :
						if ($p1 != "") {
							$checkRes = JobResponsibility::find()
								->select('jobId,employeeId,percentage')
								->where(["jobId" => $jobId, "employeeId" => $p1, "percentage" => $percentage1[$i], "responsibility" => $typeId])
								->one();
							if (!isset($checkRes) || empty($checkRes)) {
								$change = 1;
								break;
							}
						}
						$i++;
					endforeach;
				} else {
					$change = 1;
				}
			}
		} else {
			$change = 1;
		}
		if ($change == 1) {
			LogJobPic::saveLogPic($jobId, $typeId);
			JobResponsibility::deleteAll(["jobId" => $jobId, "responsibility" => $typeId]);
			if (count($pic1) > 0) {
				foreach ($pic1 as $index => $employeeId) :
					if ($employeeId != "") {
						$newRes = new JobResponsibility();
						$newRes->jobId = $jobId;
						$newRes->employeeId = $employeeId;
						$newRes->responsibility = $typeId;
						$newRes->percentage = isset($percentage1[$index]) ? $percentage1[$index] : 0;
						$newRes->status = 1;
						$newRes->createDateTime = new Expression('NOW()');
						$newRes->updateDateTime = new Expression('NOW()');
						$newRes->save(false);
					}
				endforeach;
			}
		}
	}
	public function updatePIC2($jobId, $pic2, $percentage2)
	{
		$change = 0;
		//$typeId = Type::userTypeId('PIC 2');
		$typeId = JobResponsibility::PIC2;
		$response = JobResponsibility::find()
			->select('jobId,employeeId,percentage')
			->where(["jobId" => $jobId, "responsibility" => $typeId])
			->all();
		//throw new Exception(print_r($pic2, true));
		if (isset($response) && count($response) > 0) {
			if (count($pic2) != count($response)) {
				$change = 1;
			} else {
				if (count($pic2) > 0) {

					foreach ($pic2 as $index => $p2) :
						if ($p2 != "") {
							$checkRes = JobResponsibility::find()
								->select('jobId,employeeId,percentage')
								->where(["jobId" => $jobId, "employeeId" => $p2, "percentage" => $percentage2[$index], "responsibility" => $typeId])
								->one();
							if (!isset($checkRes) || empty($checkRes)) {
								$change = 1;
								break;
							}
						}
					endforeach;
				} else {
					$change = 1;
				}
			}
		} else {
			$change = 1;
		}
		if ($change == 1) {
			LogJobPic::saveLogPic($jobId, $typeId);
			JobResponsibility::deleteAll(["jobId" => $jobId, "responsibility" => $typeId]);
			if (count($pic2) > 0) {
				foreach ($pic2 as $index => $employeeId) :
					if ($employeeId != "") {
						$newRes = new JobResponsibility();
						$newRes->jobId = $jobId;
						$newRes->employeeId = $employeeId;
						$newRes->responsibility = $typeId;
						$newRes->percentage = isset($percentage2[$index]) ? $percentage2[$index] : 0;
						$newRes->status = 1;
						$newRes->createDateTime = new Expression('NOW()');
						$newRes->updateDateTime = new Expression('NOW()');
						$newRes->save(false);
					}
				endforeach;
			}
		}
	}
	public function updateFee($fee, $feeChargeDate, $advanceRec,  $advancedChargeDate, $outsourcingFee, $estimateTime, $currentJobStepId, $jobId, $jobCategoryId)
	{
		$oldFee = Job::find()
			->where(["jobId" => $jobId])
			->asarray()
			->one();
		$log = new LogFee();
		$log->jobId = $jobId;
		$log->jobCategoryId = $jobCategoryId;
		$log->jobStepId = $currentJobStepId;
		$log->fee = $fee;
		$log->feeChargeDate = $feeChargeDate;
		$log->advanceReceivable = $advanceRec;
		$log->advancedChargeDate = $advancedChargeDate;
		$log->outSourcingFee = $outsourcingFee;
		$log->estimateTime = $estimateTime;
		$log->createDateTime = new Expression('NOW()');
		$log->save(false);
	}
	public function actionAddComplain()
	{
		if (trim($_POST["complain"]) != "") {
			$job = new JobComplain();
			$job->jobId = $_POST["jobId"];
			$job->complain = $_POST["complain"];
			$job->status = 1;
			$job->createDateTime = new Expression('NOW()');
			$job->updateDateTime = new Expression('NOW()');
			$job->save(false);
			$text = "";
			$jobId = $_POST["jobId"];
			$allComplain = JobComplain::find()
				->where(["jobId" => $_POST["jobId"]])
				->asArray()
				->orderBy('createDateTime')
				->all();
			if (isset($allComplain) && count($allComplain) > 0) {
				foreach ($allComplain as $complain) :
					$text .= "- " . ModelMaster::engDate($complain["createDateTime"], 2) . ': ' . $complain["complain"] . '<br>';
				endforeach;
			}
			$jobAlert = JobAlert::find()
				->select('em.email,em.employeeNickName')
				->JOIN("LEFT JOIN", "employee em", "em.employeeId=job_alert.userId")
				->where(["job_alert.jobId" => $jobId])
				->asArray()
				->all();
			$subject = "Lower management Client's complain";
			$data = [];
			if (isset($jobAlert) && count($jobAlert) > 0) {
				$job = Job::find()
					->select('job.jobName,job.status as jstatus,c.clientName,b.branchName')
					->JOIN("LEFT JOIN", "branch b", "b.branchId=job.branchId")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->where(["job.jobId" => $jobId])
					->asArray()
					->one();
				foreach ($jobAlert as $alert) :
					$data["nickName"] = $alert["employeeNickName"];
					$data["branch"] = $job["branchName"];
					$data["clientName"] = $job["clientName"];
					$data["jobName"] = $job["jobName"];
					$data["pic1"] = JobResponsibility::jobResponseText($jobId, JobResponsibility::PIC1);
					$data["pic2"] = JobResponsibility::jobResponseText($jobId, JobResponsibility::PIC2);
					$data["currentStepDueDate"] = JobStep::CurrentStepEmail($jobId);
					$data["currentTargetDate"] = JobCategory::CurrentJobCategoryEmail($jobId);
					$data["status"] = Job::statusText($jobId);
					$data["complain"] = $text;
					Email::jobComplain($alert["email"], $subject, $data);
				endforeach;
			}
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function sendJobEmail($jobId)
	{
		$data = [];
		$email = JobAlert::find()->select('userId')->where(["jobId" => $jobId])->asArray()->all();
		$subject = "Lower management update information";
		if (isset($email) && count($email) > 0) {
			foreach ($email as $mail) :
				$employee = Employee::find()->select('email,employeeNickName,employeeFirstName')->where(["employeeId" => $mail["userId"], "status" => 1])->asArray()->one();
				if (isset($employee) && !empty($employee)) {
					$job = Job::find()
						->select('job.jobName,job.status as jstatus,c.clientName,b.branchName,job.jobTypeId,job.categoryId')
						->JOIN("LEFT JOIN", "branch b", "b.branchId=job.branchId")
						->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
						->where(["job.jobId" => $jobId])
						->asArray()
						->one();
					if (isset($job) && !empty($job)) {
						$data["nickName"] = $employee["employeeNickName"] == '' ? $employee["employeeFirstName"] : $employee["employeeNickName"];
						$data["branch"] = $job["branchName"];
						$data["clientName"] = $job["clientName"];
						$data["jobName"] = $job["jobName"];
						$data["jobType"] = JobType::jobTypeName($job["jobTypeId"]);
						$data["category"] = Category::categoryName($job["categoryId"]);
						$data["pic1"] = JobResponsibility::jobResponseText($jobId, JobResponsibility::PIC1);
						$data["pic2"] = JobResponsibility::jobResponseText($jobId, JobResponsibility::PIC2);
						$data["currentStepDueDate"] = JobStep::CurrentStepEmail($jobId);
						$data["currentTargetDate"] = JobCategory::CurrentJobCategoryEmail($jobId);
						$data["status"] = Job::statusText($jobId);
						$data["link"] = Path::frontendUrl() . 'job/detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $jobId]);
					}
					Email::jobUpdate($employee["email"], $subject, $data);
				}
			endforeach;
		}
	}
	public function actionNextTarget($hash)
	{
		$right = "all";
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$params = ModelMaster::decodeParams($hash);
		$jobCategoryId = $params["jobCategoryId"];
		$previousUrl = $params["previousUrl"];
		$current = [];
		$currentJobCategory = JobCategory::find()->where(["jobCategoryId" => $jobCategoryId])->asArray()->one();
		JobCategory::deleteAll(["jobId" => $currentJobCategory['jobId'], "status" => JobCategory::STATUS_WAITPROCESS]);
		if (isset($currentJobCategory) && !empty($currentJobCategory)) {
			$current["status"] = JobCategory::statusText($currentJobCategory["status"]);
			$current["targetDate"] = ModelMaster::engDate($currentJobCategory["targetDate"], 2);
			$current["targetNo"] = JobCategory::currentTargetNo($currentJobCategory["jobId"]);
			$current["currentJobCategoryId"] = $currentJobCategory["jobCategoryId"];
		}
		$oldJobSteps = JobStep::find()->where(["jobCategoryId" => $jobCategoryId])->asArray()->all();
		$nextDueDate = [];
		$jobCate = Job::find()->where(["jobId" => $currentJobCategory["jobId"]])->one();
		$catagory = Category::find()->where(["categoryId" => $jobCate->categoryId])->asArray()->one();
		if (isset($oldJobSteps) && count($oldJobSteps) > 0) {
			foreach ($oldJobSteps as $jobStep) :
				if ($jobStep["dueDate"] != null) {
					$dueDate = explode(' ', $jobStep["dueDate"]);
					if ($catagory["categoryName"] == "Monthly") {
						$nextDueDate[$jobStep["stepId"]] = date('Y-m-d', strtotime($dueDate[0] . "+1 month"));
					}
					if ($catagory["categoryName"] == "Yearly") {
						$nextDueDate[$jobStep["stepId"]] = date('Y-m-d', strtotime($dueDate[0] . "+1 year"));
					}
					if ($catagory["categoryName"] == "Half year") {
						$nextDueDate[$jobStep["stepId"]] = date('Y-m-d', strtotime($dueDate[0] . "+6 month"));
					}
					if ($catagory["categoryName"] == "Quarterly") {
						$nextDueDate[$jobStep["stepId"]] = date('Y-m-d', strtotime($dueDate[0] . "+3 month"));
					}
				}
			endforeach;
		}
		if ($catagory["categoryName"] == "Monthly") {
			$nextTargetDate = date('Y-m-d', strtotime($currentJobCategory["targetDate"] . "+1 month"));
		}
		if ($catagory["categoryName"] == "Yearly") {
			$nextTargetDate = date('Y-m-d', strtotime($currentJobCategory["targetDate"] . "+1 year"));
		}
		if ($catagory["categoryName"] == "Half year") {
			$nextTargetDate = date('Y-m-d', strtotime($currentJobCategory["targetDate"] . "+6 month"));
		}
		if ($catagory["categoryName"] == "Quarterly") {
			$nextTargetDate = date('Y-m-d', strtotime($currentJobCategory["targetDate"] . "+3 month"));
		}
		$nextJobCategory = JobCategory::find()
			->where("jobCategoryId!=$jobCategoryId")
			->andWhere(["jobId" => $currentJobCategory["jobId"], "status" => JobCategory::STATUS_WAITPROCESS])
			->asArray()
			->orderBy('targetDate ASC')
			->one();
		if (!isset($nextJobCategory) || empty($nextJobCategory)) {
			$next = new JobCategory();
			$next->status = JobCategory::STATUS_WAITPROCESS;
			$next->jobId = $currentJobCategory["jobId"];
			$next->categoryId = $currentJobCategory["categoryId"];
			$next->fiscalYear = $currentJobCategory["fiscalYear"];
			$next->createDateTime = new Expression('NOW()');
			$next->updateDateTime = new Expression('NOW()');
			$next->save(false);
			$nextJobCategory = JobCategory::find()
				->where("jobCategoryId!=$jobCategoryId")
				->andWhere(["jobId" => $currentJobCategory["jobId"], "status" => JobCategory::STATUS_WAITPROCESS])
				->asArray()
				->orderBy('targetDate ASC')
				->one();
		}
		$nextFiscalYear = $nextJobCategory["fiscalYear"];
		if ($catagory["categoryName"] == "Yearly") {
			$nextFiscalYear += 1;
		}
		$job = Job::find()
			->select('job.jobId,job.jobName,job.clientId,c.clientName,jt.jobTypeName,ct.categoryName,jt.jobTypeId')
			->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
			->JOIN("LEFT JOIN", "category ct", "ct.categoryId=job.categoryId")
			->JOIN("LEFT JOIN", "job_type jt", 'jt.jobTypeId=job.jobTypeId')
			->where(["job.jobId" => $currentJobCategory["jobId"]])
			->asArray()
			->one();
		$steps = Step::find()
			->select('stepName,stepId,jobTypeId')
			->where(["jobTypeId" => $job['jobTypeId'], "status" => Step::STATUS_ACTIVE])
			->orderBy('sort')
			->asArray()
			->all();
		$sub = [];
		$subSteps = AdditionalStep::find()
			->where(["jobId" => $currentJobCategory["jobId"], "jobCategoryId" => $currentJobCategory["jobCategoryId"]])
			->andWhere("status!=99")
			->asArray()
			->orderBy('sort')
			->all();
		if (isset($subSteps) && count($subSteps) > 0) {
			foreach ($subSteps as $subStep) :
				$sub[$subStep["stepId"]][$subStep["additionalStepId"]] = [
					"name" => $subStep["additionalStepName"],
					"sort" => $subStep["sort"]
				];
			endforeach;
		}
		return $this->render('next_target', [
			"currentJobCategory" => $currentJobCategory,
			"nextJobCategory" => $nextJobCategory,
			"current" => $current,
			"job" => $job,
			"steps" => $steps,
			"sub" => $sub,
			"nextDueDate" => $nextDueDate,
			"nextTargetDate" => $nextTargetDate,
			"fiscalYear" => $nextFiscalYear,
			"previousUrl" => $previousUrl
		]);
	}
	public function actionCreateNext()
	{
		$right = "all";
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$save = 0;
		$startMonth = null;
		$currentMonth = null;
		if (isset($_POST["newTargetDate"])) {
			$nextJobCategoryId = $_POST["nextJobCategoryId"];
			$currentJobCategoryId = $_POST["currentJobCategoryId"];
			$current = JobCategory::find()->where(["jobCategoryId" => $currentJobCategoryId])->one();
			$jobId = $current->jobId;
			$current->status = JobCategory::STATUS_COMPLETE;
			$current->completeDate = new Expression('NOW()');
			$categoryIdId = $current->categoryId;
			if ($current->startMonth != null) {
				$currentMonth = $current->startMonth;
			} else if ($current->targetDate != null) {
				$currentDate = explode(' ', $current->targetDate);
				$currentArr = explode('-', $currentDate[0]);
				$currentMonth = (int)$currentArr[1]; //เดือน
				$current->startMonth = $currentMonth;
			}
			$current->save(false);
			$jobStep = JobStep::find()->where(["jobCategoryId" => $currentJobCategoryId, "status" => JobStep::STATUS_INPROCESS])->all();
			if (isset($jobStep) && count($jobStep) > 0) {
				foreach ($jobStep as $step) :
					$step->status = JobStep::STATUS_COMPLETE;
					$step->completeDate = new Expression('NOW()');
					$step->save(false);
				endforeach;
			}
			$additional = AdditionalStep::find()->where(["jobCategoryId" => $currentJobCategoryId, "status" => 1])->all();
			if (isset($additional) && count($additional) > 0) {
				foreach ($additional as $step) :
					$step->status = 4;
					$step->completeDate = new Expression('NOW()');
					$step->save(false);
				endforeach;
			}
			Job::updateAll(["status" => Job::STATUS_INPROCESS], ["jobId" => $jobId]);
			$jobCategory = JobCategory::find()->where(["jobCategoryId" => $nextJobCategoryId])->one();
			$jobCategory->targetDate = $_POST["newTargetDate"];
			$jobCategory->status = JobCategory::STATUS_INPROCESS;
			$catagory = Category::find()->where(["categoryId" => $categoryIdId])->asArray()->one();
			if ($catagory["categoryName"] == "Monthly") {
				$nextMonth = $currentMonth + 1;
			}
			if ($catagory["categoryName"] == "Yearly") {
				$nextMonth = $currentMonth;
			}
			if ($catagory["categoryName"] == "Half year") {
				$nextMonth = $currentMonth + 6;
			}
			if ($catagory["categoryName"] == "Quarterly") {
				$nextMonth = $currentMonth + 3;
			}
			if ($nextMonth > 12) {
				$nextMonth = $nextMonth - 12;
			}
			$jobCategory->startMonth = $nextMonth;
			$jobCategory->fiscalYear = $_POST["fiscalYear"];
			$jobCategory->save(false);
			if (isset($_POST["stepDueDate"]) && count($_POST["stepDueDate"]) > 0) {
				$i = 0;
				foreach ($_POST["stepDueDate"] as $stepId => $dueDate) :
					$jobStep = new JobStep();
					$jobStep->jobId = $_POST["jId"];
					$jobStep->stepId = $stepId;
					$jobStep->dueDate = isset($dueDate) && $dueDate != "" ? $dueDate : null;
					$jobStep->status = 1;
					$jobStep->jobCategoryId = $nextJobCategoryId;
					$jobStep->createDateTime = new Expression('NOW()');
					$jobStep->updateDateTime = new Expression('NOW()');
					$jobStep->save(false);
					$save = 1;
					if ($i == 0) {
						$startMonth = $dueDate;
					}
					if (isset($_POST["subStepDueDate"][$stepId]) && count($_POST["subStepDueDate"][$stepId]) > 0) {
						foreach ($_POST["subStepDueDate"][$stepId] as $sort => $dueDate) :
							$subStep = new AdditionalStep();
							$subStep->jobId = $jobId;
							$subStep->stepId = $stepId;
							$subStep->jobCategoryId = $nextJobCategoryId;
							$subStep->additionalStepName = $_POST["subStepName"][$stepId][$sort];
							$subStep->sort = $sort;
							$subStep->dueDate = $dueDate;
							$subStep->completeDate = null;
							$subStep->status = 1;
							$subStep->createDateTime = new Expression('NOW()');
							$subStep->updateDateTime = new Expression('NOW()');
							$subStep->save(false);
						endforeach;
					}
					$i++;
				endforeach;
			}
			$newJobcategory = JobCategory::find()->where(["jobCategoryId" => $nextJobCategoryId])->one();
			if ($newJobcategory->startMonth == null) {
				if ($startMonth != null) {
					$dateArr = explode('-', $startMonth);
					$startMonth = (int)$dateArr[1];
				} else {
					$startMonth = (int)date('m');
				}
				$newJobcategory->startMonth = $startMonth;
				$newJobcategory->save(false);
			}
			if ($save == 1) {
				//return $this->redirect($_POST["pUrl"]);
				return $this->redirect(Yii::$app->homeUrl . 'job/detail/job-detail/' . ModelMaster::encodeParams([
					"jobId" => $_POST["jId"],
					"previousUrl" => $_POST["pUrl"]

				]));
			} else {
				return $this->redirect(Yii::$app->homeUrl . 'job/detail/next-target/' . ModelMaster::encodeParams(["jobCategoryId" => $currentJobCategoryId]));
			}
		}
	}
	public function actionCancelComplete()
	{
		$res = [];
		$reason = $_POST["reason"];
		$jobStepId = $_POST["jobStepId"];
		if (trim($reason == "")) {
			$res["status"] = false;
		} else {
			$cancel = new LogCancel();
			$cancel->jobStepId = $jobStepId;
			$cancel->reason = $reason;
			$cancel->createDateTime = new Expression('NOW()');
			if ($cancel->save(false)) {
				$jobStep = JobStep::find()->where(["jobStepId" => $jobStepId])->one();
				$jobStep->status = JobStep::STATUS_INPROCESS;
				if ($jobStep->save(false)) {
					$res["status"] = true;
				}
				$jobCategory = JobCategory::find()->where(["jobCategoryId" => $jobStep["jobCategoryId"]])->one();
				$jobId = $jobCategory->jobId;
				$jobCategory->status = JobCategory::STATUS_INPROCESS;
				$jobCategory->save(false);
				$job = Job::find()->where(["jobId" => $jobId])->one();
				$job->status = Job::STATUS_INPROCESS;
				$job->save(false);
			}
		}
		return json_encode($res);
	}
	public function actionCancelAdditionalStepComplete()
	{
		$additionalStepId = $_POST["additionalStepId"];
		$remark = $_POST["reason"];
		$date = date('Y-m-d') . ' 00:00:00';
		if (trim($remark) != '') {
			$add = AdditionalStep::find()->where(["additionalStepId" => $additionalStepId])->one();

			if (isset($add) && !empty($add)) {
				$stepId = $add->stepId;
				$jobCategoryId = $add->jobCategoryId;
				$jobId = $add->jobId;
				$add->status = 1;
				$add->remark .= '<br>' . ModelMaster::engDate($date, 2) . '<br>' . '- ' . $remark;
				$add->save(false);
				$res["status"] = true;
				$jobCategory = JobCategory::find()->where(["jobCategoryId" => $jobCategoryId])->one();
				$jobCategory->status = JobCategory::STATUS_INPROCESS;
				$jobCategory->save(false);
				$job = Job::find()->where(["jobId" => $jobId])->one();
				$job->status = Job::STATUS_INPROCESS;
				$job->save(false);
			}
		} else {
			$res["status"] = false;
		}

		return json_encode($res);
	}
	public function actionCancelDetail()
	{
		$jobStepId = $_POST["jobStepId"];
		$text = '';
		$res = [];
		$log = LogCancel::find()
			->where(["jobStepId" => $jobStepId, "status" => 1])
			->asArray()
			->orderBy('createDateTime DESC')
			->all();
		if (isset($log) && count($log) > 0) {
			foreach ($log as $l) :
				$text .= "<div class='col-12 text-left mt-10'><b>" . ModelMaster::engDate($l["createDateTime"], 2) . "</b></div>";
				$text .= "<div class='col-12 text-left mt-10 pl-5'> - " . $l["reason"] . "</div>";
			endforeach;
		}
		if (trim($text) != "") {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionCancelSubDetail()
	{
		$additionalId = $_POST["additionalStepId"];
		$text = '';
		$res = [];
		$add = AdditionalStep::find()->select('remark')->where(["additionalStepId" => $additionalId])->one();
		if (isset($add) && !empty($add) > 0) {
			$text = "<div class='col-12 text-left mt-10 pl-5'> - " . $add["remark"] . "</div>";
		}
		if (trim($text) != "") {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionDeleteJob()
	{
		$jobId = $_POST["jobId"];
		$job = Job::find()->where(["jobId" => $jobId])->one();
		$job->status = Job::STATUS_DELETED;
		$job->save(false);
		JobCategory::updateAll(["status" => 99], ["jobId" => $jobId]);

		if ($job->save(false)) {
			$res["status"] = true;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionJobTypeDocument()
	{
		$text = '';
		$res = [];
		$jobTypeId = $_POST["jobTypeId"];
		$jobType = JobType::find()->select('jobTypeDetail,jobTypeName')->where(["jobTypeId" => $jobTypeId])->asArray()->one();
		if (isset($jobType) && !empty($jobType)) {
			$res["status"] = true;
			$text .= '<div class="font-size16"><b>' . $jobType["jobTypeName"] . '</b></div>';
			$text .= '<div class="font-size16 mt-10 pl-3">' . $jobType["jobTypeDetail"] . '</div>';
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionEditJobTypeDocument()
	{
		$text = '';
		$res = [];
		$jobTypeId = $_POST["jobTypeId"];
		$jobType = JobType::find()->select('jobTypeDetail,jobTypeName')->where(["jobTypeId" => $jobTypeId])->asArray()->one();
		if (isset($jobType) && !empty($jobType)) {
			$res["status"] = true;
			$res["text"] = $jobType["jobTypeDetail"];
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionChangeJobName()
	{
		$jobId = $_POST["jobId"];
		$job = Job::find()->where(["jobId" => $jobId])->one();
		$job->jobName = $_POST["jobName"];
		$job->createDateTime = new Expression('NOW()');
		$job->save(false);
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionUpdateJobCategory()
	{
		$right = "all";
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$jobCate = JobCategory::find()->where(["status" => JobCategory::STATUS_COMPLETE])->all();
		if (isset($jobCate) && count($jobCate) > 0) {
			foreach ($jobCate as $jc) :
				$jobStep = JobStep::find()->where(["jobCategoryId" => $jc->jobCategoryId, "status" => JobStep::STATUS_INPROCESS])->all();
				if (isset($jobStep) && count($jobStep) > 0) {
					foreach ($jobStep as $js) :
						$js->status = JobStep::STATUS_COMPLETE;
						$js->completeDate = $js->dueDate;
						$js->save(false);
					endforeach;
				}
			endforeach;
		}
		$jobStep = JobStep::find()->where(["completeDate" => Null, "status" => JobStep::STATUS_COMPLETE])->all();
		if (isset($jobStep) && count($jobStep) > 0) {
			foreach ($jobStep as $js) :
				$js->completeDate = $js->dueDate;
				$js->save(false);
			endforeach;
		}
	}
	public function actionDeleteError()
	{
		$jobCate = JobCategory::find()->where(["status" => JobCategory::STATUS_WAITPROCESS])->all();
		if (isset($jobCate) && count($jobCate) > 0) {
			foreach ($jobCate as $cate)
				$jobstep = JobStep::find()->where(["jobCategoryId" => $cate->jobCategoryId])->one();
			if (!isset($jobstep) || empty($jobstep)) {
				$cate->delete();
			}
		}
	}
	public function actionLogDuedate()
	{
		$text = "";
		$jobStepId = $_POST["jobStepId"];
		$res = [];
		$log = LogJobStep::find()
			->where(["jobStepId" => $jobStepId])
			->asArray()
			->orderBy("createDateTime DESC")
			->all();
		if (isset($log) && count($log) > 0) {
			$text = $this->renderAjax("log_due", [
				"log" => $log
			]);
		}
		if ($text != "") {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionLogAdjustDate()
	{
		$text = "";
		$jobStepId = $_POST["jobStepId"];
		$res = [];
		$log = AdjustDuedate::find()
			->where(["jobStepId" => $jobStepId])
			->asArray()
			->orderBy("createDateTime DESC")
			->all();
		if (isset($log) && count($log) > 0) {
			$text = $this->renderAjax("log_adjust", [
				"log" => $log
			]);
		}
		if ($text != "") {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionLogAdjustDateAdd()
	{
		$text = "";
		$additionalStepId = $_POST["additionalStepId"];
		$res = [];
		$log = AddjustDuedateAdditional::find()
			->where(["additionalStepId" => $additionalStepId])
			->asArray()
			->orderBy("createDateTime DESC")
			->all();
		if (isset($log) && count($log) > 0) {
			$text = $this->renderAjax("log_adjust_add", [
				"log" => $log
			]);
		}
		if ($text != "") {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionFieldInGroup()
	{
		$text = '';
		$res = [];
		$fields = Field::find()
			->select('fieldId,fieldName')
			->where(["subFieldGroupId" => $_POST["groupId"], "status" => 1, "branchId" => $_POST["branchId"]])
			->asArray()
			->orderBy('fieldName')
			->all();
		$text = '';
		if (isset($fields) && count($fields) > 0) {
			foreach ($fields as $field) :
				$text .= '<option value="' . $field["fieldId"] . '">' . $field["fieldName"] . '</option>';
			endforeach;
		}
		if ($text != '') {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionAddMoreAdditionalStep()
	{
		$text = "";
		$stepId = $_POST["stepId"];
		$sort = $_POST["sort"] + 1;
		$id = Yii::$app->security->generateRandomString(12);
		$text = $this->renderAjax('more_additional_step', [
			"stepId" => $stepId,
			"id" => $id,
			"sort" => $sort
		]);
		$res["status"] = true;
		$res["text"] = $text;
		return json_encode($res);
	}
	public function actionDeleteJobStep()
	{
		$steps = Step::find()->where(["status" => 99])->all();
		if (isset($steps) && count($steps) > 0) {
			foreach ($steps as $step) :
				JobStep::deleteAll(["stepId" => $step->stepId]);
			endforeach;
		}
	}
	public function actionUpdateJobToInprocess()
	{
		$jobStep = JobStep::find()->where(["status" => 1])->all();
		if (isset($jobStep) && count($jobStep) > 0) {
			foreach ($jobStep as $js) :
				$jobCategory = JobCategory::find()->where(["jobCategoryId" => $js->jobCategoryId, "status" => 4])->one();
				if (isset($jobCategory) && !empty($jobCategory)) {
					$jobCategory->status = 1;
					$jobCategory->save(false);
				}
			endforeach;
		}
		$jobCategory = JobCategory::find()->where(["status" => 1])->all();
		if (isset($jobCategory) && count($jobCategory) > 0) {
			foreach ($jobCategory as $j) :
				$job = Job::find()->where(["jobId" => $j->jobId])->one();
				if (isset($job) && !empty($job)) {

					$job->status = Job::STATUS_INPROCESS;
					$job->save(false);
				}
			endforeach;
		}
	}
	public function actionUpdateLastJobStep()
	{

		$jobCategory = JobCategory::find()->where(["status" => 1])->all();
		$deleteStep = [];
		$i = 0;
		if (isset($jobcategory) && count($jobCategory) > 0) { //ลบ jobStepที่ไม่มีใน step
			foreach ($jobCategory as $jc) :
				$job = Job::find()->select('jobTypeId')->where(["jobId" => $jc->jobId])->asArray()->one();
				$jobToypeId = $job["jobTypeId"];
				$jobStep = JobStep::find()->where(["jobCategoryId" => $jc->jobCategoryId])->all();
				if (isset($jobStep) && count($jobStep) > 0) {
					foreach ($jobStep as $jt) :
						$step = Step::find()->where(["jobTypeId" => $jobToypeId, "stepId" => $jt->stepId])->one();
						if (!isset($step) || empty($step)) {
							$jt->delete();
						}
					endforeach;
				}
			endforeach;
			foreach ($jobCategory as $jc) : //เพิ่มที่ไม่มี
				$masterStep = [];
				$currentStep = [];
				$job = Job::find()->select('jobTypeId,jobId')->where(["jobId" => $jc->jobId])->asArray()->one();
				$jobToypeId = $job["jobTypeId"];
				$steps = Step::find()->where(["jobTypeId" => $jobToypeId, "status" => 1])->all();
				if (isset($steps) && count($steps) > 0) {
					foreach ($steps as $step) :
						$jobStep = JobStep::find()
							->where([
								"jobCategoryId" => $jc->jobCategoryId,
								"stepId" => $step->stepId
							])
							->one();
						if (!isset($jobStep) || empty($jobStep) > 0) {
							$addStep = new JobStep();
							$addStep->jobId = $job->jobId;
							$addStep->jobCategoryId = $jc->jobCategoryId;
							$addStep->stepId = $step->stepId;
							$addStep->dueDate = Null;
							$addStep->status = 1;
							$addStep->createDateTime = new Expression('NOW()');
							$addStep->updateDateTime = new expression('NOW()');
						}
					endforeach;
				}

			endforeach;
		}

		//throw new exception(print_r($deleteStep, true));

		//เพิ่ม step ที่ไม่มีใน jobstep 
	}
	public function actionUpdateFiscalYear()
	{
		$jobCategory = JobCategory::find()->where(["fiscalYear" => null])->all();
		$year = date('Y');
		if (isset($jobCategory) && count($jobCategory) > 0) {
			foreach ($jobCategory as $jt) :
				$jobStep = JobStep::find()
					->where(["jobCategoryId" => $jt->jobCategoryId])
					->orderBy('jobStepId')
					->asArray()
					->one();
				if (isset($jobStep) && !empty($jobStep)) {
					$dueDate = $jobStep["dueDate"];
					if ($dueDate != null) {
						$dateTimeArr = explode(" ", $dueDate);
						$date = $dateTimeArr[0];
						$dateArr = explode("-", $date);
						$year = $dateArr[0];
					} else {
						$dateTimeArr = explode(" ", $jobStep["createDateTime"]);
						$date = $dateTimeArr[0];
						$dateArr = explode("-", $date);
						$year = $dateArr[0];
					}
				} else {
					if ($jt->targetDate != null) {
						$dateTimeArr = explode(" ", $jt->targetDate);
						$date = $dateTimeArr[0];
						$dateArr = explode("-", $date);
						$year = $dateArr[0];
					} else {
						$dateTimeArr = explode(" ", $jt->createDateTime);
						$date = $dateTimeArr[0];
						$dateArr = explode("-", $date);
						$year = $dateArr[0];
					}
				}
				$jt->fiscalYear = $year;
				$jt->save(false);
			endforeach;
		}
	}
	public function actionExportIssue($jc)
	{
		$jobCategoryId = $jc;
		$today = ModelMaster::engDate(date('Y-m-d') . " 00:00:00", 2);
		$category = JobCategory::find()
			->select("j.clientId,j.jobName,c.clientName,j.jobId")
			->JOIN("LEFT JOIN", "job j", "j.jobId=job_category.jobId")
			->JOIN("LEFT JOIN", "client c", "c.clientId=j.clientId")
			->where(["job_category.jobCategoryId" => $jobCategoryId])
			->asArray()
			->one();
		$pic1 = '<div class="row">' . Job::jobResponsibility($category["jobId"], JobResponsibility::PIC1) . '</div>';
		$pic2 = '<div class="row">' . Job::jobResponsibility($category["jobId"], JobResponsibility::PIC2) . '</div>';
		$steps = JobStep::find()
			->select('s.stepName,job_step.dueDate,job_step.completeDate,job_step.status,job_step.remark')
			->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
			->where(["job_step.jobCategoryId" => $jobCategoryId])
			->orderBy("s.sort")
			->asArray()
			->all();
		$htmlExcel = $this->renderPartial('issue', [
			"category" => $category,
			"steps" => $steps,
			"today" => $today,
			"PIC1" => $pic1,
			"PIC2" => $pic2,
		]);
		//throw new Exception($htmlExcel);
		$spreadsheet = new Spreadsheet;
		$reader = new Html();
		$spreadsheet = $reader->loadFromString($htmlExcel);
		$spreadsheet->getDefaultStyle()->getFont()->setSize(14);
		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
		$spreadsheet->getActiveSheet()->getRowDimension('10')->setRowHeight(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(50);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$spreadsheet->getActiveSheet()
			->getStyle('A10:J10')
			->getBorders()
			->getAllBorders()
			->setBorderStyle(Border::BORDER_THIN);
		$spreadsheet->getActiveSheet()
			->getStyle('A10:J10')
			->getFill()
			->setFillType(Fill::FILL_SOLID)
			->getStartColor()
			->setARGB('33CC99');

		for ($i = 'A'; $i !=  'O'; $i++) {
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(false);
		}

		$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

		for ($row = 11; $row <= $highestRow; $row++) {
			$spreadsheet->getActiveSheet()->getStyle("B$row")->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getRowDimension("$row")->setRowHeight(50);
		}
		$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
		for ($row = 11; $row <= $highestRow; $row++) {
			$spreadsheet->getActiveSheet()->getStyle("J$row")->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getRowDimension("$row")->setRowHeight(50);
		}
		for ($i = 11; $i <= $highestRow; $i++) {
			$spreadsheet->getActiveSheet()->getRowDimension("$i")->setRowHeight(-1);
		}
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$folderName = "export";
		$filename = Yii::$app->user->id . Yii::$app->security->generateRandomString(10) . '.xlsx';
		$urlFolder = Path::getHost() . 'file/' . $folderName . "/" . $filename;
		$folder_path = Path::getHost() . 'file/' . $folderName;
		$files = glob($folder_path . '/*');
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
		$writer->save($urlFolder);
		return Yii::$app->response->sendFile($urlFolder, $category["jobName"] . '[' . $category["clientName"] . ']' . '.xlsx');
	}
	public function actionChangeFiscalYear()
	{
		$jobCategoryId = $_POST["jobCategoryId"];
		$newFiscalYear = $_POST["newFiscalYear"];
		$oldFiscalYear = $_POST["oldFiscalYear"];
		$jobCategory = JobCategory::find()->where(["jobCategoryId" => $jobCategoryId])->one();
		$jobCategory->fiscalYear = $newFiscalYear;
		$jobCategory->updateDateTime = new Expression('NOW()');
		$jobCategory->save(false);
		$logFiscalYear = new LogFiscalYear();
		$logFiscalYear->jobCategoryId = $jobCategoryId;
		$logFiscalYear->oldFiscalYear = $oldFiscalYear;
		$logFiscalYear->newFiscalYear = $newFiscalYear;
		$logFiscalYear->createDateTime = new Expression('NOW()');
		$logFiscalYear->updateDateTime = new Expression('NOW()');
		$logFiscalYear->save(false);
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionChangeTargetDate()
	{
		$jobCategoryId = $_POST["jobCategoryId"];
		$newTargetDate = $_POST["newTargetDate"];
		$jobCategory = JobCategory::find()->where(["jobCategoryId" => $jobCategoryId])->one();
		$oldTargetDate = $jobCategory->targetDate;
		$jobCategory->targetDate = $newTargetDate;
		$jobCategory->updateDateTime = new Expression('NOW()');
		$jobCategory->save(false);
		$logTarget = new LogTargetDate();
		$logTarget->jobCategoryId = $jobCategoryId;
		$logTarget->oldTargetDate = $oldTargetDate;
		$logTarget->newTargetDate = $newTargetDate;
		$logTarget->createDateTime = new Expression('NOW()');
		$logTarget->updateDateTime = new Expression('NOW()');
		$logTarget->save(false);
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionChangeTargetMonth()
	{
		$res = [];
		$jobCategory = JobCategory::find()->where(["jobCategoryId" => $_POST["jobCategoryId"]])->one();
		if ($_POST["newTargetMonth"] != '') {
			$jobCategory->startMonth = ModelMaster::shotMonthValue($_POST["newTargetMonth"]);
			$jobCategory->save(false);
			$res["status"] = true;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionShowNextTarget()
	{

		$jobStepId = $_POST["jobStepId"];
		// $jobStep = JobStep::find()
		// 	->where(["status" => 1, "jobStepId" => $jobStepId])
		// 	->asArray()
		// 	->one();
		$jobStep = JobStep::find()
			->select('job_step.*,s.sort')
			->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
			->where(["job_step.status" => 1, "job_step.jobStepId" => $jobStepId])
			->asArray()
			->one();
		// $jobStep2 = JobStep::find()
		// 	->where(["status" => 1, "jobCategoryId" => $jobStep["jobCategoryId"]])
		// 	->andWhere("jobStepId>" . $jobStep["jobStepId"])
		// 	->asArray()
		// 	->one();
		//throw new exception(print_r($jobStep, true));
		$jobStep2 = JobStep::find()
			->select('job_step.*,s.sort')
			->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
			->where(["job_step.status" => 1, "job_step.jobCategoryId" => $jobStep["jobCategoryId"]])
			->andWhere("s.sort>" . $jobStep["sort"])
			->asArray()
			->one();

		if (isset($jobStep2) && !empty($jobStep2)) {
			$res["status"] = false;
		} else { // last
			$jobStep = JobStep::find()
				->where(["jobStepId" => $jobStepId])
				->asArray()
				->one();
			$add = AdditionalStep::find()
				->where(["stepId" => $jobStep["stepId"], "jobCategoryId" => $jobStep["jobCategoryId"], "status" => 1])
				->one();
			if (isset($add) && !empty($add)) {
				$res["status"] = false;
			} else {
				$res["status"] = true;
			}
		}
		//$res["status"] = true;
		return json_encode($res);
	}
	public function actionShowNextTargetAdd()
	{
		$additionalStepId = $_POST["additionalStepId"];
		$res = [];
		$add = AdditionalStep::find()
			->where(["additionalStepId" => $additionalStepId])
			->asArray()
			->one();
		$add2 = AdditionalStep::find()
			->where("jobCategoryId=" . $add["jobCategoryId"] . " and additionalStepId>" . $add["additionalStepId"] . " and status!=99")
			->asArray()
			->one();
		if (isset($add2) && !empty($add2)) {
			$res["status"] = 0;
		} else {
			$add3 = AdditionalStep::find()
				->where("jobCategoryId=" . $add["jobCategoryId"] . " and stepId=" . $add["stepId"] . " and sort>" . $add["sort"] . " and status!=99")
				->asArray()
				->one();
			if (isset($add3) && !empty($add3)) {
				$res["status"] = 0;
			} else {
				$jobStep = JobStep::find()
					->where([
						"jobCategoryId" => $add["jobCategoryId"],
						"status" => JobStep::STATUS_INPROCESS,
					])
					->andWhere("stepId!=" . $add["stepId"])
					->one();
				if (isset($jobStep) && !empty($jobStep)) {
					$res["status"] = 0;
				} else {
					$res["status"] = 1;
				}
			}
		}
		return json_encode($res);
	}
	public function actionJobStepComment()
	{
		$res = [];
		$jobStep = JobStep::find()->where(["jobStepId" => $_POST["jobStepId"]])->one();
		$res["text"] = $jobStep->remark;
		return json_encode($res);
	}
	public function actionSaveComment()
	{
		$res = [];
		$jobStep = JobStep::find()->where(["jobStepId" => $_POST["jobStepId"]])->one();
		$jobStep->remark = $_POST["comment"];
		$jobStep->updateDateTime = new Expression('NOW()');
		if ($jobStep->save(false)) {
			$res["status"] = true;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionShowComment()
	{
		$res = [];
		$jobStep = JobStep::find()->where(["jobStepId" => $_POST["jobStepId"]])->one();
		$res["text"] = $jobStep->remark;
		return json_encode($res);
	}
	public function actionSaveSubmitDate()
	{
		$jobId = $_POST["jobId"];
		$jobCategoryId = $_POST["jobCategoryId"];
		$currentStep = JobStep::find()
			->select('stepId')
			->where(["jobCategoryId" => $jobCategoryId, "status" => JobStep::STATUS_INPROCESS])
			->orderBy('jobStepId')
			->asArray()
			->one();
		$res["status"] = false;
		if (!isset($currentStep) || empty($currentStep)) {
			$currentStep = JobStep::find()
				->select('stepId')
				->where(["jobCategoryId" => $jobCategoryId, "status" => JobStep::STATUS_APPROVED])
				->orderBy('jobStepId DESC')
				->asArray()
				->one();
		}
		if (isset($currentStep) && !empty($currentStep)) {
			$stepId = $currentStep["stepId"];
			$submitDate = new SubmitReport();
			$submitDate->jobId = $jobId;
			$submitDate->CategoryId = $jobCategoryId;
			$submitDate->stepId = $stepId;
			$submitDate->submitDate = $_POST["submitDate"];
			$submitDate->status = 1;
			$submitDate->createDateTime = new Expression('NOW()');
			$submitDate->updateDateTime = new Expression('NOW()');
			if ($submitDate->save(false)) {
				$res["status"] = true;
			}
		}
		return json_encode($res);
	}
	public function actionChangeCompleteDate()
	{
		$res = [];
		$jobCategoryId = $_POST["jobcateId"];
		$stepId = $_POST["stepId"];
		$newDate = $_POST["newDate"] . " 00:00:00";
		$jobStep = JobStep::find()->where(["jobCategoryId" => $jobCategoryId, "stepId" => $stepId])->one();
		date_default_timezone_set("Asia/Bangkok");
		if (isset($jobStep) && !empty($jobStep)) {
			$adjust = AdjustDuedate::find()->where(["jobStepId" => $jobStep->jobStepId])->orderBy('id ASC')->one();
			if (isset($adjust) && !empty($adjust)) {
				$lmsDate = $adjust->lmsDate;
			} else {
				$lmsDate = $jobStep->completeDate;
			}
			$newAdjust = new AdjustDueDate();
			$newAdjust->jobStepId = $jobStep->jobStepId;
			$newAdjust->lmsDate = $lmsDate;

			$newAdjust->employeeId = Yii::$app->user->id;
			$newAdjust->newDate = $newDate;
			$newAdjust->createDateTime = new expression('NOW()');
			if ($newAdjust->save(false)) {
				$res["status"] = true;
				$res["newDate"] = ModelMaster::engDate($newDate, 2);
				$jobStep->completeDate = $newDate;
				$jobStep->save(false);
			}
		}
		return json_encode($res);
	}
	public function actionChangeCompleteDateAdditional()
	{
		$res = [];
		$additionalStepId = $_POST["additionalStepId"];

		$newDate = $_POST["newDate"] . " 00:00:00";

		date_default_timezone_set("Asia/Bangkok");
		$adjustAdditional = AddjustDuedateAdditional::find()->where(["additionalStepId" => $additionalStepId])->orderby('id ASC')->one();
		$additionalStep = AdditionalStep::find()->where(["additionalStepId" => $additionalStepId])->one();
		if (isset($adjustAdditional) && !empty($adjustAdditional)) {
			$lmsDate = $adjustAdditional->lmsDate;
		} else {
			$lmsDate = $additionalStep->completeDate;
		}
		$newAdjust = new AddjustDuedateAdditional();
		$newAdjust->additionalStepId = $additionalStepId;
		$newAdjust->lmsDate = $lmsDate;
		$newAdjust->employeeId = Yii::$app->user->id;
		$newAdjust->newDate = $newDate;
		$newAdjust->createDateTime = new expression('NOW()');
		if ($newAdjust->save(false)) {
			$res["status"] = true;
			$res["newDate"] = ModelMaster::engDate($newDate, 2);
			$additionalStep->completeDate = $newDate;
			$additionalStep->save(false);
		}
		return json_encode($res);
	}
	public function actionUpdateFirstDuedate()
	{
		/*$jobStep = JobStep::find()->where("status=4 or status=1")->all();
		if (isset($jobStep) && count($jobStep) > 0) {
			foreach ($jobStep as $js) :
				if ($js->dueDate == "0000-00-00 00:00:00") {
					$js->firstDueDate = "2023-02-10 18:39:50";
				} else {
					$js->firstDueDate = $js->dueDate;
				}
				$js->save(false);
			endforeach;
		}*/
		/*$jobCategory = JobCategory::find()->where("status=4 or status=1 or status=10")->all();
		if (isset($jobCategory) && count($jobCategory) > 0) {
			foreach ($jobCategory as $jc) :
				$jc->firstTargetDate = $jc->targetDate;
				$jc->save(false);
			endforeach;
		}
		$additional = AdditionalStep::find()->where("status=1 or status=4")->all();
		if (isset($additional) && count($additional) > 0) {
			foreach ($additional as $add) :
				if ($add->dueDate != null) {
					$add->firstDueDate = $add->dueDate;
					$add->save(false);
				}
			endforeach;
		}*/
	}
	public function actionUpdateStartMonth()
	{
		$jobCategory = JobCategory::find()->where("startMonth>12")->all();
		if (isset($jobCategory) && count($jobCategory) > 0) {
			foreach ($jobCategory as $jc) :
				$jc->startMonth = $jc->startMonth - 12;
				$jc->save(false);
			endforeach;
		}
	}
	public function findJobByUrl($url)
	{
		$jobName = '';
		if ($url != '' && $url != null) {
			$urlArr = explode('/', $url);
			if (count($urlArr) > 0) {
				$countId = count($urlArr);
				$jobIdHash = $urlArr[$countId - 1];


				$a = str_replace("%3D", "=", $jobIdHash);
				$b = str_replace("%21", "!", $a);
				$c = str_replace("%23", "#", $b);
				$d = str_replace("%24", "$", $c);
				$e = str_replace("%26", "&", $d);
				$f = str_replace("%27", "'", $e);
				$g = str_replace("%28", "(", $f);
				$h = str_replace("%29", ")", $g);
				$i = str_replace("%2A", "*", $h);
				$j = str_replace("%2B", "+", $i);
				$k = str_replace("%2C", ",", $j);
				$l = str_replace("%2F", "/", $k);
				$m = str_replace("%3A", ":", $l);
				$n = str_replace("%3B", ";", $m);
				$o = str_replace("%3F", "?", $n);
				$p = str_replace("%40", "@", $o);
				$q = str_replace("%5B", "[", $p);
				$r = str_replace("%5D", "]", $q);
				$param = ModelMaster::decodeParams($r);
				if (isset($param["jobId"])) {
					$job = Job::find()->select('jobName')->where(["jobId" => $param["jobId"]])->asArray()->one();
					if (isset($job) && !empty($job)) {
						$jobName = $job["jobName"];
					}
				}
			}
		}
		return $jobName;
	}
}
