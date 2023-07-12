<?php

namespace frontend\modules\kpi\controllers;

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Kgi2HasKpi;
use frontend\models\lower_management\Kpi;
use frontend\models\lower_management\KpiTeam;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\Type;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

class KpiController extends Controller
{
	public function actionIndex()
	{
		$kpi = Kpi::find()->where(["status" => 1])->orderBy("createDateTime DESC")->asArray()->all();
		return $this->render('index', [
			"kpi" => $kpi
		]);
	}
	public function actionCreateKpi()
	{
		if (isset($_POST["kpiName"]) && trim($_POST["kpiName"]) != '') {
			$kpi = new Kpi();
			$kpi->branchId = $_POST["branch"];
			$kpi->kpiName = $_POST["kpiName"];
			$kpi->kpiDetail = $_POST["kpiDetail"];
			$kpi->status = 1;
			$kpi->createDateTime = new Expression('NOW()');
			$kpi->updateDateTime = new Expression('NOW()');
			if ($kpi->save(false)) {
				$kpiId = Yii::$app->db->lastInsertID;
				if (isset($_POST['kgi2']) && count($_POST['kgi2']) > 0) {
					foreach ($_POST['kgi2'] as $kgi2Id) :
						$kgiKpi = new Kgi2HasKpi();
						$kgiKpi->kgi2Id = $kgi2Id;
						$kgiKpi->kpiId = $kpiId;
						$kgiKpi->status = 1;
						$kgiKpi->createDateTime = new Expression('NOW()');
						$kgiKpi->updateDateTime = new Expression('NOW()');
						$kgiKpi->save(false);
					endforeach;
				}
				if (isset($_POST['team']) && count($_POST['team']) > 0) {
					foreach ($_POST['team'] as $teamId) :
						if (isset($_POST["position"]) && count($_POST["position"]) > 0) {
							foreach ($_POST["position"] as $postionId) :
								$kpiTeam = new KpiTeam();
								$kpiTeam->kpiId = $kpiId;
								$kpiTeam->teamId = $teamId;
								$kpiTeam->teamPositionId = $postionId;
								$kpiTeam->createDateTime = new Expression('NOW()');
								$kpiTeam->updateDateTime = new Expression('NOW()');
								$kpiTeam->save(false);
							endforeach;
						}
					endforeach;
				}
				return $this->redirect('index');
			}
		}
		$branch = Branch::find()
			->select('branchName,branchId')
			->where(["status" => 1])
			->asArray()
			->orderBy('branchName')
			->all();
		return $this->render('create_kpi', ["branch" => $branch]);
	}
	public function actionKpiProgress($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$kpiId = $param["kpiId"];
		$kpi = Kpi::find()->where(["kpiId" => $kpiId])->one();
		if (isset($param['year'])) {
			$year = $param['year'];
		} else {
			$year = date('Y');
		}
		$currentYear = date('Y');
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
		if ($fag == 1) { //admin / gm
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => 1])
				->orderBy('branchName')
				->asArray()
				->all();
		} else {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => 1, "branchId" => $branchId])
				->orderBy('branchName')
				->asArray()
				->all();
		}
		return $this->render('update_kpi_progress', [
			"kpiiId" => $kpiId,
			"kpi" => $kpi,
			"year" => $year,
			"currentYear" => $currentYear,
			"branch" => $branch
		]);
	}
	public function actionBranchKgi()
	{
		$branchId = $_POST["branchId"];

		$teamList = '';

		$res = [];
		$teamList = '<option value="">Team</option>';


		$teams = Team::find()->where(["status" => 1, "branchId" => $branchId])->asArray()->all();
		if (isset($teams) && count($teams) > 0) {
			foreach ($teams as $team) :
				$teamList .= '<option value="' . $team["teamId"] . '">' . $team["teamName"] . '</option>';
			endforeach;
		}


		if ($teamList != '') {
			$res["teamList"] = $teamList;
		} else {
			$res["teamList"] = '';
		}
		return json_encode($res);
	}
	public function actionYearKpi()
	{
		$year = $_POST["year"];
		if ($year == '') {
			$year = date('Y');
		}
		return $this->redirect(Yii::$app->homeUrl . '');
	}
}
