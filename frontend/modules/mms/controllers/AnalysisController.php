<?php

namespace frontend\modules\mms\controllers;

use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Chart;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\FiscalYear;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\Team;
use yii\web\Controller;
use Yii;
use yii\data\ActiveDataProvider;

class AnalysisController extends Controller
{
	public function actionIndex()
	{
		//$thisYear = date("Y");
		$employeeBranchId = Employee::employeeBranch();
		$jobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName')
			->JOIN("LEFT JOIN", "category c", "c.categoryId=job.categoryId")
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"c.categoryName" => "Monthly",
				"c.status" => 1,
				"jt.status" => 1,
				"jt.branchId" => $employeeBranchId
			])
			->orderBy('jt.jobTypeName')
			->groupBy('job.jobTypeId')
			->asArray()
			->all();
		$teams = Team::find()->select('teamId,teamName')->where(["branchId" => $employeeBranchId, "status" => 1])->all();
		$branch = Branch::find()->where(["status" => 1])->orderBy("branchName")->asArray()->all();
		$employeeBranch = Branch::find()->select('branchId,branchName')->where(["branchId" => $employeeBranchId])->asArray()->one();
		$firstJobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName,job.updateDateTime')
			->JOIN("LEFT JOIN", "category c", "c.categoryId=job.categoryId")
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"c.categoryName" => "Monthly",
				"c.status" => 1,
				"jt.status" => 1,
				"jt.branchId" => $employeeBranchId
			])
			->orderBy('jt.jobTypeName')
			//->orderBy('job.updateDateTime DESC')
			->groupBy('job.jobTypeId')
			->asArray()
			->one();
		$steps = Step::find()->select('stepId,stepName,sort')
			->where(["jobTypeId" => $firstJobType["jobTypeId"], "status" => 1])
			->orderBy('sort')
			->asArray()->all();
		$i = 0;
		$dateArr = [];
		$date = [];
		$data = [];
		$dataOnprocess = [];
		$totalMonth = [];
		while (count($dateArr) != 3) {
			$dateArr[$i] = date("Y-m-d", mktime(0, 0, 0, ((int)date('m') - 2 + $i), (int)'01', (int)date('Y')));
			$month = explode('-', $dateArr[$i]);
			$data["0"][$i][$month[1]] = 0;
			$data["1"][$i][$month[1]] = 0;
			$data["2"][$i][$month[1]] = 0;
			$data["3"][$i][$month[1]] = 0;
			$data["4"][$i][$month[1]] = 0;
			$data["5"][$i][$month[1]] = 0;
			$data["6"][$i][$month[1]] = 0;
			$totalMonth[$i][$month[1]] = 0;
			$over[$i][$month[1]] = 0;
			$dataOnprocess[$i][$month[1]] = 0;
			$i++;
		}
		$dataDay = [];

		$total = 0;
		if (count($dateArr) > 0) {
			$a = 0;
			foreach ($dateArr as $d) :
				$dArr = explode('-', $d);
				$date[$a] = [
					"month" => $dArr[1],
					"year" => $dArr[0]
				];
				$m = 0;
				while ($m <= 30) {
					$dataDay[$a][(int)$dArr[1]][$m] = 0;
					$m++;
				}
				$over[$a][(int)$dArr[1]] = 0;
				$jobs = Job::find()
					->select('jc.completeDate,job.jobId,jc.jobCategoryId')
					->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
					->where([
						"job.branchId" => $employeeBranchId,
						"job.status" => [1, 4],
						"job.jobTypeId" => $firstJobType["jobTypeId"],
						"job.categoryId" => 1,
						"jc.status" => JobCategory::STATUS_COMPLETE,
						"jc.fiscalYear" => $dArr[0],
						"jc.startMonth" => (int)$dArr[1],

					])
					->orderBy('job.jobId')
					->groupBy('jc.jobId')
					->asArray()
					->all();
				$jobOnProcess = Job::find()
					->select('jc.completeDate,job.jobId,jc.jobCategoryId')
					->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
					->where([
						"job.branchId" => $employeeBranchId,
						"job.status" => [1, 4],
						"job.jobTypeId" => $firstJobType["jobTypeId"],
						"job.categoryId" => 1,
						"jc.status" => JobCategory::STATUS_INPROCESS,
						"jc.fiscalYear" => $dArr[0],
						"jc.startMonth" => (int)$dArr[1],

					])
					->orderBy('job.jobId')
					->groupBy('jc.jobId')
					->asArray()
					->all();
				if (isset($jobOnProcess) && !empty($jobOnProcess)) {

					foreach ($jobOnProcess as $inprocess) :
						$dataOnprocess[$a][$dArr[1]]++;
					endforeach;
				}
				if (isset($jobs) && count($jobs) > 0) {
					foreach ($jobs as $job) :
						if ($job["completeDate"] != '') {
							$completeDate = explode(' ', $job["completeDate"]);
							$fullDateArr = explode('-', $completeDate[0]);
							$check = $this->checkLate($dArr[1], $fullDateArr[1]);
							if ($dArr[1] == $fullDateArr[1]) {
								$fullDateArr[2] = 1;
							}
							if ((int)$dArr[1] == 12) {
								$fullDateArr[0] = $dArr[0];
							}
							if ((int)$fullDateArr[2] <= 5 && $dArr[0] == $fullDateArr[0] && ($dArr[1] == $fullDateArr[1] || $check == 1)) {
								if (isset($data["0"][$a][$dArr[1]])) {
									$data["0"][$a][$dArr[1]]++;
								} else {
									$data["0"][$a][$dArr[1]] = 1;
								}
							}
							if ((int)$fullDateArr[2] <= 10 && (int)$fullDateArr[2] > 5 && $dArr[0] == $fullDateArr[0] && ($dArr[1] == $fullDateArr[1] || $check == 1)) {
								if (isset($data["1"][$a][$dArr[1]])) {
									$data["1"][$a][$dArr[1]]++;
								} else {
									$data["1"][$a][$dArr[1]] = 1;
								}
							}
							if ((int)$fullDateArr[2] <= 15 && (int)$fullDateArr[2] > 10 && $dArr[0] == $fullDateArr[0] && ($dArr[1] == $fullDateArr[1] || $check == 1)) {
								if (isset($data["2"][$a][$dArr[1]])) {
									$data["2"][$a][$dArr[1]]++;
								} else {
									$data["2"][$a][$dArr[1]] = 1;
								}
							}
							if ((int)$fullDateArr[2] <= 20 && (int)$fullDateArr[2] > 15 && $dArr[0] == $fullDateArr[0] && ($dArr[1] == $fullDateArr[1] || $check == 1)) {
								if (isset($data["3"][$a][$dArr[1]])) {
									$data["3"][$a][$dArr[1]]++;
								} else {
									$data["3"][$a][$dArr[1]] = 1;
								}
							}
							if ((int)$fullDateArr[2] <= 25 && (int)$fullDateArr[2] > 20 && $dArr[0] == $fullDateArr[0] && ($dArr[1] == $fullDateArr[1] || $check == 1)) {
								if (isset($data["4"][$a][$dArr[1]])) {
									$data["4"][$a][$dArr[1]]++;
								} else {
									$data["4"][$a][$dArr[1]] = 1;
								}
							}
							if ((int)$fullDateArr[2] <= 31 && (int)$fullDateArr[2] > 25 && $dArr[0] == $fullDateArr[0] && ($dArr[1] == $fullDateArr[1] || $check == 1)) {
								if (isset($data["5"][$a][$dArr[1]])) {
									$data["5"][$a][$dArr[1]]++;
								} else {
									$data["5"][$a][$dArr[1]] = 1;
								}
							}
							//if ($dArr[1] != $fullDateArr[1] || $dArr[0] != $fullDateArr[0]) {
							if ($check == 0) {
								$data["6"][$a][$dArr[1]]++;
							}
							if (($dArr[1] == $fullDateArr[1] || $check == 1) && $dArr[0] == $fullDateArr[0]) {
								$dataDay[$a][(int)$dArr[1]][(int)$fullDateArr[2] - 1]++;
							} else {
								$over[$a][(int)$dArr[1]]++;
							}
							if (isset($totalMonth[$a][$dArr[1]])) {
								$totalMonth[$a][$dArr[1]]++;
							} else {
								$totalMonth[$a][$dArr[1]] = 1;
							}
							$total++;
						}
					endforeach;
				}
				$a++;

			endforeach;
		}
		//throw new exception(print_r($data, true));
		ksort($data);
		$value = [];
		$xData = Chart::getXvacter(1, null);
		$dDay = [];
		$color = ["#0066FF", "#FF9900", "#778899"];
		$jobCategoryFiscalYear = JobCategory::allFiscalYear();
		$months = ModelMaster::month();
		if (count($dataDay) > 0) {
			$index = 0;
			foreach ($dataDay as $dataYear) :
				foreach ($dataYear as $monthIndex => $everyDay) :
					$i = 0;
					if (count($everyDay) > 0) {
						foreach ($everyDay as $d => $day) :
							//if ($d != "over") {
							$dDay[$i] = $day;
							//}
							$i++;
						endforeach;
					}
					$value[$index] = [
						"name" => ModelMaster::shotMonthText($monthIndex),
						"data" => $dDay,
						"color" => $color[$index]
					];
					$index++;
				endforeach;
			endforeach;
		}
		//$a = substr('2023-03-10 11:03:19', 5, 2);
		//throw new exception(print_r($value, true));
		return $this->render('index', [
			"jobType" => $jobType,
			"branch" => $branch,
			"employeeBranch" => $employeeBranch,
			"data" => $data,
			"totalMonth" => $totalMonth,
			"total" => $total,
			"dataDay" => $dataDay,
			"xData" => $xData,
			"values" => $value,
			"chartName" => $firstJobType["jobTypeName"],
			"jobTypeId" => $firstJobType["jobTypeId"],
			"color" => $color,
			"jobCategoryFiscalYear" => $jobCategoryFiscalYear,
			"months" => $months,
			"date" => $date,
			"over" => $over,
			"dataOnprocess" => $dataOnprocess,
			"teams" => $teams,
			"teamId" => '',
			"personId" => '',
			"steps" => $steps

		]);
	}
	public function actionFilterAnalysis()
	{
		$year = $_POST["year"];
		$month = $_POST["month"];
		$branchId = $_POST["branchId"];
		$jobTypeId = $_POST["jobTypeId"];
		$teamId = $_POST["teamId"];
		$personId = $_POST["personId"];
		$stepId = $_POST["stepId"];
		$dateArr = [];
		$data = [];
		$i = 0;
		$date = [];
		$dataOnprocess = [];
		while ($i < 3) {

			$data["0"][$i][$month[$i]] = 0;
			$data["1"][$i][$month[$i]] = 0;
			$data["2"][$i][$month[$i]] = 0;
			$data["3"][$i][$month[$i]] = 0;
			$data["4"][$i][$month[$i]] = 0;
			$data["5"][$i][$month[$i]] = 0;
			$data["6"][$i][$month[$i]] = 0;
			$over[$i][(int)$month[$i]] = 0;
			$dataOnprocess[$i][$month[$i]] = 0;
			$i++;
		}
		$dataDay = [];
		$totalMonth = [];
		$total = 0;
		$i = 0;
		$existArray = [];
		$key = 0;
		while ($i < 3) {
			$date[$i] = [
				"month" => $month[$i],
				"year" => $year[$i]
			];
			$m = 0;
			while ($m <= 30) {
				$dataDay[$i][(int)$month[$i]][$m] = 0;
				$m++;
			}

			$over[(int)$year[$i]][(int)$month[$i]] = 0;
			$key = $year[$i] . '-' . $month[$i];
			$jobs = [];
			$jobs = Job::find()
				->select('jc.completeDate,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->where([
					"job.branchId" => $branchId,
					"job.status" => [1, 4],
					"job.jobTypeId" => $jobTypeId,
					"job.categoryId" => 1,
					"jc.status" => JobCategory::STATUS_COMPLETE,
					"jc.fiscalYear" => $year[$i],
					"jc.startMonth" => (int)$month[$i],
					"jr.responsibility" => [2, 3]
				])
				->andFilterWhere([
					"job.teamId" => $teamId,
					"jr.employeeId" => $personId,
					"js.stepId" => $stepId
				])
				->asArray()
				->groupBy('jc.jobId')
				->all();
			$jobOnProcess = Job::find()
				->select('jc.completeDate,job.jobId,jc.jobCategoryId,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
				->where([
					"job.branchId" => $branchId,
					"job.status" => [1, 4],
					"job.jobTypeId" => $jobTypeId,
					"job.categoryId" => 1,
					"jc.status" => JobCategory::STATUS_INPROCESS,
					"jc.fiscalYear" => $year[$i],
					"jc.startMonth" => (int)$month[$i],
					"jr.responsibility" => [2, 3]
				])
				->andFilterWhere([
					"job.teamId" => $teamId,
					"jr.employeeId" => $personId,
					"js.stepId" => $stepId
				])
				->orderBy('job.jobId')
				->groupBy('jc.jobId')
				->asArray()
				->all();


			$existArray[$key] = 1;
			if (isset($jobOnProcess) && !empty($jobOnProcess)) {
				foreach ($jobOnProcess as $inprocess) :
					$dataOnprocess[$i][$month[$i]]++;
				endforeach;
			}
			if (isset($jobs) && count($jobs) > 0) {
				foreach ($jobs as $job) :
					if ($stepId != '') {
						$completeDateUse = $job["jsCompleteDate"];
					} else if ($job["completeDate"] != '') {
						$completeDateUse = $job["completeDate"];
					} else {
						$completeDateUse = '';
					}
					//$completeDateUse = $job["completeDate"];
					if ($completeDateUse != '') {
						$completeDate = explode(' ', $completeDateUse);
						$fullDateArr = explode('-', $completeDate[0]);
						$check = $this->checkLate($month[$i], $fullDateArr[1]);
						if ($month[$i] == $fullDateArr[1]) {
							$fullDateArr[2] = 1;
						}
						if ((int)$month[$i] == 12) {
							$fullDateArr[0] = $year[$i];
						}
						if ((int)$fullDateArr[2] <= 5  && $year[$i] == $fullDateArr[0] && ($month[$i] == $fullDateArr[1] || $check == 1)) {
							if (isset($data["0"][$i][$month[$i]])) {
								$data["0"][$i][$month[$i]]++;
							} else {
								$data["0"][$i][$month[$i]] = 1;
							}
						}
						if ((int)$fullDateArr[2] <= 10 && (int)$fullDateArr[2] > 5  && $year[$i] == $fullDateArr[0] && ($month[$i] == $fullDateArr[1] || $check == 1)) {
							if (isset($data["1"][$i][$month[$i]])) {
								$data["1"][$i][$month[$i]]++;
							} else {
								$data["1"][$i][$month[$i]] = 1;
							}
						}
						if ((int)$fullDateArr[2] <= 15 && (int)$fullDateArr[2] > 10  && $year[$i] == $fullDateArr[0] && ($month[$i] == $fullDateArr[1] || $check == 1)) {
							if (isset($data["2"][$i][$month[$i]])) {
								$data["2"][$i][$month[$i]]++;
							} else {
								$data["2"][$i][$month[$i]] = 1;
							}
						}
						if ((int)$fullDateArr[2] <= 20 && (int)$fullDateArr[2] > 15  && $year[$i] == $fullDateArr[0] && ($month[$i] == $fullDateArr[1] || $check == 1)) {
							if (isset($data["3"][$i][$month[$i]])) {
								$data["3"][$i][$month[$i]]++;
							} else {
								$data["3"][$i][$month[$i]] = 1;
							}
						}
						if ((int)$fullDateArr[2] <= 25 && (int)$fullDateArr[2] > 20 && $year[$i] == $fullDateArr[0] && ($month[$i] == $fullDateArr[1] || $check == 1)) {
							if (isset($data["4"][$i][$month[$i]])) {
								$data["4"][$i][$month[$i]]++;
							} else {
								$data["4"][$i][$month[$i]] = 1;
							}
						}
						if ((int)$fullDateArr[2] <= 31 && (int)$fullDateArr[2] > 25 && $year[$i] == $fullDateArr[0] && ($month[$i] == $fullDateArr[1] || $check == 1)) {
							if (isset($data["5"][$i][$month[$i]])) {
								$data["5"][$i][$month[$i]]++;
							} else {
								$data["5"][$i][$month[$i]] = 1;
							}
						}
						//if ($month[$i] != $fullDateArr[1] || $year[$i] != $fullDateArr[0]) {
						if ($check == 0) {
							$data["6"][$i][$month[$i]]++;
						}
						if (($month[$i] == $fullDateArr[1] || $check == 1) && $year[$i] == $fullDateArr[0]) {
							$dataDay[$i][(int)$month[$i]][(int)$fullDateArr[2] - 1]++;
						} else {
							$over[$i][(int)$month[$i]]++;
						}

						if (isset($totalMonth[$i][$month[$i]])) {
							$totalMonth[$i][$month[$i]]++;
						} else {
							$totalMonth[$i][$month[$i]] = 1;
						}
						$total++;
					}
				endforeach;
			} else {
				$totalMonth[$i][$month[$i]] = 0;
				$over[$i][(int)$month[$i]] = 0;
			}
			$i++;
		}
		return $this->redirect(Yii::$app->homeUrl . 'mms/analysis/filter/' . ModelMaster::encodeParams([
			"dataDay" => $dataDay,
			"date" => $date,
			"branchId" => $branchId,
			"data" => $data,
			"totalMonth" => $totalMonth,
			"total" => $total,
			"jobTypeId" => $jobTypeId,
			"over" => $over,
			"dataOnprocess" => $dataOnprocess,
			"teamId" => $teamId,
			"personId" => $personId,
			"stepId" => $stepId

		]));
	}
	public function actionFilter($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$dataDay = $param["dataDay"];
		$date = $param["date"];
		$branchId = $param["branchId"];
		$data = $param["data"];
		$totalMonth = $param["totalMonth"];
		$total = $param["total"];
		$jobTypeId = $param["jobTypeId"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$over = $param["over"];
		$stepId = $param["stepId"];
		$dataOnprocess = $param["dataOnprocess"];
		$value = [];
		$xData = Chart::getXvacter(1, null);
		$dDay = [];
		$color = ["#0066FF", "#FF9900", "#778899"];
		$jobCategoryFiscalYear = JobCategory::allFiscalYear();
		//throw new exception(print_r($data, true));
		$months = ModelMaster::month();
		$teams = Team::find()->select('teamId,teamName')->where(["branchId" => $branchId, "status" => 1])->asArray()->all();
		$persons = [];
		if ($teamId != '') {
			$persons = Employee::find('employeeId,employeeNickName')
				->where(["teamId" => $teamId, "status" => Team::STATUS_ACTIVE])->asArray()->all();
		}
		if (count($dataDay) > 0) {

			$index = 0;
			foreach ($dataDay as $dataYear) :
				foreach ($dataYear as $monthIndex => $everyDay) :
					$i = 0;
					if (count($everyDay) > 0) {
						foreach ($everyDay as $day) :
							$dDay[$i] = $day * 1;
							$i++;
						endforeach;
					}
					$value[$index] = [
						"name" => ModelMaster::shotMonthText($monthIndex),
						"data" => $dDay,
						"color" => $color[$index]
					];
					$index++;
				endforeach;
			endforeach;
		}
		$jobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName')
			->JOIN("LEFT JOIN", "category c", "c.categoryId=job.categoryId")
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"c.categoryName" => "Monthly",
				"c.status" => 1,
				"jt.status" => 1,
				"jt.branchId" => $branchId
			])
			->orderBy('jt.jobTypeName')
			->groupBy('job.jobTypeId')
			->asArray()
			->all();
		$steps = Step::find()->select('stepId,stepName,sort')
			->where(["jobTypeId" => $jobTypeId, "status" => 1])
			->orderBy('sort')
			->asArray()->all();
		$branch = Branch::find()->where(["status" => 1])->orderBy("branchName")->asArray()->all();
		$selectBranch = Branch::find()->select('branchId,branchName')->where(["branchId" => $branchId])->asArray()->one();
		$selectJobType = JobType::find()
			->select('jobTypeName,jobTypeId')
			->where([
				"jobTypeId" => $jobTypeId,
			])
			->asArray()
			->one();
		//throw new exception(print_r($date, true));
		return $this->render('filter_result', [
			"jobType" => $jobType,
			"branch" => $branch,
			"selectBranch" => $selectBranch,
			"data" => $data,
			"totalMonth" => $totalMonth,
			"total" => $total,
			"dataDay" => $dataDay,
			"xData" => $xData,
			"values" => $value,
			"chartName" => $selectJobType["jobTypeName"],
			"jobTypeId" => $selectJobType["jobTypeId"],
			"color" => $color,
			"jobCategoryFiscalYear" => $jobCategoryFiscalYear,
			"months" => $months,
			"date" => $date,
			"over" => $over,
			"dataOnprocess" => $dataOnprocess,
			"teamId" => $teamId,
			"teams" => $teams,
			"persons" => $persons,
			"personId" => $personId,
			"steps" => $steps,
			"stepId" => $stepId


		]);
	}
	public function actionYearly()
	{
		$thisyear = date('Y');
		$dateArr = [];
		$data = [];
		$defaultMonth = [
			"value" => 12,
			"text" => "Dec"
		];
		$i = 0;
		while (count($dateArr) != 3) {
			$year = $thisyear - 2 + $i;
			$dateArr[$i] = $year;
			$data[0][$i] = 0;
			$data[1][$i] = 0;
			$data[2][$i] = 0;
			$data[3][$i] = 0;
			$data[4][$i] = 0;
			$data[5][$i] = 0;
			$data[6][$i] = 0;
			$totalYear[$i] = 0;
			$defaultMonth[$i] = [
				"value" => 12,
				"text" => "Dec"
			];
			$i++;
		}
		$employeeBranchId = Employee::employeeBranch();
		$teams = Team::find()->select('teamId,teamName')->where(["branchId" => $employeeBranchId, "status" => 1])->all();
		$employeeBranch = Branch::find()->select('branchId,branchName')->where(["branchId" => $employeeBranchId])->asArray()->one();
		$jobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName')
			->JOIN("LEFT JOIN", "category c", "c.categoryId=job.categoryId")
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"c.categoryName" => "Yearly",
				"c.status" => 1,
				"jt.status" => 1,
				"jt.branchId" => $employeeBranchId
			])
			->orderBy('jt.jobTypeName')
			->groupBy('job.jobTypeId')
			->asArray()
			->all();
		$branch = Branch::find()->where(["status" => 1])->orderBy("branchName")->asArray()->all();
		$firstJobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName,job.updateDateTime')
			->JOIN("LEFT JOIN", "category c", "c.categoryId=job.categoryId")
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->where([
				"job.status" => [1, 4],
				"c.categoryName" => "Yearly",
				"c.status" => 1,
				"jt.status" => 1,
				"jt.branchId" => $employeeBranchId,
				"jc.startMonth" => 12,
				"jc.fiscalYear" => $dateArr,
				"jc.status" => JobCategory::STATUS_COMPLETE
			])
			->orderBy('jt.updateDateTime DESC')
			->groupBy('job.jobTypeId')
			->asArray()
			->one();
		$steps = Step::find()->select('stepId,stepName,sort')
			->where(["jobTypeId" => $firstJobType["jobTypeId"], "status" => 1])
			->orderBy('sort')
			->asArray()->all();
		$y = 0;
		$datePeriod = [];
		$i = 0;
		foreach ($dateArr as $year) :

			$day = 1;
			while ($day < 180) {
				$start = $day;
				$end = $day + 4;
				$datePeriod[$i][$end] = 0;
				$start = $end + 1;

				$day += 5;
			}
			$datePeriod[$i]["over"] = 0;
			$dataOnprocess[$i] = 0;
			$i++;
		endforeach;
		$dataDay = [];
		$total = 0;
		if (count($dateArr) > 0) {
			$a = 0;
			$o = 0;
			foreach ($dateArr as $year) :
				$jobsOnprocess = Job::find()
					->select('jc.completeDate')
					->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
					->where([
						"job.branchId" => $employeeBranchId,
						"job.status" => 1,
						"job.jobTypeId" => $firstJobType["jobTypeId"],
						"job.categoryId" => 6,
						"jc.status" => JobCategory::STATUS_INPROCESS,
						"jc.fiscalYear" => $year,
						"jc.startMonth" => $defaultMonth[$o]["value"]

					])
					->asArray()
					->all();
				if (isset($jobsOnprocess) && count($jobsOnprocess) > 0) {
					foreach ($jobsOnprocess as $onprocess) :
						$dataOnprocess[$o]++;
					endforeach;
				}
				$o++;
			endforeach;
			foreach ($dateArr as $year) :
				$jobs = Job::find()
					->select('jc.completeDate')
					->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
					->where([
						"job.branchId" => $employeeBranchId,
						"job.status" => [1, 4],
						"job.jobTypeId" => $firstJobType["jobTypeId"],
						"job.categoryId" => 6,
						"jc.status" => JobCategory::STATUS_COMPLETE,
						"jc.fiscalYear" => $year,
						"jc.startMonth" => $defaultMonth[$a]["value"]

					])
					->asArray()
					->all();

				if (isset($jobs) && count($jobs) > 0) {
					foreach ($jobs as $job) :
						if ($job["completeDate"] != '') {
							$completeDate = explode(' ', $job["completeDate"]);
							$days = $this->calculateDays($year, $defaultMonth[$a]["value"], $completeDate[0]);
							$fullDateArr = explode('-', $completeDate[0]);
							if ($days <= 30) {
								$data[0][$a]++;
							}
							if ($days > 30 && $days <= 60) {
								$data[1][$a]++;
							}
							if ($days > 60 && $days <= 90) {
								$data[2][$a]++;
							}
							if ($days > 90 && $days <= 120) {
								$data[3][$a]++;
							}
							if ($days > 120 && $days <= 150) {
								$data[4][$a]++;
							}
							if ($days > 150 && $days <= 180) {
								$data[5][$a]++;
							}
							if ($days > 180) { //over
								$data[6][$a]++;
							}
							if (isset($totalYear[$a])) {
								$totalYear[$a]++;
							} else {
								$totalYear[$a] = 1;
							}
							$totalUsedate = $days;
							$indexFloor = floor($totalUsedate / 5);
							$index = $indexFloor * 5;
							if ($totalUsedate % 5 != 0) {
								$index = $index + 5;
							}
							if ($index == 0) {
								$index = 5;
							}
							if ($totalUsedate <= 180) {
								$datePeriod[$a][$index]++;
							} else {
								$datePeriod[$a]["over"]++;
							}
							$total++;
						}
					endforeach;
				}
				$a++;

			endforeach;
		}

		$jobCategoryFiscalYear = JobCategory::allFiscalYear();
		$xData = $this->xData();
		$months = ModelMaster::month();
		$color = ["#0066FF", "#FF9900", "#778899"];
		$values = [];
		if (count($datePeriod) > 0) {

			$index = 0;
			foreach ($datePeriod as $year => $dataYear) :
				$i = 0;
				foreach ($dataYear as $dateIndex => $value) :
					if ($dateIndex != "over") {
						$dDay[$i] = $value * 1;
						$i++;
					}
				endforeach;
				$values[$index] = [
					//"name" => $year,
					"name" => $dateArr[$year],
					"data" => $dDay,
					"color" => $color[$index]
				];
				$index++;
			endforeach;
		}
		return $this->render('yearly_index', [
			"date" => $dateArr,
			"jobCategoryFiscalYear" => $jobCategoryFiscalYear,
			"dataOnprocess" => $dataOnprocess,
			"jobType" => $jobType,
			"chartName" => $firstJobType["jobTypeName"],
			"jobTypeId" => $firstJobType["jobTypeId"],
			"employeeBranch" => $employeeBranch,
			"branch" => $branch,
			"data" => $data,
			"total" => $total,
			"totalYear" => $totalYear,
			"datePeriod" => $datePeriod,
			"xData" => $xData,
			"color" => $color,
			"values" => $values,
			"months" => $months,
			"defaultMonth" => $defaultMonth,
			"teams" => $teams,
			"teamId" => '',
			"personId" => '',
			"steps" => $steps
		]);
	}
	public function actionFilterYearlyAnalysis()
	{
		$postYear = $_POST["year"];
		$branchId = $_POST["branchId"];
		$jobTypeId = $_POST["jobTypeId"];
		$postMonth = $_POST["month"];
		$teamId = $_POST["teamId"];
		$personId = $_POST["personId"];
		$stepId = $_POST["stepId"];
		$dateArr = [];
		$totalYear = [];
		$data = [];
		$i = 0;
		while (count($dateArr) != 3) {
			$dateArr[$i] = $postYear[$i];
			$month[$i] = ["value" => $postMonth[$i]];
			$year = $postYear[$i];
			$data[0][$i] = 0;
			$data[1][$i] = 0;
			$data[2][$i] = 0;
			$data[3][$i] = 0;
			$data[4][$i] = 0;
			$data[5][$i] = 0;
			$data[6][$i] = 0;
			$totalYear[$i] = 0;
			$i++;
		}
		//krsort($dateArr);
		//ksort($data);
		$y = 0;
		$datePeriod = [];
		foreach ($dateArr as $indexYear => $year) :
			$i = 0;
			$day = 1;
			while ($day < 180) {
				//$start = $day;
				$end = $day + 4;
				$datePeriod[$indexYear][$end] = 0;
				//$start = $end + 1;
				$i++;
				$day += 5;
			}
			$datePeriod[$indexYear]["over"] = 0;
			$dataOnprocess[$indexYear] = 0;
		endforeach;
		$dataDay = [];

		$total = 0;
		if (count($dateArr) > 0) {
			$a = 0;
			$previousYear = 0;
			foreach ($dateArr as $indexYear => $year) :
				$jobsOnprocess = Job::find()
					->select('jc.completeDate,js.completeDate as jsCompleteDate')
					->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
					->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where([
						"job.branchId" => $branchId,
						"job.status" => 1,
						"job.jobTypeId" => $jobTypeId,
						"job.categoryId" => 6,
						"jc.status" => JobCategory::STATUS_INPROCESS,
						"jc.fiscalYear" => $year,
						"jr.responsibility" => [2, 3]
					])
					->andFilterWhere([
						"jc.startMonth" => $month[$indexYear]["value"],
						"job.teamId" => $teamId,
						"jr.employeeId" => $personId,
						"js.stepId" => $stepId
					])
					->groupBy('jc.jobId')
					->asArray()
					->all();
				if (isset($jobsOnprocess) && count($jobsOnprocess) > 0) {
					foreach ($jobsOnprocess as $onprocess) :
						$dataOnprocess[$indexYear]++;
					endforeach;
				}
			endforeach;
			foreach ($dateArr as $indexYear => $year) :
				$jobs = Job::find()
					->select('jc.completeDate,jc.startMonth,job.jobId,jc.jobCategoryId,js.completeDate as jsCompleteDate')
					->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
					->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
					->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
					->where([
						"job.branchId" => $branchId,
						"job.status" => [1, 4],
						"job.jobTypeId" => $jobTypeId,
						"job.categoryId" => 6,
						"jc.status" => JobCategory::STATUS_COMPLETE,
						"jc.fiscalYear" => $year,
						"jr.responsibility" => [2, 3]
					])
					->andFilterWhere([
						"jc.startMonth" => isset($month[$indexYear]["value"]) ? $month[$indexYear]["value"] : null,
						"job.teamId" => $teamId,
						"jr.employeeId" => $personId,
						"js.stepId" => $stepId
					])
					->groupBy('jc.jobId')
					->asArray()
					->all();
				if (isset($jobs) && count($jobs) > 0) {
					foreach ($jobs as $job) :


						if ($stepId != '') {
							$completeDateUse = $job["jsCompleteDate"];
						} else if ($job["completeDate"] != '') {
							$completeDateUse = $job["completeDate"];
						} else {
							$completeDateUse = '';
						}
						//$completeDateUse = $job["completeDate"];
						if ($completeDateUse != '') {
							$completeDate = explode(' ', $completeDateUse);
							$fullDateArr = explode('-', $completeDate[0]);
							if ($month[$indexYear]["value"] != "") {
								$days = $this->calculateDays($year, $month[$indexYear]["value"], $completeDate[0]);
							} else {
								$startMonth = $job["startMonth"];
								$days = $this->calculateDays($year, $startMonth, $completeDate[0]);
							}
							if ($days <= 30) {
								$data[0][$a]++;
							}
							if ($days > 30 && $days <= 60) {
								$data[1][$a]++;
							}
							if ($days > 60 && $days <= 90) {
								$data[2][$a]++;
							}
							if ($days > 90 && $days <= 120) {
								$data[3][$a]++;
							}
							if ($days > 120 && $days <= 150) {
								$data[4][$a]++;
							}
							if ($days > 150 && $days <= 180) {
								$data[5][$a]++;
							}
							if ($days > 180) { //over
								$data[6][$a]++;
							}
							if (isset($totalYear[$indexYear])) {
								$totalYear[$indexYear]++;
							} else {
								$totalYear[$indexYear] = 1;
							}
							$totalUsedate = $days;
							$indexFloor = floor($totalUsedate / 5);
							$index = $indexFloor * 5;
							if ($totalUsedate % 5 != 0) {
								$index = $index + 5;
							}
							if ($index == 0) {
								$index = 5;
							}

							if ($totalUsedate <= 180) {
								$datePeriod[$indexYear][$index]++;
							} else {
								$datePeriod[$indexYear]["over"]++;
							}
							$total++;
						}
					endforeach;
				}
				$a++;

			endforeach;
		}

		return $this->redirect(Yii::$app->homeUrl . 'mms/analysis/filter-yearly/' . ModelMaster::encodeParams(
			[
				"date" => $dateArr,
				"data" => $data,
				"total" => $total,
				"totalYear" => $totalYear,
				"datePeriod" => $datePeriod,
				"jobTypeId" => $jobTypeId,
				"branchId" => $branchId,
				"dataOnprocess" => $dataOnprocess,
				"month" => $month,
				"personId" => $personId,
				"teamId" => $teamId,
				"stepId" => $stepId

			]
		));
	}
	public function actionFilterYearly($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$dateArr = $param["date"];
		$branchId = $param["branchId"];
		$data = $param["data"];
		$total = $param["total"];
		$totalYear = $param["totalYear"];
		$datePeriod = $param["datePeriod"];
		$jobTypeId = $param["jobTypeId"];
		$selectMonth = $param["month"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$stepId = $param["stepId"];
		$dataOnprocess = $param["dataOnprocess"];
		$branch = Branch::find()->where(["status" => 1])->orderBy("branchName")->asArray()->all();
		$teams = Team::find()->select('teamId,teamName')->where(["branchId" => $branchId, "status" => 1])->asArray()->all();
		$persons = [];
		if ($teamId != '') {
			$persons = Employee::find('employeeId,employeeNickName')
				->where(["teamId" => $teamId, "status" => Team::STATUS_ACTIVE])->asArray()->all();
		}
		$jobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName')
			->JOIN("LEFT JOIN", "category c", "c.categoryId=job.categoryId")
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"c.categoryName" => "Yearly",
				"c.status" => 1,
				"jt.status" => 1,
				"jt.branchId" => $branchId
			])
			->orderBy('jt.jobTypeName')
			->groupBy('job.jobTypeId')
			->asArray()
			->all();
		$firstJobType = JobType::find()
			->select('jobTypeId,jobTypeName')
			->where([
				"jobTypeId" => $jobTypeId,
			])
			->asArray()
			->one();
		$steps = Step::find()->select('stepId,stepName,sort')
			->where(["jobTypeId" => $firstJobType["jobTypeId"], "status" => 1])
			->orderBy('sort')
			->asArray()->all();
		$selectBranch = Branch::find()->select('branchId,branchName')->where(["branchId" => $branchId])->asArray()->one();
		$jobCategoryFiscalYear = JobCategory::allFiscalYear();
		$xData = $this->xData();
		$color = ["#0066FF", "#FF9900", "#778899"];
		$values = [];
		$months = ModelMaster::month();
		if (count($datePeriod) > 0) {

			$index = 0;
			foreach ($datePeriod as $year => $dataYear) :
				$i = 0;
				foreach ($dataYear as $dateIndex => $value) :
					if ($dateIndex != "over") {
						$dDay[$i] = $value * 1;
						$i++;
					}
				endforeach;
				$values[$index] = [
					"name" => $dateArr[$year],
					"data" => $dDay,
					"color" => $color[$index]
				];
				$index++;
			endforeach;
		}
		return $this->render('filter_yearly', [
			"date" => $dateArr,
			"jobCategoryFiscalYear" => $jobCategoryFiscalYear,
			"jobType" => $jobType,
			"chartName" => $firstJobType["jobTypeName"],
			"jobTypeId" => $firstJobType["jobTypeId"],
			"selectBranch" => $selectBranch,
			"branch" => $branch,
			"data" => $data,
			"total" => $total,
			"totalYear" => $totalYear,
			"datePeriod" => $datePeriod,
			"xData" => $xData,
			"color" => $color,
			"values" => $values,
			"dataOnprocess" => $dataOnprocess,
			"selectMonth" => $selectMonth,
			"months" => $months,
			"teams" => $teams,
			"persons" => $persons,
			"personId" => $personId,
			"teamId" => $teamId,
			"stepId" => $stepId,
			"steps" => $steps
		]);
	}
	public function calculateDate($month)
	{
		$year = date('Y');
		if ($year % 4 == 0) {
			$feb = 29;
		} else {
			$feb = 28;
		}
		//throw new Exception($month);
		$month = $month - 1;
		$totalDate = 0;

		$months = [31, $feb, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
		while ($month > 0) {
			$totalDate += $months[$month];
			$month--;
		}

		return $totalDate;
	}
	public function xData()
	{
		$i = 0;
		$day = 1;
		$datePeriod = [];
		while ($day < 180) {
			$start = $day;
			$end = $day + 4;
			//$datePeriod[$year][$i]["start"] = $start;
			$datePeriod[$i] = $start . '-' . $end;
			$start = $end + 1;
			$i++;
			$day += 5;
		}
		return $datePeriod;
	}
	public function checkLate($month, $completeMonth)
	{
		$check = 0;
		//throw new Exception($month . '=>' . $completeMonth);
		if ((int)$month < 12) {
			if (((int)$completeMonth == (int)$month + 1) || ((int)$month == (int)$completeMonth)) {
				$check = 1;
			}
		} else {
			if ((int)$completeMonth == 12 || (int)$completeMonth == 1) {
				$check = 1;
			}
		}
		//throw new Exception($check);
		return $check;
	}
	public function actionDetail1Monthly($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$year = $param["year"];
		$month = $param["month"];
		$period = $param["period"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$stepId = $param["stepId"];
		$jobId = [];
		$jobs = Job::find()
			->select('jc.completeDate,job.jobId,job.jobName,js.completeDate as jsCompleteDate')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
			->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
			->where([
				"job.branchId" => $branchId,
				"job.status" => [1, 4],
				"job.jobTypeId" => $jobTypeId,
				"job.categoryId" => 1,
				"jc.status" => JobCategory::STATUS_COMPLETE,
				"jc.fiscalYear" => $year,
				"jc.startMonth" => (int)$month,
				"jr.responsibility" => [2, 3]
			])
			->andFilterWhere([
				"job.teamId" => $teamId,
				"jr.employeeId" => $personId,
				"js.stepId" => $stepId
			])
			->asArray()
			->groupBy('jc.jobId')
			->all();
		if ($period == 30) {
			$period = 31;
			$start = 26;
			$end = 31;
		} else {
			$start = $period - 4;
			$end = $period;
		}
		if (isset($jobs) && count($jobs) > 0) {
			$i = 0;
			foreach ($jobs as $job) :
				if ($stepId != '') {
					$completeDateUse = $job["jsCompleteDate"];
				} else if ($job["completeDate"] != '') {
					$completeDateUse = $job["completeDate"];
				} else {
					$completeDateUse = '';
				}
				//$completeDateUse = $job["completeDate"];
				if ($completeDateUse != '') {
					$completeDate = explode(' ', $completeDateUse);
					$fullDateArr = explode('-', $completeDate[0]);
					if ((int)$fullDateArr[1] == (int)$month) {
						$fullDateArr[2] = 1;
					}
					if ($period != 0) {
						if ($period == 35) {
							$check = $this->checkLate($month, (int)$fullDateArr[1]);
							if ($check == 0) {
								$jobId[$i] = $job["jobId"];
							}
						} else {
							if ((int)$fullDateArr[2] >= $start && (int)$fullDateArr[2] <= $end) {
								$jobId[$i] = $job["jobId"];
							}
						}
					} else {
						$jobId[$i] = $job["jobId"];
					}
					$i++;
				}
			endforeach;
		}
		if (count($jobId) > 0) {
			$query = Job::find()
				->select('job.*,jc.completeDate as completeDate,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->where([
					"job.jobId" => $jobId,
					"jc.fiscalYear" => $year,
					"jc.startMonth" => (int)$month,
				])
				->andFilterWhere([
					"js.stepId" => $stepId
				])
				->groupBy('jc.jobId')
				->orderBy('jc.completeDate DESC');
		}
		$stepName = '';
		if ($stepId != '') {

			$stepName = Step::stepName($stepId);
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		return $this->render('show_job', [
			"dataProviderJob" => $dataProviderJob,
			"month" => $month,
			"year" => $year,
			"start" => $start,
			"end" => $end,
			"period" => $period,
			"stepName" => $stepName
		]);
		//throw new Exception(print_r($jobId, true));
	}
	public function actionDetail1MonthlyDay($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$year = $param["year"];
		$month = $param["month"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$stepId = $param["stepId"];
		$day = $param["day"];
		$jobId = [];
		$query = [];
		$jobs = Job::find()
			->select('jc.completeDate,job.jobId,job.jobName,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
			->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
			->where([
				"job.branchId" => $branchId,
				"job.status" => [1, 4],
				"job.jobTypeId" => $jobTypeId,
				"job.categoryId" => 1,
				"jc.status" => JobCategory::STATUS_COMPLETE,
				"jc.fiscalYear" => $year,
				"jc.startMonth" => (int)$month,
				"jr.responsibility" => [2, 3]
			])
			->andFilterWhere([
				"js.stepId" => $stepId,
				"job.teamId" => $teamId,
				"jr.employeeId" => $personId
			])
			->asArray()
			->groupBy('jc.jobId')
			->all();
		if (isset($jobs) && count($jobs) > 0) {
			$i = 0;
			foreach ($jobs as $job) :
				if ($stepId != '') {
					$completeDateUse = $job["jsCompleteDate"];
				} else if ($job["completeDate"] != '') {
					$completeDateUse = $job["completeDate"];
				} else {
					$completeDateUse = '';
				}
				if ($completeDateUse != '') {
					$completeDate = explode(' ', $completeDateUse);
					$fullDateArr = explode('-', $completeDate[0]);
					if ((int)$fullDateArr[1] == (int)$month) {
						$fullDateArr[2] = 1;
					}
					if ($day == 99) {
						$check = $this->checkLate((int)$fullDateArr[1], (int)$month);
						if ($check == 0) {
							$jobId[$i] = $job["jobId"];
						}
					} else {
						if ((int)$fullDateArr[2] == $day + 1) {
							$jobId[$i] = $job["jobId"];
						}
					}
					$i++;
				}
			endforeach;
		}
		if (count($jobId) > 0) {
			$query = Job::find()
				->select('job.*,jc.completeDate as completeDate,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->where([
					"job.jobId" => $jobId,
					"jc.fiscalYear" => $year,
					"jc.startMonth" => (int)$month,
				])
				->andFilterWhere([
					"js.stepId" => $stepId
				])
				->groupBy('jc.jobId')
				->orderBy('jc.completeDate DESC');
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		$stepName = '';
		if ($stepId != '') {

			$stepName = Step::stepName($stepId);
		}
		$text = ($day + 1) . " " . ModelMaster::shotMonthText((int)$month) . " " . $year . '<br>' . $stepName;
		return $this->render('show_job_date', [
			"dataProviderJob" => $dataProviderJob,
			"month" => $month,
			"year" => $year,
			"day" => $day, "text" => $text
		]);
	}

	public function actionDetailMonthlyOnProcess($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$year = $param["year"];
		$month = $param["month"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$stepId = $param["stepId"];
		//throw new Exception(print_r($param, true));
		$jobId = [];
		$jobOnProcess = Job::find()
			->select('jc.completeDate,job.jobId,jc.jobCategoryId,js.completeDate as jsCompleteDate')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
			->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
			->where([
				"job.branchId" => $branchId,
				"job.status" => [1, 4],
				"job.jobTypeId" => $jobTypeId,
				"job.categoryId" => 1,
				"jc.status" => JobCategory::STATUS_INPROCESS,
				"jc.fiscalYear" => $year,
				"jc.startMonth" => (int)$month,
				"jr.responsibility" => [2, 3]
			])
			->andFilterWhere([
				"job.teamId" => $teamId,
				"jr.employeeId" => $personId,
				"js.stepId" => $stepId
			])
			->orderBy('job.jobId')
			->groupBy('jc.jobId')
			->asArray()
			->all();
		$jobId = [];
		if (isset($jobOnProcess) && count($jobOnProcess) > 0) {
			$i = 0;
			foreach ($jobOnProcess as $inprocess) :
				$jobId[$i] = $inprocess["jobId"];
				$i++;
			endforeach;
		}
		$query = [];
		if (count($jobId) > 0) {
			$query = Job::find()
				->select('job.*,jc.completeDate as completeDate,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->where([
					"job.jobId" => $jobId,
					"jc.fiscalYear" => $year,
					"jc.startMonth" => (int)$month,
				])
				->andFilterWhere([
					"js.stepId" => $stepId
				])
				->groupBy('jc.jobId')
				->orderBy('jc.completeDate DESC');
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		$stepName = '';
		if ($stepId != '') {

			$stepName = Step::stepName($stepId);
		}
		$text = ModelMaster::shotMonthText((int)$month) . " " . $year . " (On Process)" . '<br>' . $stepName;
		return $this->render('show_job_date', [
			"dataProviderJob" => $dataProviderJob,
			"month" => $month,
			"year" => $year,
			"text" => $text
		]);
	}
	public function actionDetailYearly($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$year = $param["year"];
		$textMonth = $param["textMonth"];
		$selectMonth = $param["defaultMonth"];
		$stepId = $param["stepId"];
		$month = $param["month"]; //with in 
		//throw new exception(print_r($param, true));
		$jobs = Job::find()
			->select('jc.completeDate,job.jobId,job.jobName,jc.startMonth,js.completeDate as jsCompleteDate')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
			->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
			->where([
				"job.branchId" => $branchId,
				"job.status" => [1, 4],
				"job.jobTypeId" => $jobTypeId,
				"job.categoryId" => 6,
				"jc.status" => JobCategory::STATUS_COMPLETE,
				"jc.fiscalYear" => $year,
				"jr.responsibility" => [2, 3],
				//
			])
			->andFilterWhere([
				"jc.startMonth" => $selectMonth != '' ? (int)$selectMonth : null,
				"job.teamId" => $teamId,
				"jr.employeeId" => $personId,
				"js.stepId" => $stepId
			])
			->asArray()
			->groupBy('jc.jobId')
			->all();

		//throw new exception(print_r($param, true));
		if (($month == 99 || (int)$month == 6) && $month != 0) {
			//throw new exception('all');
			$start = 0;
			$end = 0;
		} else {
			//throw new exception('not');
			$start = ((int)$month * 30) + 1;
			$end = ((int)$month * 30) + 30;
		}

		$jobId = [];
		$i = 0;
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $job) :
				if ($stepId != '') {
					$completeDateUse = $job["jsCompleteDate"];
				} else if ($job["completeDate"] != '') {
					$completeDateUse = $job["completeDate"];
				} else {
					$completeDateUse = '';
				}
				if ($completeDateUse != '') {
					$completeDate = explode(' ', $completeDateUse);
					$fullDateArr = explode('-', $completeDate[0]);
					if ($selectMonth == '') {
						$calculateMonth = $job["startMonth"];
					} else {
						$calculateMonth = $selectMonth;
					}
					$days = $this->calculateDays($year, (int)$calculateMonth, $completeDate[0]);
					if ($start > 0 && $end > 0) {
						if ($days >= $start && $days <= $end) {
							$jobId[$i] = $job["jobId"];
						}
					} else {
						if ($month == 6) {
							if ($days > 180) {
								$jobId[$i] = $job["jobId"];
							}
						} else {
							$jobId[$i] = $job["jobId"];
						}
					}
				}
				$i++;
			endforeach;
		}
		if (count($jobId) > 0) {
			$query = Job::find()
				->select('job.*,jc.completeDate as completeDate,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->where([
					"job.jobId" => $jobId,
					"jc.fiscalYear" => $year,

				])
				->andFilterWhere([
					"js.stepId" => $stepId
				])
				->groupBy('jc.jobId')
				->orderBy('jc.completeDate DESC');
		} else {
			$query = [];
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		if ($month == 6) {
			$textMonth = "over than 180 days";
		}
		$stepName = '';
		if ($stepId != '') {

			$stepName = Step::stepName($stepId);
		}
		$showText = $year . " " . ModelMaster::shotMonthText((int)$selectMonth) . " Complete in $textMonth" . '<br>' . $stepName;
		if ($month == "all") {
			$showText = "Total " . $year . " " . ModelMaster::shotMonthText((int)$selectMonth);
		}
		return $this->render('yearly_job', [
			"dataProviderJob" => $dataProviderJob,
			"text" => $showText
		]);
	}
	public function actionDetailYearlyDay($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$year = $param["year"];
		$stepId = $param["stepId"];
		$period = $param["period"];
		$selectMonth = $param["selectMonth"];
		if ($period != "over") {
			$start = $period - 4;
			$end = $param["period"];
		} else {
			$start = 0;
			$end = 0;
		}
		$jobs = Job::find()
			->select('jc.completeDate,job.jobId,job.jobName,jc.startMonth,jc.fiscalYear,js.completeDate as jsCompleteDate')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
			->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
			->where([
				"job.branchId" => $branchId,
				"job.status" => [1, 4],
				"job.jobTypeId" => $jobTypeId,
				"job.categoryId" => 6,
				"jc.status" => JobCategory::STATUS_COMPLETE,
				"jc.fiscalYear" => $year,
				"jr.responsibility" => [2, 3]
			])
			->andFilterWhere([
				"jc.startMonth" => $selectMonth != '' ? (int)$selectMonth : null,
				"job.teamId" => $teamId,
				"jr.employeeId" => $personId,
				"js.stepId" => $stepId
			])
			->asArray()
			->groupBy('jc.jobId')
			->all();

		$i = 0;
		$jobId = [];
		$temp = [];
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $job) :
				if ($stepId != '') {
					$completeDateUse = $job["jsCompleteDate"];
				} else if ($job["completeDate"] != '') {
					$completeDateUse = $job["completeDate"];
				} else {
					$completeDateUse = '';
				}
				if ($completeDateUse != '') {
					$completeDate = explode(' ', $completeDateUse);
					$fullDateArr = explode('-', $completeDate[0]);
					if ($selectMonth == '') {
						$calculateMonth = $job["startMonth"];
					} else {
						$calculateMonth = $selectMonth;
					}
					$days = $this->calculateDays($year, (int)$calculateMonth, $completeDate[0]);
					if ($start > 0 && $end > 0) {

						if ($days >= $start && $days <= $end) {
							$jobId[$i] = $job["jobId"];
						}
					} else {
						if ($days > 180) {
							$jobId[$i] = $job["jobId"];
						}
					}
				}
				$i++;
			endforeach;
		}
		if (count($jobId) > 0) {
			$query = Job::find()
				->select('job.*,jc.completeDate as completeDate,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->where([
					"job.jobId" => $jobId,
					"jc.fiscalYear" => $year,
				])
				->andFilterWhere([
					"js.stepId" => $stepId
				])
				->groupBy('jc.jobId')
				->orderBy('jc.completeDate DESC');
		} else {
			$query = [];
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		if ($period != "over") {
			$textMonth = $start . " - " . $end . " days.";
			$showText = $year . " " . ModelMaster::shotMonthText((int)$selectMonth) . " Complete in $textMonth";
		} else {
			$showText = $year . " " . ModelMaster::shotMonthText((int)$selectMonth) . " Complete over than 180 days";
		}
		$stepName = '';
		if ($stepId != '') {
			$stepName = Step::stepName($stepId);
			$showText .= '<br>' . $stepName;
		}

		return $this->render('yearly_job', [
			"dataProviderJob" => $dataProviderJob,
			"text" => $showText
		]);
	}
	public function actionDetailYearlyOnprocess($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$year = $param["year"];
		$month = $param["month"];
		$stepId = $param["stepId"];
		$jobId = [];
		$jobOnProcess = Job::find()
			->select('jc.completeDate,job.jobId,jc.jobCategoryId,js.completeDate as jsCompleteDate')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_responsibility jr", "jr.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
			->where([
				"job.branchId" => $branchId,
				"job.status" => [1, 4],
				"job.jobTypeId" => $jobTypeId,
				"job.categoryId" => 6,
				"jc.status" => JobCategory::STATUS_INPROCESS,
				"jc.fiscalYear" => $year,
				"jr.responsibility" => [2, 3]
			])
			->andFilterWhere([
				"jc.startMonth" => $month != '' ? (int)$month : null,
				"job.teamId" => $teamId,
				"jr.employeeId" => $personId,
				"js.stepId" => $stepId
			])
			->orderBy('job.jobId')
			->groupBy('jc.jobId')
			->asArray()
			->all();
		if (isset($jobOnProcess) && count($jobOnProcess)) {
			$i = 0;
			foreach ($jobOnProcess as $inprocess) :
				$jobId[$i] = $inprocess["jobId"];
				$i++;
			endforeach;
		}
		$query = [];
		if (isset($jobId) && count($jobId) > 0) {
			$query = Job::find()
				->select('job.*,jc.completeDate as completeDate,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate')
				->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
				->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
				->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
				->where([
					"job.jobId" => $jobId,
					"jc.fiscalYear" => $year,
				])
				->andFilterWhere([
					"jc.startMonth" => $month != '' ? (int)$month : null,
					"js.stepId" => $stepId
				])
				->groupBy('jc.jobId')
				->orderBy('jc.completeDate DESC');
		}
		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		$stepName = '';
		if ($stepId != '') {
			$stepName = Step::stepName($stepId);
		}
		$text = $year . " " . ModelMaster::shotMonthText((int)$month) . " (On Process)" . '<br>' . $stepName;
		return $this->render('yearly_job', [
			"dataProviderJob" => $dataProviderJob,
			"month" => $month,
			"text" => $text
		]);
	}
	public function actionJobType()
	{
		$employeeBranchId = Employee::employeeBranch();
		$jobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName')
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"jt.status" => 1,
				"jt.branchId" => $employeeBranchId
			])
			->orderBy('jt.jobTypeName')
			->groupBy('job.jobTypeId')
			->asArray()
			->all();
		$teams = Team::find()->select('teamId,teamName')->where(["branchId" => $employeeBranchId, "status" => 1])->all();
		$branch = Branch::find()->where(["status" => 1])->orderBy("branchName")->asArray()->all();
		$employeeBranch = Branch::find()->select('branchId,branchName')->where(["branchId" => $employeeBranchId])->asArray()->one();
		$firstJobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName,job.updateDateTime')
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"jt.status" => 1,
				"jt.branchId" => $employeeBranchId
			])
			->orderBy('jt.jobTypeName')
			->groupBy('job.jobTypeId')
			->asArray()
			->one();
		$steps = Step::find()
			->select('stepId,stepName')
			->where([
				"status" => Step::STATUS_ACTIVE,
				"jobTypeId" => $firstJobType["jobTypeId"]
			])
			->orderBy('sort')
			->asArray()
			->all();
		$lastestStep = Step::find()
			->select('stepId')
			->where([
				"status" => Step::STATUS_ACTIVE,
				"jobTypeId" => $firstJobType["jobTypeId"]
			])
			->orderBy('sort DESC')
			->asArray()
			->one();
		$completeAll = 1;
		$stepName = [];
		$complete = [];
		$currentfiscalYear = FiscalYear::currentFiscalYear();
		$selectYear = $currentfiscalYear;
		if (isset($steps) && count($steps) > 0) {
			foreach ($steps as $step) :
				$complete[$step["stepId"]] = 0;
				$stepName[$step["stepId"]] = $step["stepName"];
			endforeach;
		}
		$jobs = Job::find()
			->select('job.jobId')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->where([
				"job.status" => [1, 4],
				"jc.status" => [1, 4],
				"job.jobTypeId" => $firstJobType["jobTypeId"],
				"job.branchId" => $employeeBranchId,
				"jc.fiscalYear" => $currentfiscalYear,
			])
			->orderBy('job.jobId')
			->groupby('jc.jobId')
			->asArray()
			->all();
		if (isset($jobs) && count($jobs) > 0) {
			$i = 0;
			foreach ($jobs as $job) :
				$currentCompleteStep = JobStep::currentCompleteStep($job["jobId"], null, $selectYear);
				if (isset($currentCompleteStep["stepId"])) {
					if (isset($complete[$currentCompleteStep["stepId"]])) {
						$complete[$currentCompleteStep["stepId"]]++;
					} else {
						$complete[$currentCompleteStep["stepId"]] = 1;
					}
					if ($currentCompleteStep["stepId"] != $lastestStep["stepId"]) {
						$completeAll = 0;
					}
				}

				$i++;
			endforeach;
		}
		$values = [];
		$xData = [];
		$data = [];
		//$text='',
		$colors = Chart::setColor();
		if (count($complete) > 0) {
			$index = 0;
			$step = 1;
			foreach ($complete as $stepId => $dataVirtual) :
				$data[$index] = $dataVirtual;
				$xData[$index] = "Step $step";
				$values[$index] = [
					"Step $step", $dataVirtual
				];
				$index++;
				$step++;

			endforeach;
		}
		$months = ModelMaster::month();
		$fiscalYear = FiscalYear::allFiscalYear();
		return $this->render('job_type_index', [
			"jobType" => $jobType,
			"teams" => $teams,
			"branch" => $branch,
			"person" => [],
			"employeeBranch" => $employeeBranch,
			"chartName" => $firstJobType["jobTypeName"],
			"jobTypeId" => $firstJobType["jobTypeId"],
			"xData" => $xData,
			"values" => $values,
			"complete" => $complete,
			"stepName" => $stepName,
			"teamId" => null,
			"personId" => null,
			"colors" => $colors,
			"months" => $months,
			"fiscalYear" => $fiscalYear,
			"selectYear" => $selectYear,
			"selectMonth" => null,
			"completeAll" => $completeAll


		]);
	}
	public function actionFilterJobTypeAnalysis()
	{
		$branchId = $_POST["branchId"];
		$jobTypeId = $_POST["jobTypeId"];
		$teamId = $_POST["teamId"];
		$personId = $_POST["personId"];
		$selectMonth = $_POST["month"];
		$selectYear = $_POST["year"];

		$firstJobType = JobType::find()
			->select('jobTypeName,jobTypeId')
			->where(["jobTypeId" => $jobTypeId])
			->asArray()
			->one();
		$steps = Step::find()
			->select('stepId,stepName')
			->where([
				"status" => Step::STATUS_ACTIVE,
				"jobTypeId" => $jobTypeId
			])
			->orderBy('sort')
			->asArray()
			->all();
		$lastestStep = Step::find()
			->select('stepId')
			->where([
				"status" => Step::STATUS_ACTIVE,
				"jobTypeId" => $jobTypeId
			])
			->orderBy('sort DESC')
			->asArray()
			->one();
		$completeAll = 1;
		$stepName = [];
		$complete = [];
		$currentStep = [];
		if ($selectYear == '') {
			$selectYear = FiscalYear::currentFiscalYear();
		}
		if (isset($steps) && count($steps) > 0) {
			foreach ($steps as $step) :
				$stepName[$step["stepId"]] = $step["stepName"];
				$complete[$step["stepId"]] = 0;
			endforeach;
		}
		$jobs = Job::find()
			->select('job.jobId')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_responsibility rs", "rs.jobId=job.jobId")
			->where([
				"job.status" => [1, 4],
				"jc.status" => [1, 4],
				"job.jobTypeId" => $firstJobType["jobTypeId"],
				"job.branchId" => $branchId,
				"jc.fiscalYear" => $selectYear,
			])
			->andFilterWhere([
				"job.teamId" => $teamId,
				"jr.employeeId" => $personId,
				"jc.startMonth" => $selectMonth
			])
			->orderBy('job.jobId')
			->groupby('jc.jobId')
			->asArray()->all();
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $job) :
				$currentCompleteStep = JobStep::currentCompleteStep($job["jobId"], $selectMonth, $selectYear);
				if (isset($currentCompleteStep["stepId"])) {
					if (isset($complete[$currentCompleteStep["stepId"]])) {
						$complete[$currentCompleteStep["stepId"]]++;
					} else {
						$complete[$currentCompleteStep["stepId"]] = 1;
					}
					if ($currentCompleteStep["stepId"] != $lastestStep["stepId"]) {
						$completeAll = 0;
					}
				}
			endforeach;
		}
		$values = [];
		$xData = [];
		$data = [];
		//$text='',
		if (count($complete) > 0) {
			$index = 0;
			$step = 1;
			foreach ($complete as $stepId => $dataVirtual) :
				$data[$index] = $dataVirtual * 1;
				$xData[$index] = "Step $step";
				$values[$index] = [
					"Step $step", $dataVirtual
				];
				$index++;
				$step++;
			endforeach;
		}
		return $this->redirect(Yii::$app->homeUrl . 'mms/analysis/filter-job-type/' . ModelMaster::encodeParams([
			"person" => "",
			"branchId" => $branchId,
			"chartName" => $firstJobType["jobTypeName"],
			"jobTypeId" => $firstJobType["jobTypeId"],
			"xData" => $xData,
			"values" => $values,
			"complete" => $complete,
			"stepName" => $stepName,
			"teamId" => $teamId,
			"personId" => $personId,
			"selectMonth" => $selectMonth,
			"selectYear" => $selectYear,
			"completeAll" => $completeAll
		]));
	}
	public function actionFilterJobType($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$personId = $param["personId"];
		$branchId = $param["branchId"];
		$chartName = $param["chartName"];
		$jobTypeId = $param["jobTypeId"];
		$xData = $param["xData"];
		$values = $param["values"];
		$complete = $param["complete"];
		$stepName = $param["stepName"];
		$teamId = $param["teamId"];
		$selectYear = $param["selectYear"];
		$selectMonth = $param["selectMonth"];
		$completeAll = $param["completeAll"];
		$jobType = Job::find()
			->select('job.jobTypeId,jt.jobTypeName')
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=job.jobTypeId")
			->where([
				"job.status" => [1, 4],
				"jt.status" => 1,
				"jt.branchId" => $branchId
			])
			->orderBy('jt.jobTypeName')
			->groupBy('job.jobTypeId')
			->asArray()
			->all();
		$employeeBranch = Branch::find()->select('branchId,branchName')->where(["branchId" => $branchId])->asArray()->one();
		$teams = Team::find()->select('teamId,teamName')->where(["branchId" => $branchId, "status" => 1])->all();
		$branch = Branch::find()->where(["status" => 1])->orderBy("branchName")->asArray()->all();
		$persons = [];
		if ($teamId != '') {
			$persons = Employee::find('employeeId,employeeNickName')
				->where(["teamId" => $teamId, "status" => Team::STATUS_ACTIVE])->asArray()->all();
		}
		$colors = Chart::setColor();
		$months = ModelMaster::month();
		$fiscalYear = FiscalYear::allFiscalYear();
		return $this->render('job_type_index', [
			"jobType" => $jobType,
			"teams" => $teams,
			"branch" => $branch,
			"person" => $persons,
			"employeeBranch" => $employeeBranch,
			"chartName" => $chartName,
			"jobTypeId" => $jobTypeId,
			"xData" => $xData,
			"values" => $values,
			"complete" => $complete,
			"stepName" => $stepName,
			"teamId" => $teamId,
			"personId" => $personId,
			"colors" => $colors,
			"months" => $months,
			"selectMonth" => $selectMonth,
			"selectYear" => $selectYear,
			"fiscalYear" => $fiscalYear,
			"completeAll" => $completeAll

		]);
	}
	public function actionDetailJobTypeStep($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$jobTypeId = $param["jobTypeId"];
		$stepId = $param["stepId"];
		$teamId = $param["teamId"];
		$personId = $param["personId"];
		$branchId = $param["branchId"];
		$fiscalYear = $param["selectYear"];
		$selectMonth = $param["selectMonth"];
		//throw new exception(print_r($param, true));
		//$fiscalYear = FiscalYear::currentFiscalYear();
		$jobs = Job::find()
			->select('job.jobId')
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_responsibility rs", "rs.jobId=job.jobId")
			->where([
				"job.status" => [1, 4],
				"jc.status" => [1, 4],
				"jc.fiscalYear" => $fiscalYear,
				"job.jobTypeId" => $jobTypeId,
				"job.branchId" => $branchId
			])
			->andFilterWhere([
				"job.teamId" => $teamId,
				"rs.employeeId" => $personId,
				"jc.startMonth" => $selectMonth
			])
			->orderBy('job.jobId')
			->groupby('jc.jobId')
			->asArray()
			->all();
		$jobId = [];
		//throw new exception(count($jobs));
		if (isset($jobs) && count($jobs) > 0) {
			$i = 0;
			foreach ($jobs as $job) :
				$currentCompleteStep = JobStep::currentCompleteStep($job["jobId"], $selectMonth, $fiscalYear);
				if (isset($currentCompleteStep["stepId"]) && $currentCompleteStep["stepId"] == $stepId) {
					$jobId[$i] = $job["jobId"];
				}
				$i++;
			endforeach;
		}

		$query = Job::find()
			//->select('job.*,js.completeDate as jsCompleteDate')
			->select('job.*,jc.completeDate as completeDate,jc.targetDate as jcTargetDate,js.completeDate as jsCompleteDate,js.dueDate')
			->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
			->JOIN("LEFT JOIN", "job_category jc", "jc.jobId=job.jobId")
			->JOIN("LEFT JOIN", "job_step js", "js.jobCategoryId=jc.jobCategoryId")
			->where([
				"job.jobId" => $jobId,
				"jc.fiscalYear" => $fiscalYear,
			])->andFilterWhere([
				"job.teamId" => $teamId,
				"jc.startMonth" => $selectMonth
			])
			->groupBy('js.jobId')
			->orderBy('js.completeDate DESC');

		$dataProviderJob = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		$date = $fiscalYear . " " . ModelMaster::shotMonthText($selectMonth);
		$stepName = Step::stepName($stepId);
		$stepSort = Step::sort($stepId);
		$text = JobType::jobTypeName($jobTypeId) . " ($date)<br>$stepSort. $stepName";
		return $this->render('job_type_list', [
			"dataProviderJob" => $dataProviderJob,
			"text" => $text,
			"stepName" => $stepName
		]);
	}
	public function calculateDays($year, $month, $completeDate)
	{
		date_default_timezone_set("Asia/Bangkok");
		$day = '01';
		$completeDateArr = explode('-', $completeDate);

		if ($completeDateArr[0] == $year && (int)$month == 12) {
			$yearStartCal = $year;
			$month = 12;
		}
		if ($completeDateArr[0] == $year && (int)$month != 12) {
			$yearStartCal = $year;
			if ((int)$month != (int)$completeDateArr[1]) {
				$month = (int)$month + 1;
			}
		}
		if ($completeDateArr[0] != $year) {

			if ((int)$month == 12) {
				$month = '01';
				$yearStartCal = $year + 1;
			} else {
				$month = (int)$month + 1;
				$yearStartCal = $year;
			}
		}
		if ($month < 10) {
			$month = "0" . (int)$month;
		}
		$start = $yearStartCal . "-" . $month . "-" . $day;

		$startToTime = strtotime($start);
		$completeDateToTime = strtotime($completeDate);
		$diff = $completeDateToTime - $startToTime;
		$diffDate = floor($diff / 86400); //จำนวนวันที่ต่างกัน
		return $diffDate + 1;
	}
}
