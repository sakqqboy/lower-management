<?php

namespace frontend\modules\kpi\controllers;

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Kpi;
use frontend\models\lower_management\PersonalKgi;
use frontend\models\lower_management\PersonalKpi;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use frontend\models\lower_management\Type;
use Yii;
use yii\web\Controller;

class EmployeeKpiController extends Controller
{
	public function actionEmployee()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_GM;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranch = Employee::employeeBranch();
		if ($isAdmin == 1) {
			$employee = Employee::find()
				->where(["status" => Employee::STATUS_CURRENT])
				->orderBy("status,employeeFirstName ASC")
				->limit(100)
				->asArray()
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
		} else {
			$employee = Employee::find()
				->where(["status" => Employee::STATUS_CURRENT, "branchId" => $employeeBranch])
				->orderBy("status,employeeFirstName ASC")
				->asArray()
				->all();
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranch])
				->asArray()
				->all();
		}
		$userType = Type::find()->select('typeId,typeName')
			->where(["status" => Type::STATUS_ACTIVE])
			->asArray()
			->all();
		$teamPosition = TeamPosition::find()->select('name,id')->where(["status" => 1])->all();

		return $this->render('employee', [
			"employee" => $employee,
			"branch" => $branch,
			"userType" => $userType,
			"teamPosition" => $teamPosition
		]);
	}
	public function actionSearchEmployee()
	{
		$branchId = $_POST["branchId"];
		$sectionId = $_POST["sectionId"];
		$positionId = $_POST["positionId"];
		$teamId = $_POST["teamId"];
		$teamPositionId = $_POST["teamPositionId"];
		$userTypeId = $_POST["userTypeId"];
		return $this->redirect(Yii::$app->homeUrl . 'kpi/employee-kpi/search-result/' . ModelMaster::encodeParams([
			"branchId" => $branchId,
			"sectionId" => $sectionId,
			"positionId" => $positionId,
			"teamId" => $teamId,
			"teamPositionId" => $teamPositionId,
			"userTypeId" => $userTypeId,

		]));
	}
	public function actionSearchResult($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranch = Employee::employeeBranch();
		$section = [];
		$position = [];
		$team = [];
		if ($param["branchId"] != '') {
			$employee = Employee::find()
				->JOIN("LEFT JOIN", "employee_type et", "employee.employeeId=et.employeeId")
				->where(["employee.status" => 1])
				->andFilterWhere(["et.typeId" => $param["userTypeId"]])
				->andFilterWhere(["employee.branchId" => $param["branchId"]])
				->andFilterWhere(["employee.sectionId" => $param["sectionId"]])
				->andFilterWhere(["employee.positionId" => $param["positionId"]])
				->andFilterWhere(["employee.teamId" => $param["teamId"]])
				->andFilterWhere(["employee.teamPositionId" => $param["teamPositionId"]])
				->orderBy("employee.status,employee.employeeFirstName ASC")
				->asArray()
				->all();
			$section = Section::find()->select('sectionId,sectionName')
				->where(["branchId" => $param["branchId"], "status" => 1])
				->asArray()
				->all();
			if (isset($param["sectionId"]) && $param["sectionId"] != '') {
				$position = Position::find()
					->select('position.positionId,position.positionName')
					->JOIN("LEFT JOIN", "section_has_position sp", "sp.positionId=position.positionId")
					->where([
						"position.branchId" => $param["branchId"],
						"sp.sectionId" => $param["sectionId"],
						"position.status" => 1
					])->all();
			} else {
				$position = Position::find()
					->select('positionId,positionName')
					->where([
						"branchId" => $param["branchId"],
						"status" => 1
					])->all();
			}
			$team = Team::find()->select('teamId,teamName')
				->where(["branchId" => $param["branchId"], "status" => 1])
				->asArray()
				->all();
		} else {

			if ($isAdmin == 1) {
				$employee = Employee::find()
					->where(["status" => Employee::STATUS_CURRENT])
					->orderBy("status,employeeFirstName ASC")
					->limit(100)
					->asArray()
					->all();
			} else {
				$employee = Employee::find()
					->where(["status" => Employee::STATUS_CURRENT, "branchId" => $employeeBranch])
					->orderBy("status,employeeFirstName ASC")
					->asArray()
					->all();
			}
		}
		if ($isAdmin == 1) {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
		} else {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranch])
				->asArray()
				->all();
		}
		$userType = Type::find()->select('typeId,typeName')
			->where(["status" => Type::STATUS_ACTIVE])
			->asArray()
			->all();
		$teamPosition = TeamPosition::find()->select('name,id')->where(["status" => 1])->all();
		return $this->render('result', [
			"employee" => $employee,
			"branch" => $branch,
			"userType" => $userType,
			"teamPosition" => $teamPosition,
			"team" => $team,
			"position" => $position,
			"section" => $section,
			"branchId" => $param["branchId"],
			"sectionId" => $param["sectionId"],
			"positionId" => $param["positionId"],
			"teamId" => $param["teamId"],
			"teamPositionId" => $param["teamPositionId"],
			"userTypeId" => $param["userTypeId"],
		]);
	}
	public function actionEmployeeKpi($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$employeeId = $param["employeeId"];
		$employeeName = Employee::employeeName($employeeId);
		$kpi = Kpi::personalKpi($employeeId);
		return $this->render('personal_kpi', [
			"kpi" => $kpi,
			"employeeName" => $employeeName
		]);
	}
	public function actionUpdatePersonalKpi()
	{
		$pkpiId = $_POST["pkpiId"];
		$amount = $_POST["amount"];
		$personalKpi = PersonalKpi::find()->where(["personalKpiId" => $pkpiId])->one();
		$personalKpi->personalTargetAmount = $amount;
		$personalKpi->save(false);
		$res["status"] = true;
		return json_encode($res);
	}
}
