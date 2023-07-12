<?php

namespace frontend\modules\job\controllers;

use common\carlendar\Carlendar;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\Type;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\debug\models\timeline\DataProvider;
use yii\web\Controller;

/**
 * Default controller for the `job` module
 */
class CarlendarController extends Controller
{

	public function actionIndex()
	{
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$date = date('Y-m-d');
		$dateValue = Carlendar::currentMonth($date);
		$selectMonth = date('m');
		$selectDate = ModelMaster::engDate($date, 1);
		$jobStatus[Job::STATUS_INPROCESS] = "Inprocess";
		$jobStatus[Job::STATUS_COMPLETE] = "Complete";

		$employeeType = EmployeeType::findEmployeeType();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		$fag = 0;
		$teamId = Employee::employeeTeam();
		$teams = [];
		$persons = [];
		$branchId = "";
		if (count($employeeType) > 0) {
			foreach ($employeeType as $all) :
				if (in_array($all, $rightAll)) {
					$fag = 1;
				}
			endforeach;
		}
		if ($fag == 1) { // management
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
			$fields = Field::find()
				->select('fieldName,fieldId')
				->where(["status" => Field::STATUS_ACTIVE])
				->asArray()
				->orderBy('fieldName ASC')
				->all();
		} else {
			$branchId = Employee::employeeBranch();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $branchId])
				->asArray()
				->all();
			$teams = Team::find()->select('teamId,teamName')
				->where(["branchId" => $branchId, "status" => 1])
				->asarray()
				->all();
			$fields = Field::find()
				->select('fieldName,fieldId')
				->where(["status" => Field::STATUS_ACTIVE, "branchId" => $branchId])
				->asArray()
				->orderBy('fieldName ASC')
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
		$category = Category::find()->select('categoryName,categoryId')
			->where(["status" => Category::STATUS_ACTIVE])
			->asArray()
			->all();


