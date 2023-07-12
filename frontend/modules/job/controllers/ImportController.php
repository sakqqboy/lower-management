<?php

namespace frontend\modules\job\controllers;

use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Currency;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\Team;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use yii\web\Controller;
use Yii;
use yii\db\Expression;
use yii\web\UploadedFile;

class ImportController extends Controller
{
	public function actionIndex()
	{
		$branch = Branch::find()
			->select('branchName,branchId')
			->where(["status" => 1])
			->asArray()
			->orderBy('branchName')
			->all();
		$success = [];
		$fail = [];
		$total = '';
		$textError = 'Please check<br>';
		if (isset($_POST["branch"])) {
			$total = 0;
			$branchId = $_POST["branch"];
			$imageObj = UploadedFile::getInstanceByName("jobFile");
			if (isset($imageObj) && !empty($imageObj)) {
				$urlFolder = Path::getHost() . 'file/job/';
				if (!file_exists($urlFolder)) {
					mkdir($urlFolder, 0777, true);
				}
				$file = $imageObj->name;
				$filenameArray = explode('.', $file);
				$countArrayFile = count($filenameArray);
				$fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[$countArrayFile - 1];
				$pathSave = $urlFolder . $fileName;
				if ($imageObj->saveAs($pathSave)) {
					$reader = new Xlsx();
					$spreadsheet = $reader->load($pathSave);
					$sheetData = $spreadsheet->getActiveSheet()->toArray();
					$i = 0;
					foreach ($sheetData as $data) :
						if ($i >= 1) {
							$jobName = $data[0];
							$clientName = $data[1];
							$fieldName = $data[2];
							$categoryName = $data[3];
							$jobType = $data[4];
							$fiscalYear = $data[5];
							$startMonth = $data[6];
							$teamName = $data[7];
							$fee = $data[8];
							$currency = $data[9];
							//throw new Exception(print_r($data, true));
							if (trim($clientName) != "" && trim($currency) != "" && trim($fieldName) != "" && trim($categoryName) != "" && trim($jobType) != "" && trim($fiscalYear) != "" && trim($teamName) != "" && trim($fee) != "" && trim($startMonth) != "") {
								$clientId = Client::isExistClient($clientName, $branchId);
								$fieldId = Field::fieldId($fieldName, $branchId);
								$jobTypeId = JobType::jobTypeId($jobType, $branchId);
								$categoryId = Category::categoryId($categoryName);
								$teamId = Team::teamId2($teamName, $branchId);
								$currencyId = Currency::currencyId($currency);
								$a = [
									"fieldId" => $fieldId,
									"jobTypeId" => $jobTypeId,
									"categoryId" => $categoryId,
									"teamId" => $teamId,
								];
								//throw new Exception(print_r($a, true));
								if ($fieldId != '' && $jobTypeId != '' && $categoryId != '' && $teamId != '') {
									$job = new Job();
									$job->jobName = $jobName;
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
										$success[$i] = [
											"jobName" => $jobName,
											"clientName" => $clientName,
											"categoryName" => $categoryName,
											"jobType" => $jobType,

										];
										$targetDate = null;
										$jobId = Yii::$app->db->getLastInsertID();
										$jobCategoryId = $this->saveJobCategoryRound($jobId, $categoryId, $startMonth, $targetDate, $fiscalYear);
										$this->saveJobStep($jobId, $jobTypeId, $jobCategoryId);
									}
								} else {
									if ($fieldId == '') {
										$textError .= "<b>Field</b>,<br>";
									}
									if ($jobTypeId == '') {
										$textError .= "<b>Job Type name</b>,<br>";
									}
									if ($categoryId == '') {
										$textError .= "<b>Category</b>,<br>";
									}
									if ($teamId == '') {
										$textError .= "<b>Team name</b>,<br>";
									}
									$fail[$i] = [
										"jobName" => $jobName,
										"clientName" => $clientName,
										"error" => $textError

									];
								}
							} else {

								$fail[$i] = [
									"jobName" => $jobName,
									"clientName" => $clientName,
									"error" => "Empty field"

								];
							}
							$total++;
						}
						$i++;
					endforeach;
				}
				unlink($pathSave);
			}
		}
		return $this->render('index', [
			"branch" => $branch,
			"success" => $success,
			"fail" => $fail,
			"total" => $total
		]);
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
}
