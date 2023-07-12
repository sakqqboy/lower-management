<?php

namespace frontend\modules\kpi\controllers;

use common\carlendar\Carlendar;
use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Kpi;
use frontend\models\lower_management\PersonalKgi;
use frontend\models\lower_management\PersonalKpi;
use frontend\models\lower_management\PersonalKpiDetail;
use Yii;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\UploadedFile;

class UpdateController extends Controller
{
	public function actionPersonalUpdate($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$pkpiId = $param["pkpiId"];
		$date = date('Y-m-d');
		$dateValue = Carlendar::currentMonth($date);
		$selectMonth = date('m');
		$selectDate = ModelMaster::engDate($date, 1);
		$pkpiId = $param["pkpiId"];
		$year = (int)date('Y');
		$month = (int)date('m');
		$pkpi = PersonalKpi::find()->where(["personalKpiId" => $pkpiId])->asArray()->one();
		$kpi = Kpi::find()->where(["kpiId" => $pkpi["kpiId"]])->asArray()->one();
		return $this->render('update_form', [
			"selectDate" => $selectDate,
			"selectMonth" => $selectMonth,
			"yearKpi" => $year,
			"monthKpi" => $month,
			"dateValue" => $dateValue,
			"pkpi" => $pkpi,
			"kpi" => $kpi

		]);
	}
	public function actionSearchKpiCarlendar()
	{
		//throw new Exception($_POST["month"]);
		return $this->redirect(Yii::$app->homeUrl . 'kpi/update/show-carlendar/' . ModelMaster::encodeParams([
			"year" => $_POST["year"],
			"month" => $_POST["month"],
			"kpiId" => $_POST["kpiId"],
			"pkpiId" => $_POST["pkpiId"],
		]));
	}
	public function actionShowCarlendar($hash)
	{
		$params = ModelMaster::decodeParams($hash);
		$year = $params["year"];
		$month = $params["month"];

		$pkpiId = $params["pkpiId"];
		$kpiId = $params["kpiId"];
		$day = date('d');
		if ($month < 10) {
			$month = "0" . $month;
		}

		$date = $year . "-" . $month . "-" . $day;
		$dateValue = Carlendar::currentMonth($date);
		$selectMonth = $month;
		$selectDate = ModelMaster::engDate($date, 1);
		$pkpi = PersonalKpi::find()->where(["personalKpiId" => $pkpiId])->asArray()->one();
		$kpi = Kpi::find()->where(["kpiId" => $kpiId])->asArray()->one();
		//throw new Exception($month);
		return $this->render('update_form', [
			"dateValue" => $dateValue,
			"selectMonth" => $selectMonth,
			"yearKpi" => (int)$year,
			"monthKpi" => (int)$month,
			"selectDate" => $selectDate,
			"pkpi" => $pkpi,
			"kpi" => $kpi
		]);
	}
	public function actionSaveUpdate()
	{
		if (isset($_POST["pkpiId"])) {
			$update = new PersonalKpiDetail();
			$update->pkpiId = $_POST["pkpiId"];
			$update->kpiId = $_POST["kpiId"];
			$update->day = $_POST["select-day-kpi"];
			$update->month = $_POST["select-month-kpi"];
			$update->year = $_POST["select-year-kpi"];
			$update->amount = $_POST["amount"];
			$update->detail = $_POST["personalDetail"];
			$update->status = 1;
			$update->createDateTime = new Expression('NOW()');
			$update->updateDateTime = new Expression('NOW()');
			$imageObj = UploadedFile::getInstanceByName("kpiFile");
			if (isset($imageObj) && !empty($imageObj)) {
				$urlFolder = Path::getHost() . 'file/kpi/';
				if (!file_exists($urlFolder)) {
					mkdir($urlFolder, 0777, true);
				}
				$file = $imageObj->name;
				$filenameArray = explode('.', $file);
				$countArrayFile = count($filenameArray);
				$fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[$countArrayFile - 1];
				$pathSave = $urlFolder . $fileName;
				if ($imageObj->saveAs($pathSave)) {
					$update->file = 'file/kpi/' . $fileName;
				}
			}
			if ($update->save(false)) {
				return $this->redirect(Yii::$app->homeUrl . 'kpi/update/personal-update/' . ModelMaster::encodeParams(["pkpiId" => $_POST["pkpiId"]]));
			}
		}
	}
	public function actionProgressDetail()
	{
		$year = $_POST["year"];
		$month = $_POST["month"];
		$day = $_POST["day"];
		$pkpiDetailId = $_POST["pkpiDetailId"];
		$res = [];
		$personalKpiDetail = PersonalKpiDetail::find()
			->select('personal_kpi_detail.amount,personal_kpi_detail.personalKpiDetailId,
			kpi.amountType,personal_kpi_detail.detail,personal_kpi_detail.file,kpi.kpiName')
			->JOIN("LEFT JOIN", "personal_kpi pk", "pk.personalKpiId=personal_kpi_detail.pkpiId")
			->JOIN("LEFT JOIN", "kpi", "kpi.kpiId=pk.kpiId")
			->where([
				"personal_kpi_detail.year" => $year,
				"personal_kpi_detail.month" => $month,
				"personal_kpi_detail.day" => $day,
				"pk.employeeId" => Yii::$app->user->id,
				"personal_kpi_detail.personalKpiDetailId" => $pkpiDetailId
			])
			->asArray()
			->one();
		$res["amountType"] = $personalKpiDetail["amountType"] == 1 ? '' : '%';
		$res["amount"] = $personalKpiDetail["amount"];
		$res["detail"] = $personalKpiDetail["detail"];
		$fileName = '';
		if ($personalKpiDetail["file"] != null) {
			$fileNameArr = explode('.', $personalKpiDetail["file"]);

			$fileName = $personalKpiDetail["kpiName"] . '.' . $fileNameArr[1];
		}
		$res["link"] = 'Evidence File : <a href="' . Yii::$app->homeUrl . $personalKpiDetail["file"] . '">' . $fileName . '</a>';
		$res["file"] = $personalKpiDetail["file"];
		$res["fileName"] = $fileName;
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionSaveUpdateProgress()
	{
		if (isset($_POST["personalKpiDetailId"])) {
			$update = PersonalKpiDetail::find()->where(["personalKpiDetailId" => $_POST["personalKpiDetailId"]])->one();
			$update->amount = $_POST["amount"];
			$update->detail = $_POST["personalDetail"];
			$update->status = 1;
			$update->updateDateTime = new Expression('NOW()');
			$imageObj = UploadedFile::getInstanceByName("kpiFile");
			if (isset($imageObj) && !empty($imageObj)) {
				$urlFolder = Path::getHost() . 'file/kpi/';
				if (!file_exists($urlFolder)) {
					mkdir($urlFolder, 0777, true);
				}
				$file = $imageObj->name;
				$filenameArray = explode('.', $file);
				$countArrayFile = count($filenameArray);
				$fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[$countArrayFile - 1];
				$pathSave = $urlFolder . $fileName;
				if ($imageObj->saveAs($pathSave)) {
					$update->file = 'file/kpi/' . $fileName;
				}
			}
			if ($update->save(false)) {
				return $this->redirect(Yii::$app->homeUrl . 'kpi/update/personal-update/' . ModelMaster::encodeParams(["pkpiId" => $update["pkpiId"]]));
			}
		}
	}
}
