<?php

namespace frontend\modules\kpi\controllers;

use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi1;
use frontend\models\lower_management\Kgi1HasKgi2;
use frontend\models\lower_management\Kgi2;
use frontend\models\lower_management\Kgi2Team;
use frontend\models\lower_management\Kpi;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

class Kgi2Controller extends Controller
{
	public function actionIndex()
	{
		$data = [];
		$kgi2 = Kgi2::find()->where(["status" => 1])->orderBy('createDateTime DESC')->asArray()->all();
		if (isset($kgi2) && count($kgi2) > 0) {
			foreach ($kgi2 as $kgi) :
				$data[$kgi["kgi2Id"]] = [
					"branch" => Branch::branchName($kgi["branchId"]),
					"content" => $kgi["kgi2Name"],
					"team" => Kgi2::dreamTeam($kgi["kgi2Id"]),
					"position" => Kgi2::dreamTeamPosition($kgi["kgi2Id"]),
					"code" => "-",
					"targetAmount" => "-",
					"actualAmount" => "-",
					"achiveRatio" => "-",
					"main" => Kgi2::mainKgi1($kgi["kgi2Id"]),
					"kgi1" => Kgi2::countKgi1($kgi["kgi2Id"]),
					"kpi" => Kgi2::AllKpi($kgi["kgi2Id"])
				];
			endforeach;
		}
		//throw new Exception(print_r($data, true));
		return $this->render('index', [
			"kgi2" => $kgi2,
			"data" => $data
		]);
	}
	public function actionCreateKgi2()
	{
		if (isset($_POST["kgi2Name"]) && trim($_POST["kgi2Name"]) != '') {
			$kgi2 = new Kgi2();
			$kgi2->branchId = $_POST["branch"];
			$kgi2->kgi2Name = $_POST["kgi2Name"];
			$kgi2->status = 1;
			$kgi2->detail = $_POST["detail"];
			$kgi2->createDateTime = new Expression('NOW()');
			$kgi2->updateDateTime = new Expression('NOW()');
			if ($kgi2->save(false)) {
				$kig2Id = Yii::$app->db->lastInsertID;
				if (isset($_POST['kgi1']) && count($_POST['kgi1']) > 0) {
					$kgiMain = new Kgi1HasKgi2();
					$kgiMain->kgi2Id = $kig2Id;
					$kgiMain->kgi1Id = $_POST["mainKgi"];
					$kgiMain->isMain = 1;
					$kgiMain->status = 1;
					$kgiMain->createDateTime = new Expression('NOW()');
					$kgiMain->updateDateTime = new Expression('NOW()');
					$kgiMain->save(false);
					foreach ($_POST['kgi1'] as $kgi1) :
						if ($kgi1 != $_POST["mainKgi"]) {
							$kgi12 = new Kgi1HasKgi2();
							$kgi12->kgi2Id = $kig2Id;
							$kgi12->kgi1Id = $kgi1;
							$kgi12->status = 1;
							$kgi12->isMain = 0;
							$kgi12->createDateTime = new Expression('NOW()');
							$kgi12->updateDateTime = new Expression('NOW()');
							$kgi12->save(false);
						}
					endforeach;
				}
				if (isset($_POST['team']) && count($_POST['team']) > 0) {
					foreach ($_POST['team'] as $teamId) :
						if (isset($_POST["position"]) && count($_POST["position"]) > 0) {
							foreach ($_POST["position"] as $postionId) :
								$kgi2Team = new Kgi2Team();
								$kgi2Team->kgi2Id = $kig2Id;
								$kgi2Team->teamId = $teamId;
								$kgi2Team->teamPositionId = $postionId;
								$kgi2Team->createDateTime = new Expression('NOW()');
								$kgi2Team->updateDateTime = new Expression('NOW()');
								$kgi2Team->save(false);
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

		return $this->render('create_kgi2', [
			"branch" => $branch,
		]);
	}
	public function actionBranchKgi()
	{
		$branchId = $_POST["branchId"];
		$option = '';
		$teamList = '';
		$positionList = '';
		$res = [];
		$mainKgi = '<option value="">Main KGI 1</option>';
		$kgi1 = Kgi1::find()->where(["status" => 1, "branchId" => $branchId])->asArray()->all();
		if (isset($kgi1) && count($kgi1) > 0) {
			foreach ($kgi1 as $kgi) :
				$option .= "<option value='" . $kgi['kgi1Id'] . "'>" . $kgi['kgi1Name'] . "</option>";
				$mainKgi .= "<option value='" . $kgi['kgi1Id'] . "'>" . $kgi['kgi1Name'] . "</option>";
			endforeach;
		}
		$teams = Team::find()->where(["status" => 1, "branchId" => $branchId])->asArray()->all();
		if (isset($teams) && count($teams) > 0) {
			foreach ($teams as $team) :
				$teamList .= "<div class='col-12 mt-10'><input type='checkbox' class='checkbox-sm' name='team[]' value='" . $team['teamId'] . "'> " . $team['teamName'] . "</div>";
			endforeach;
		}
		$positions = TeamPosition::find()->where(["status" => 1])->asArray()->all();
		if (isset($positions) && count($positions) > 0) {
			foreach ($positions as $position) :
				$positionList .= "<div class='col-12 mt-10'><input type='checkbox' class='checkbox-sm' name='position[]' value='" . $position['id'] . "'> " . $position['name'] . "</div>";
			endforeach;
		}
		if ($option != '') {
			$res["kgi1"] = $option;
		} else {
			$res["kgi1"] = '';
		}
		if ($teamList != '') {
			$res["teamList"] = $teamList;
		} else {
			$res["teamList"] = '';
		}
		if ($positionList != '') {
			$res["positionList"] = $positionList;
		} else {
			$res["positionList"] = '';
		}
		$res["mainKgi"] = $mainKgi;
		return json_encode($res);
	}
}
