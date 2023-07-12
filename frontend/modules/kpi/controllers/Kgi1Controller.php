<?php

namespace frontend\modules\kpi\controllers;

use common\models\ModelMaster;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Kgi1;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

class Kgi1Controller extends Controller
{
	public function actionIndex()
	{
		$kgi1 = Kgi1::find()->where(["status" => 1])->orderBy('createDateTime DESC')->asArray()->all();
		return $this->render('index', [
			"kgi1" => $kgi1
		]);
	}
	public function actionCreateKgi1()
	{
		if (isset($_POST["kgi1Name"]) && trim($_POST["kgi1Name"]) != '') {
			$kgi1 = new Kgi1();
			$kgi1->branchId = $_POST["branch"];
			$kgi1->kgi1Name = $_POST["kgi1Name"];
			$kgi1->targetAmount = $_POST["targetAmount"];
			$kgi1->code = $_POST["code"];
			$kgi1->status = 1;
			$kgi1->detail = $_POST["detail"];
			$kgi1->amountType = $_POST["amountType"];
			$kgi1->createDateTime = new Expression('NOW()');
			$kgi1->updateDateTime = new Expression('NOW()');
			if ($kgi1->save(false)) {
				return $this->redirect('index');
			}
		}
		$branch = Branch::find()
			->select('branchName,branchId')
			->where(["status" => 1])
			->asArray()
			->orderBy('branchName')
			->all();

		return $this->render('create_kgi1', [
			"branch" => $branch,
		]);
	}
	public function actionUpdateKgi1($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$kgi1Id = $param["kgi1Id"];
		$kgi1 = Kgi1::find()->where(["kgi1Id" => $kgi1Id])->asArray()->one();
		$branch = Branch::find()
			->select('branchName,branchId')
			->where(["status" => 1])
			->asArray()
			->orderBy('branchName')
			->all();

		return $this->render('update_kgi1', [
			"branch" => $branch,
			"kgi1" => $kgi1
		]);
	}
	public function actionSaveUpdateKgi1()
	{
		if (isset($_POST["kgi1Id"])) {
			$kgi1Id = $_POST["kgi1Id"];
			$kgi1 = Kgi1::find()->where(["kgi1Id" => $kgi1Id])->one();
			$kgi1->branchId = $_POST["branch"];
			$kgi1->kgi1Name = $_POST["kgi1Name"];
			$kgi1->targetAmount = $_POST["targetAmount"];
			$kgi1->code = $_POST["code"];
			$kgi1->detail = $_POST["detail"];
			$kgi1->status = 1;
			$kgi1->amountType = $_POST["amountType"];
			$kgi1->updateDateTime = new Expression('NOW()');
			if ($kgi1->save(false)) {
				return $this->redirect('index');
			}
		}
	}
	public function actionDisableKgi1()
	{
		$res["status"] = false;
		if (isset($_POST["kgi1Id"])) {
			$kgi1Id = $_POST["kgi1Id"];
			$kgi1 = Kgi1::find()->where(["kgi1Id" => $kgi1Id])->one();
			$kgi1->status = 99;
			if ($kgi1->save(false)) {
				$res["status"] = true;
			}
		}
		return json_encode($res);
	}
}
