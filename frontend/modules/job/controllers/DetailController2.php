<?php

namespace frontend\modules\job\controllers;

use common\carlendar\Carlendar;
use common\email\Email;
use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use FFI\Exception as FFIException;
use frontend\models\lower_management\AdditionalStep;
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
use frontend\models\lower_management\JobTypeStep;
use frontend\models\lower_management\LogCancel;
use frontend\models\lower_management\LogFee;
use frontend\models\lower_management\LogJobCategory;
use frontend\models\lower_management\LogJobPic;
use frontend\models\lower_management\LogJobStep;
use frontend\models\lower_management\LogJobTeam;
use frontend\models\lower_management\LogSubStep;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\SubFieldGroup;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use frontend\models\lower_management\Type;
use Matrix\Operators\Addition;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
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
		$fields = Field::find()
			->select('fieldName,fieldId')
			->where(["status" => Field::STATUS_ACTIVE])
			->asArray()
			->orderBy('fieldName ASC')
			->all();
		$job = Job::find()
			->where("job.status!=" . Job::STATUS_DELETED)->all();
		$branchId = "";
		$employeeType = EmployeeType::findEmployeeType();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		$fag = 0;
		$onlyManager = 0;
		$rightBranch = Type::TYPE_MANAGER;
		$teamId = Employee::employeeTeam();
		$teams = [];
		$persons = [];
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

			$branch = Branch::find()
				->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
			$jobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["status" => JobType::STATUS_ACTIVE])
				->asarray()
				->orderBy('jobTypeName')
				->all();
		} else { //NORMAL STAFF
			$branchId = Employee::employeeBranch();
			$query = Job::find()
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->where("job.status!=" . Job::STATUS_DELETED)
				->andWhere(["job.teamId" => $teamId])
				->orderBy('c.clientName ASC');
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $branchId])
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
			}
			$teams = Team::find()->select('teamId,teamName')
				->where(["branchId" => $branchId, "status" => 1])
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

		return $this->render('index', [
			"job" => $job,
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
			"jobType" => $jobType

		]);
	}
	/*public function actionSearchFilter()
	{
		$textTeam = '<option value="">Team</option>';
		$textPerson = '<option value="">Person</option>';
		$employeeType = EmployeeType::findEmployeeType();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_MANAGER, Type::TYPE_GM];
		$fag = 0;
		$teamId = Employee::employeeTeam();
		if (count($rightAll) > 0) {
			foreach ($employeeType as $all) :
				if (in_array($all, $rightAll)) {
					$fag = 1;
				}
			endforeach;
		}
		if ($fag == 1) {
			$team = Team::find()
				->select('teamId,teamName')
				->where(["branchId" => $_POST["id"], "status" => Team::STATUS_ACTIVE])
				->orderBy("teamName")
				->asArray()
				->all();
			$type = Employee::find()->select('t.typeId,t.typeName,employee.employeeNickName as nickName,employee.employeeId as emId')
				->JOIN("LEFT JOIN", "employee_type emt", "emt.employeeId=employee.employeeId")
				->JOIN("LEFT JOIN", "type t", "t.typeId=emt.typeId")
				->where(["t.typeName" => "PIC 1"])
				->orwhere(["t.typeName" => "PIC 2"])
				->andWhere(["employee.status" => Employee::STATUS_CURRENT, "employee.branchId" => $_POST["id"]])
				->asArray()
				->groupBy('emt.employeeId')
				->orderBy('nickName')
				->all();
		} else {
			$team = Team::find()
				->select('teamId,teamName')
				->where(["branchId" => $_POST["id"], "teamId" => $teamId, "status" => Team::STATUS_ACTIVE])
				->orderBy("teamName")
				->asArray()
				->all();
			$type = Employee::find()->select('t.typeId,t.typeName,employee.employeeNickName as nickName,employee.employeeId as emId')
				->JOIN("LEFT JOIN", "employee_type emt", "emt.employeeId=employee.employeeId")
				->JOIN("LEFT JOIN", "type t", "t.typeId=emt.typeId")
				->where(["t.typeName" => "PIC 1"])
				->orwhere(["t.typeName" => "PIC 2"])
				->andWhere(["employee.status" => Employee::STATUS_CURRENT, "employee.branchId" => $_POST["id"]])
				->andWhere(["employee.teamId" => $teamId])
				->asArray()
				->groupBy('emt.employeeId')
				->orderBy('nickName')
				->all();
		}
		if (isset($team) && count($team) > 0) {
			foreach ($team as $t) :
				$textTeam .= '<option value="' . $t["teamId"] . '">' . $t["teamName"] . '</option>';
			endforeach;
		}


		if (isset($type) && count($type) > 0) {
			foreach ($type as $t) :
				$textPerson .= '<option value="' . $t["emId"] . '">' . $t["nickName"] . '</option>';
			endforeach;
		}
		$res["status"] = true;
		$res["textTeam"] = $textTeam;
		$res["person"] = $textPerson;

		return json_encode($res);
	}*/
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
		//throw new Exception(print_r($_POST["status"], true));
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
		];
		return $this->redirect(Yii::$app->homeUrl . 'job/detail/show-result/' . ModelMaster::encodeParams(["filter" => $dataIn]));
	}
	public function actionShowResult($hash)
	{
		$params = ModelMaster::decodeParams($hash);
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
		$showMaxminStep = '';
		$showMinmaxStep = '';
		$showMaxminFinal = '';
		$showMinmaxFinal = '';
		$employeeType = EmployeeType::findEmployeeType();
		$employeeBranchId = Employee::employeeBranch();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		$fag = 0;
		$status = '';
		$team = [];
		$teamId = Employee::employeeTeam();
		$person = [];
		$client = [];
		$onlyManager = 0;
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
		if ($fag == 1) { // GM SUPERVISOR MANAGER

			$query = Job::find()
				->where("job.status!=" . Job::STATUS_DELETED)
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->orderBy('c.clientName ASC');
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
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->where("job.status!=" . Job::STATUS_DELETED)
					->orderBy('c.clientName ASC');
			} else {
				$query = Job::find()
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->where("job.status!=" . Job::STATUS_DELETED)
					->andFilterWhere(["job.teamId" => $postTeamId])
					//->andWhere(["job.teamId" => $teamId])
					->orderBy('c.clientName ASC');
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
		$jobId = '';
		//throw new Exception(print_r($postStatus, true));
		//throw new Exception(count($postStatus));
		if (count($postStatus) > 0 && count($postStatus) != 4) {
			$jobId = Job::findJobIdByStatus2($postStatus, $branchId);
			//throw new Exception($jobId);
		}
		//throw new Exception($jobId);
		/*if ($postStatus == 9 || $postStatus == 10) {
			$status = Job::STATUS_INPROCESS;
			$jobId = Job::findJobIdByStatus($postStatus, $branchId);
		}*/
		/*if ($postStatus == 1) {
			$status = Job::STATUS_INPROCESS;
			$jobId = Job::findJobIdByStatus($postStatus, $branchId);
		}*/
		if ($fieldId == null && $postGroupFieldId != null) {

			$fieldId = SubFieldGroup::fieldName($postGroupFieldId);
			//throw new exception(print_r($fieldId, true));
		}
		if ($fieldId != null && $postGroupFieldId != null) {
			$fieldIdArr = SubFieldGroup::fieldName($postGroupFieldId);
			if (!in_array($fieldId, $fieldIdArr)) {
				//$fieldId = "";
				$postFieldId = "";
			}
		}
		if (isset($personId) && $personId != '') {
			$query = job::find()
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->where(["jr.employeeId" => $personId])
				->andWhere("job.status!=" . Job::STATUS_DELETED)
				->andFilterWhere([
					"job.branchId" => $branchId,
					"job.categoryId" => $categoryId,
					"job.fieldId" => $fieldId,
					"job.teamId" => $postTeamId,
					"job.clientId" => $postClientId,
					"job.jobTypeId" => $postJobTypeId
				])
				->orderBy("c.clientName  ASC");
		} else {
			$query->andFilterWhere([
				"job.branchId" => $branchId,
				"job.categoryId" => $categoryId,
				"job.fieldId" => $fieldId,
				"job.teamId" => $postTeamId,
				"job.clientId" => $postClientId,
				"job.jobTypeId" => $postJobTypeId
			])
				->orderBy("c.clientName  ASC");
		}
		if ($onlyManager == 1) {
			$query->andWhere(["job.branchId" => $employeeBranchId]);
		}
		if ($jobId != '') {
			$query->andWhere("job.jobId in ($jobId)");
		}
		/*if ($postStatus == 9 || $postStatus == 10) {
			if ($jobId != '') {
				$query->andWhere("job.jobId in ($jobId)")
					->andWhere("job.status=" . Job::STATUS_INPROCESS);
			} else {
				$query->andWhere(["job.jobId" => 0]);
				//->andWhere("job.status=" . Job::STATUS_INPROCESS);
			}
		} else {
			if ($postStatus == 1) {
				$query->andWhere("job.jobId not in($jobId)")
					->andFilterWhere(["job.status" => $status]);
			} else {
				$query->andFilterWhere(["job.status" => $status]);
			}
		}
		if ($postStatus == Job::STATUS_COMPLETE) {
			$query->andWhere(["job.status" => $postStatus]);
		}*/

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



		if ($postSortStep == 0) {
			$showMaxminStep = '';
			$showMinmaxStep = 'none';
		}
		if ($postSortStep == 1) {
			$showMaxminStep = '';
			$showMinmaxStep = 'none';
		}
		if ($postSortStep == 2) {
			$showMaxminStep = 'none';
			$showMinmaxStep = '';
		}
		if ($postSortFinal == 0) {
			$showMaxminFinal = '';
			$showMinmaxFinal = 'none';
		}
		if ($postSortFinal == 1) {
			$showMaxminFinal = '';
			$showMinmaxFinal = 'none';
		}
		if ($postSortFinal == 2) {
			$showMaxminFinal = 'none';
			$showMinmaxFinal = '';
		}

		return $this->render('search_result2', [
			"dataProviderJob" => $dataProviderJob,
			"branch" => $branch,
			"category" => $category,
			"dataProviderJob" => $dataProviderJob,
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
		]);
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
		JobCategory::deleteAll(["jobId" => $jobId, "status" => JobCategory::STATUS_WAITPROCESS]);
		$job = Job::find()
			->select('job.jobId,job.jobName,job.checkListPath,job.memo,job.branchId,job.clientId,job.teamId,job.fieldId,
			job.status,job.jobTypeId,c.clientName,t.teamName,b.branchName,jt.jobTypeName,job.p1Time,job.p2Time,
			job.fee,job.advanceReceivable,job.outSourcingFee,job.advancedChargeDate,job.feeChargeDate,
			job.estimateTime,job.startDate,job.url,job.currencyId,cu.name as currencyName,cu.code,cu.symbol')
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
					->select('job_category.jobCategoryId,job_category.fiscalYear,job_category.categoryId as jcategoryId,c.categoryName,job_category.startMonth,job_category.targetDate,job_category.status as jcStatus,c.totalRound')
					->JOIN("LEFT JOIN", "category c", "c.categoryId=job_category.categoryId")
					->where(["job_category.jobId" => $jobId])
					->orderBy('job_category.jobCategoryId DESC')
					->asArray()
					->one();
			} else {
				$category = JobCategory::find()
					->select('job_category.jobCategoryId,job_category.fiscalYear,job_category.categoryId as jcategoryId,c.categoryName,job_category.startMonth,job_category.targetDate,job_category.status as jcStatus,c.totalRound')
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
				->select('job_step.jobStepId,job_step.stepId as jStepId,s.stepName,job_step.dueDate as dueDate,job_step.status as jsStatus,job_step.jobCategoryId')
				->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
				->where(["job_step.jobId" => $jobId, "s.jobTypeId" => $job["jobTypeId"], "job_step.jobCategoryId" => $jobCate["jobCateId"]])
				->andWhere("s.status!=99")
				->orderBy('s.sort ASC,duedate ASC')
				->asArray()
				->all();
			if (isset($jobSteps) && count($jobSteps) > 0) { //depend on jobType
				foreach ($jobSteps as $step) :
					$jobStep[$step["jobStepId"]] = [
						"stepId" => $step["jStepId"],
						"stepName" => $step["stepName"],
						"dueDate" => $step["dueDate"],
						"status" => $step["jsStatus"],
						"isCancel" => LogCancel::isCancel($step["jobStepId"]),
						"history" => LogJobStep::hasLog($step["jobStepId"]),
						"additionalStep" => AdditionalStep::AdditionalJobStep($jobId, $step["jStepId"], $step["jobCategoryId"])
					];
					if ($step["jsStatus"] == JobStep::STATUS_COMPLETE) {
						$totalStepComplete++;
					}
				endforeach;
			}
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
				"jobTypes" => $jobTypes
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
		$jobs = JobStep::find()->select('s.stepName,jc.targetDate,jc.completeDate as tCompleteDate,job_step.dueDate,job_step.stepId,jc.jobCategoryId,job_step.status,job_step.completeDate,job_step.jobStepId')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobCategoryId=job_step.jobCategoryId")
			->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
			->where(["job_step.jobId" => $jobId])
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
				$alljobs[$j["jobCategoryId"]]["targetDate"] = ModelMaster::engDate($j["targetDate"], 2);
				$alljobs[$j["jobCategoryId"]]["tCompleteDate"] = $j["tCompleteDate"] != null ? ModelMaster::engDate($j["tCompleteDate"], 2) : '';
				$alljobs[$j["jobCategoryId"]][$j["stepId"]] = [
					"stepName" => $j["stepName"],
					"dueDate" => $j["dueDate"] != null ? ModelMaster::engDate($j["dueDate"], 2) : null,
					"status" => JobStep::statusText($j["status"]),
					"completeDate" => $j["completeDate"] != null ? ModelMaster::engDate($j["completeDate"], 2) : '',
					"history" => LogJobStep::hasLog($j["jobStepId"]),
					"jobStepId" => $j["jobStepId"],
					"additionalStep" => AdditionalStep::AdditionalJobStep($jobId, $j["stepId"], $j["jobCategoryId"])
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
			$firstDueDate = isset($_POST["dueDateFirstSet"]) ? $_POST["dueDateFirstSet"] : null;
			$subStepName = isset($_POST["additionalStep"]) ? $_POST["additionalStep"] : [];
			$subDueDate = isset($_POST["subDueDate"]) ? $_POST["subDueDate"] : [];
			$completeSubStep = isset($_POST["subComplete"]) ? $_POST["subComplete"] : [];
			$newSubName = isset($_POST["moreStep"]) ? $_POST["moreStep"] : [];
			$newDueDate = isset($_POST["subStepDueDate"]) ? $_POST["subStepDueDate"] : [];
			$newStartMonth = $_POST["currentMonth"];
			$fiscalYear = $_POST["fiscalYear"];
			$this->saveMoreSubStep($newSubName, $newDueDate, $jobId);
			$this->updateFirstJobStepDueDate($firstDueDate, $_POST["jcId"]);
			$this->updateJobStep($jobId, $dueDate, $completeStep, $_POST["jcId"]);
			$this->updateJobSubStep($subDueDate, $completeSubStep, $subStepName);
			$this->updateJobCategory($jobId, $_POST["targetDate"], $_POST["jcId"], $completeTarget, $newStartMonth, $fiscalYear);
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
			$job->save(false);
			$this->sendJobEmail($jobId);
		}
		return $this->redirect('index');
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
	public function updateFirstJobStepDueDate($dueDate, $jocategoId)
	{
		$jobStep = JobStep::find()->where(["jobCategoryId" => $jocategoId])->all();
		$save = 0;
		if (isset($jobStep) && count($jobStep) > 0) {
			foreach ($jobStep as $step) :
				if (isset($dueDate[$step->jobStepId]) && $dueDate != null) {
					$step->firstDueDate = $dueDate[$step->jobStepId];
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
					LogSubStep::saveChangeSubDueDate($additionalStepId, $oldDueDate, $newDueDate);
				}
				$addition->save(false);
			}
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

	public function updateApprover($approverId, $jobId, $jobCategoryId, $jobStepId)
	{
		//$type = Type::find()->select('typeId')->where(["typeName" => 'Approver'])->asArray()->one();
		//if (isset($type) && !empty($type)) {
		$typeId = JobResponsibility::APPROVER;
		$res = JobResponsibility::find()->where(["jobId" => $jobId, "responsibility" => $typeId])->one();
		if ($approverId != $res->employeeId) {
			JobResponsibility::saveLogApprover($jobId, $approverId, $jobCategoryId, $jobStepId);
			$res->employeeId = $approverId;
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
				$employee = Employee::find()->select('email,employeeNickName')->where(["employeeId" => $mail["userId"]])->asArray()->one();
				$job = Job::find()
					->select('job.jobName,job.status as jstatus,c.clientName,b.branchName,job.jobTypeId,job.categoryId')
					->JOIN("LEFT JOIN", "branch b", "b.branchId=job.branchId")
					->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
					->where(["job.jobId" => $jobId])
					->asArray()
					->one();
				if (isset($job) && !empty($job)) {
					$data["nickName"] = $employee["employeeNickName"];
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
					if ($catagory["categoryName"] == "Quaterly") {
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
		if ($catagory["categoryName"] == "Quaterly") {
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
			->asArray()
			->all();
		$sub = [];
		$subSteps = AdditionalStep::find()->where(["jobId" => $currentJobCategory["jobId"]])->asArray()->orderBy('sort')->all();
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
			"fiscalYear" => $nextFiscalYear
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
			Job::updateAll(["status" => Job::STATUS_INPROCESS], ["jobId" => $jobId]);
			$jobCategory = JobCategory::find()->where(["jobCategoryId" => $nextJobCategoryId])->one();
			$jobCategory->targetDate = $_POST["newTargetDate"];
			$jobCategory->status = JobCategory::STATUS_INPROCESS;
			$jobCategory->startMonth = $currentMonth + 1;
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
				return $this->redirect(Yii::$app->homeUrl . 'job/detail/job-detail/' . ModelMaster::encodeParams(["jobId" => $_POST["jId"]]));
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
				$jobCategory->status = JobCategory::STATUS_INPROCESS;
				$jobCategory->save(false);
			}
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
	public function actionDeleteJob()
	{
		$jobId = $_POST["jobId"];
		$job = Job::find()->where(["jobId" => $jobId])->one();
		$job->status = Job::STATUS_DELETED;
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
			->orderBy("createDateTime")
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
				$steps = Step::find()->where(["jobTypeId" => $jobToypeId])->all();
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
							$addStep->updateDateTime = new expression('NOW');
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
}