		return $this->render('index', [
			"dateValue" => $dateValue,
			"selectMonth" => $selectMonth,
			"selectDate" => $selectDate,
			"jobStatus" => $jobStatus,
			"branch" => $branch,
			"category" => $category,
			"fields" => $fields,
			"branchId" => $branchId,
			"teams" => $teams,
			"teamId" => $teamId,
			"persons" => $persons
		]);
	}
	public function actionTargetMonth()
	{
		$text = "";
		$year = $_POST["year"];
		$month = $_POST["month"];
		$day = date('d');
		if ($month < 10) {
			$month = "0" . $month;
		}
		$date = $year . "-" . $month . "-" . $day;
		//$date = date('Y-m-d');
		$dateValue = Carlendar::currentMonth($date);
		$selectMonth = $month;
		$selectDate = ModelMaster::engDate($date, 1);
		$text = $this->renderAjax('search_target', [
			"dateValue" => $dateValue,
			"selectMonth" => $selectMonth,
			"selectDate" => $selectDate
		]);
		if ($text == "") {
			$res["status"] = false;
		} else {
			$res["target"] = $text;
			$res["selectDate"] = $selectDate;
			$res["status"] = true;
		}
		return json_encode($res);
	}
	public function actionSearchJobCarlendar()
	{
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$dataIn = [
			"branchId" => $_POST["branchId"],
			"categoryId" => $_POST["categoryId"],
			"fieldId" => $_POST["fieldId"],
			"teamId" => $_POST["teamId"],
			"status" => $_POST["status"],
			"personId" => $_POST["personId"],
			"year" => $_POST["year"],
			"month" => $_POST["month"],
			"stepCheck" => $_POST["stepCheck"],
			"finalCheck" => $_POST["finalCheck"],
		];
		return $this->redirect(Yii::$app->homeUrl . 'job/carlendar/show-result/' . ModelMaster::encodeParams(["filter" => $dataIn]));
	}
	public function actionShowResult($hash)
	{
		$params = ModelMaster::decodeParams($hash);
		$branchId = $params["filter"]["branchId"];
		$categoryId = $params["filter"]["categoryId"];
		$fieldId = $params["filter"]["fieldId"];
		$personId = $params["filter"]["personId"];
		$postTeamId = $params["filter"]["teamId"];
		$postStatus = $params["filter"]["status"];
		$postYear = $params["filter"]["year"];
		$postMonth = $params["filter"]["month"];
		$employeeType = EmployeeType::findEmployeeType();
		$year = $params["filter"]["year"];
		$month = $params["filter"]["month"];
		$filter = [];
		$filter["branchId"] = $branchId;
		$filter["fieldId"] = $fieldId;
		$filter["categoryId"] = $categoryId;
		$filter["teamId"] = $postTeamId;
		$filter["personId"] = $personId;
		$filter["status"] = $postStatus;
		$stepCheck =  $params["filter"]["stepCheck"];
		$finalCheck = $params["filter"]["finalCheck"];
		$day = date('d');
		if ($month < 10) {
			$month = "0" . $month;
		}
		$date = $year . "-" . $month . "-" . $day;
		$teamId = Employee::employeeTeam();
		$dateValue = Carlendar::currentMonth($date);
		$selectMonth = $month;
		$selectDate = ModelMaster::engDate($date, 1);

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
		$team = [];
		$teamId = Employee::employeeTeam();
		$fag = 0;
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
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
		if ($fag == 1) { //MANAGE GM ADMIN
			$team = Team::find()->select('teamId,teamName')
				->where(["branchId" => $branchId, "status" => Team::STATUS_ACTIVE])
				->asArray()
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
			if ($postTeamId != '') {
				$persons = Employee::find()
					->select('employeeNickName as nickName,employeeId as emId')
					->where(["status" => Employee::STATUS_CURRENT, "teamId" => $postTeamId])
					->orderBy('nickName')
					->asArray()
					->all();
			} else {
				$persons = Employee::find()
					->select('.employeeNickName as nickName,employeeId as emId')
					->where(["status" => Employee::STATUS_CURRENT])
					->andFilterWhere(["branchId" => $branchId])
					->orderBy('nickName')
					->asArray()
					->all();
			}
		} else {
			$employeeBranchId = Employee::employeeBranch();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranchId])
				->asArray()
				->all();
			if ($onlyManager == 1) {
				$team = Team::find()->select('teamId,teamName')
					->where(["status" => Team::STATUS_ACTIVE])
					->andFilterWhere(["branchId" => $branchId])
					->asArray()
					->all();
			} else {
				$team = Team::find()->select('teamId,teamName')
					->where(["branchId" => $employeeBranchId, "status" => Team::STATUS_ACTIVE])
					->asArray()
					->all();
			}
			$persons = Employee::find()
				->select('employeeNickName as nickName,employeeId as emId')
				->andWhere(["status" => Employee::STATUS_CURRENT])
				->andWhere(["teamId" => $postTeamId])
				->andFilterWhere(["branchId" => $branchId])
				->orderBy('nickName')
				->asArray()
				->all();
		}
		return $this->render('search_result2', [
			"dateValue" => $dateValue,
			"selectMonth" => $selectMonth,
			"selectDate" => $selectDate,
			"filter" => $filter,
			"stepCheck" => $stepCheck,
			"finalCheck" => $finalCheck,
			"branch" => $branch,
			"category" => $category,
			"fields" => $fields,
			"branchId" => $branchId,
			"categoryId" => $categoryId,
			"fieldId" => $fieldId,
			"personId" => $personId,
			"postTeamId" => $postTeamId,
			"postStatus" => $postStatus,
			"team" => $team,
			"persons" => $persons,
			"postYear" => $postYear,
			"postMonth" => $postMonth
		]);
	}
	public function actionSearchJobDate()
	{
		$text = '';
		$filter["branchId"] = $_POST["branchId"];
		$filter["fieldId"] = $_POST["fieldId"];
		$filter["categoryId"] = $_POST["categoryId"];
		$filter["teamId"] = $_POST["teamId"];
		$filter["personId"] = $_POST["personId"];
		$filter["status"] = $_POST["status"];
		$stepCheck =  $_POST["stepCheck"];
		$finalCheck =  $_POST["finalCheck"];
		$jobDate = $_POST["jobDate"];
		return $this->redirect(Yii::$app->homeUrl . 'job/carlendar/job-date/' . ModelMaster::encodeParams([
			"filter" => $filter,
			"stepCheck" => $stepCheck,
			"finalCheck" => $finalCheck,
			"jobDate" => $jobDate

		]));
	}
	public function actionJobDate($hash)
	{
		$params = ModelMaster::decodeParams($hash);
		$filter["branchId"] = $params["filter"]["branchId"];
		$filter["fieldId"] = $params["filter"]["fieldId"];
		$filter["categoryId"] = $params["filter"]["categoryId"];
		$filter["teamId"] = $params["filter"]["teamId"];
		$filter["personId"] = $params["filter"]["personId"];
		$filter["status"] = $params["filter"]["status"];
		$stepCheck = $params["stepCheck"];
		$finalCheck = $params["finalCheck"];
		$jobDate = $params["jobDate"];
		$jobs = Job::getDateJobsFilter($jobDate, $filter, $stepCheck, $finalCheck);
		$date = ModelMaster::engDate($jobDate, 1);
		return $this->render('date_job', [
			"jobs" => $jobs,
			"date" => $date
		]);
	}
}
