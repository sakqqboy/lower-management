<?php

namespace frontend\modules\job\controllers;

use common\helpers\Path;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobCategory;
use frontend\models\lower_management\JobResponsibility;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\Type;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use yii\web\Controller;

class JobTypeCalendarController extends Controller
{
	public function actionIndex()
	{
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$fag = 0;
		$employeeType = EmployeeType::findEmployeeType();
		$rightAll = [Type::TYPE_ADMIN, Type::TYPE_GM];
		if (count($employeeType) > 0) {
			foreach ($employeeType as $all) :
				if (in_array($all, $rightAll)) {
					$fag = 1;
				}
			endforeach;
		}
		if ($fag == 1) { //can see other branch
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
			$teams = Team::find()
				->select('teamId,teamName')
				->where(["status" => Team::STATUS_ACTIVE])
				->asarray()
				->orderBy('teamName')
				->all();
		} else {
			$branchId = Employee::employeeBranch();
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
			$teams = Team::find()
				->select('teamId,teamName')
				->where(["status" => Team::STATUS_ACTIVE, "branchId" => $branchId])
				->asarray()
				->orderBy('teamName')
				->all();
		}
		return $this->render('index', [
			"branches" => $branch,
			"jobTypes" => $jobType,
			"fag" => $fag,
			"teams" => $teams
		]);
	}
	public function actionFindJobType()
	{
		$branchId = $_POST["branchId"];
		$textJobType = '<option value="">Select JobType</option>';
		$textTeam = '<option value="">Select Team</option>';
		$jobTypes = JobType::find()->where(["branchId" => $branchId, "status" => 1])->orderBy('jobTypeName')->asArray()->all();
		if (isset($jobTypes) && count($jobTypes) > 0) {
			foreach ($jobTypes as $jobType) :
				$textJobType .= '<option value="' . $jobType["jobTypeId"] . '">' . $jobType["jobTypeName"] . '</option>';
			endforeach;
		}
		$teams = Team::find()->where(["branchId" => $branchId, "status" => 1])->orderBy('teamName')->asArray()->all();
		if (isset($teams) && count($teams) > 0) {
			foreach ($teams as $team) :
				$textTeam .= '<option value="' . $team["teamId"] . '">' . $team["teamName"] . '</option>';
			endforeach;
		}
		$res["textJobType"] = $textJobType;
		$res["teamBranch"] = $textTeam;
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionClientJobType()
	{
		if (Yii::$app->user->id) {
		} else {
			return $this->redirect(Yii::$app->homeUrl . 'site/login');
		}
		$branchId = $_POST["branchId"];
		$jobTypeId = $_POST["jobTypeId"];
		$teamId = $_POST["teamId"];
		$compare = $_POST["compare"];
		$jobs = Job::find()
			->select('job.jobId,job.clientId,job.teamId')
			->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
			->where(["job.jobTypeId" => $jobTypeId, "job.branchId" => $branchId, "job.status" => [1, 4]])
			->andFilterWhere(["teamId" => $teamId])
			->asArray()
			->orderBy('c.clientName')
			->all();
		$data = [];
		$pic = [];
		$month = [];
		$team = [];
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $job) :
				$jobCategory = JobCategory::find()
					->select('jobCategoryId,targetDate,startMonth')
					->where(["jobId" => $job["jobId"], "status" => [1, 4]])
					->asArray()
					->orderBy('status')
					->one();
				if (isset($jobCategory) && !empty($jobCategory)) {
					$jobSteps = JobStep::find()
						->select('job_step.jobStepId,job_step.dueDate,job_step.status,s.stepId,job_step.firstDueDate,
						job_step.dueDate,job_step.status,job_step.completeDate')
						->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
						->where([
							"job_step.jobCategoryId" => $jobCategory["jobCategoryId"],
							"job_step.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]
						])
						->asArray()
						->orderBy('s.sort')
						->all();
					if (isset($jobSteps) && count($jobSteps) > 0) {
						foreach ($jobSteps as $step) :
							if ($compare > 0) {
								$lastCompleteDate = JobCategory::lastCompletDate($job["jobId"], $jobCategory["jobCategoryId"], $step["stepId"], $compare);
							}
							if ($compare == 0) {
								$data[$job["clientId"]][$step["stepId"]] = [
									"firstDueDate" => $step["firstDueDate"],
									"dueDate" => $step["dueDate"],
									"classText" => JobStep::createClassText($step["status"], $step["dueDate"]),
									"completeDate" => $step["completeDate"],
								];
							}
							if ($compare == 1) {
								$data[$job["clientId"]][$step["stepId"]] = [
									"firstDueDate" => $step["firstDueDate"],
									"dueDate" => $step["dueDate"],
									"classText" => JobStep::createClassText($step["status"], $step["dueDate"]),
									"completeDate" => $step["completeDate"],
									"lastCompleteDate" => isset($lastCompleteDate[0]) ? $lastCompleteDate[0] : '',
								];
							}
							if ($compare == 2) {
								$data[$job["clientId"]][$step["stepId"]] = [
									"firstDueDate" => $step["firstDueDate"],
									"dueDate" => $step["dueDate"],
									"classText" => JobStep::createClassText($step["status"], $step["dueDate"]),
									"completeDate" => $step["completeDate"],
									"lastCompleteDate" => isset($lastCompleteDate[0]) ? $lastCompleteDate[0] : '',
									"beforeLastCompleteDate" => isset($lastCompleteDate[1]) ? $lastCompleteDate[1] : ''
								];
							}

						endforeach;
					}
					$team[$job["clientId"]] = Team::teamNameExcel($job["teamId"]);
					$month[$job["clientId"]] = JobCategory::TargetMonth($jobCategory["startMonth"]);
				}
				$pic[$job["clientId"]] = [
					"pIc1" => Job::jobResponsibilityExcel($job["jobId"], JobResponsibility::PIC1),
					"pIc2" => Job::jobResponsibilityExcel($job["jobId"], JobResponsibility::PIC2),
				];
			endforeach;
		}
		$steps = Step::find()
			->select('stepId,stepName,sort')
			->where(["status" => Step::STATUS_ACTIVE, "jobTypeId" => $jobTypeId])
			->asArray()
			->orderBy('sort')
			->all();
		$jobType = JobType::find()
			->select('jobTypeName')
			->where(["jobTypeId" => $jobTypeId])
			->asArray()
			->one();
		$textResult = $this->renderAjax('result_client', [
			"data" => $data,
			"steps" => $steps,
			"jobTypeName" => $jobType["jobTypeName"],
			"jobTypeId" => $jobTypeId,
			"branchId" => $branchId,
			"branchName" => Branch::branchName($branchId),
			"pic" => $pic,
			"teamId" => $teamId,
			"teamName" => Team::teamNameExcel($teamId),
			"month" => $month,
			"team" => $team,
			"compare" => $compare
		]);
		$res["textResult"] = $textResult;
		if (count($data) > 0) {
			$res["status"] = true;
		} else {
			$res["status"] = false;
		}


		return json_encode($res);
	}
	public function actionExport($b, $j, $t, $c)
	{
		$branchId = $b;
		$jobTypeId = $j;
		$teamId = $t;
		$jobs = Job::find()
			->select('job.jobId,job.clientId,job.teamId')
			->JOIN("LEFT JOIN", "client c", "c.clientId=job.clientId")
			->where(["job.jobTypeId" => $jobTypeId, "job.branchId" => $branchId, "job.status" => [1, 4]])
			->andFilterWhere(['job.teamId' => $teamId])
			->asArray()
			->orderBy('c.clientName')
			->all();
		$data = [];
		$pic = [];
		$month = [];
		if (isset($jobs) && count($jobs) > 0) {
			foreach ($jobs as $job) :
				$jobCategory = JobCategory::find()
					->select('jobCategoryId,targetDate,startMonth')
					->where(["jobId" => $job["jobId"], "status" => [1, 4]])
					->asArray()
					->orderBy('status')
					->one();
				if (isset($jobCategory) && !empty($jobCategory)) {
					$jobSteps = JobStep::find()
						->select('job_step.jobStepId,job_step.dueDate,job_step.status,s.stepId,job_step.firstDueDate,
						job_step.dueDate,job_step.status,job_step.completeDate')
						->JOIN("LEFT JOIN", "step s", "s.stepId=job_step.stepId")
						->where([
							"job_step.jobCategoryId" => $jobCategory["jobCategoryId"],
							"job_step.status" => [JobStep::STATUS_INPROCESS, JobStep::STATUS_COMPLETE]
						])
						->asArray()
						->orderBy('s.sort')
						->all();
					if (isset($jobSteps) && count($jobSteps) > 0) {
						foreach ($jobSteps as $step) :
							if ($c > 0) {
								$lastCompleteDate = JobCategory::lastCompletDate($job["jobId"], $jobCategory["jobCategoryId"], $step["stepId"], $c);
							}
							if ($c == 2) {
								$data[$job["clientId"]][$step["stepId"]] = [
									"firstDueDate" => $step["firstDueDate"],
									"dueDate" => $step["dueDate"],
									"classText" => JobStep::createClassText($step["status"], $step["dueDate"]),
									"completeDate" => $step["completeDate"],
									"lastCompleteDate" => isset($lastCompleteDate[0]) ? $lastCompleteDate[0] : '',
									"beforeLastCompleteDate" => isset($lastCompleteDate[1]) ? $lastCompleteDate[1] : ''
								];
							}
							if ($c == 1) {
								$data[$job["clientId"]][$step["stepId"]] = [
									"firstDueDate" => $step["firstDueDate"],
									"dueDate" => $step["dueDate"],
									"classText" => JobStep::createClassText($step["status"], $step["dueDate"]),
									"completeDate" => $step["completeDate"],
									"lastCompleteDate" => isset($lastCompleteDate[0]) ? $lastCompleteDate[0] : ''
								];
							}
							if ($c == 0) {
								$data[$job["clientId"]][$step["stepId"]] = [
									"firstDueDate" => $step["firstDueDate"],
									"dueDate" => $step["dueDate"],
									"classText" => JobStep::createClassText($step["status"], $step["dueDate"]),
									"completeDate" => $step["completeDate"],
								];
							}
						endforeach;
					}
					$month[$job["clientId"]] = JobCategory::TargetMonth($jobCategory["startMonth"]);
					$team[$job["clientId"]] = Team::teamNameExcel($job["teamId"]);
				}

				$pic[$job["clientId"]] = [
					"pIc1" => Job::jobResponsibilityExcel($job["jobId"], JobResponsibility::PIC1),
					"pIc2" => Job::jobResponsibilityExcel($job["jobId"], JobResponsibility::PIC2),
				];
			endforeach;
		}
		$steps = Step::find()
			->select('stepId,stepName,sort')
			->where(["status" => Step::STATUS_ACTIVE, "jobTypeId" => $jobTypeId])
			->asArray()
			->orderBy('sort')
			->all();
		$jobType = JobType::find()
			->select('jobTypeName')
			->where(["jobTypeId" => $jobTypeId])
			->asArray()
			->one();
		$htmlExcel = $this->renderPartial('export', [
			"data" => $data,
			"steps" => $steps,
			"jobTypeName" => $jobType["jobTypeName"],
			"jobTypeId" => $jobTypeId,
			"branchId" => $branchId,
			"branchName" => Branch::branchName($branchId),
			"pic" => $pic,
			"month" => $month,
			"teamName" => Team::teamNameExcel($teamId),
			"team" => $team,
			"compare" => $c
		]);
		$spreadsheet = new Spreadsheet;
		$reader = new Html();
		$spreadsheet = $reader->loadFromString($htmlExcel);
		$spreadsheet->getDefaultStyle()->getFont()->setSize(12);
		$spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(40);
		$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$spreadsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFEBCD');
		$spreadsheet->getActiveSheet()->getStyle('B2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F0FFF0');
		$spreadsheet->getActiveSheet()->getStyle('A1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		$spreadsheet->getActiveSheet()->getStyle('A2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		$spreadsheet->getActiveSheet()->getStyle('C2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		$spreadsheet->getActiveSheet()->getStyle('D2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		$spreadsheet->getActiveSheet()->getStyle('E2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		$spreadsheet->getActiveSheet()->getStyle('F4:AZ300')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		$spreadsheet->getActiveSheet()->getStyle('A3:AZ3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
		for ($i = 'B'; $i <  'F'; $i++) {
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setWidth(13);
		}
		$a = 0;
		$i = 'F';
		while ($a < (count($steps) * (2 + $c))) { // column step
			$spreadsheet->getActiveSheet()->getStyle($i . '2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);
			$spreadsheet->getActiveSheet()->getStyle($i . '2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('E6E6FA');
			$spreadsheet->getActiveSheet()->getStyle($i . '2')->getAlignment()->setWrapText(true);
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setWidth(18);
			$spreadsheet->getActiveSheet()->getStyle($i . '2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
			$a++;
			$i++;
		}
		$b = 'A';
		while ($b < 'Z') { // title step client
			$spreadsheet->getActiveSheet()->getStyle($b . '3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

			$b++;
		}
		$highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
		$spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
		$spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
		for ($row = 4; $row <= $highestRow; $row++) {
			$spreadsheet->getActiveSheet()->getRowDimension("$row")->setRowHeight(25);
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
		return Yii::$app->response->sendFile($urlFolder, $jobType["jobTypeName"] . '.xlsx');
	}
}
