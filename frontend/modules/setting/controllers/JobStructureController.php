<?php

namespace frontend\modules\setting\controllers;

use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\AdditionalStep;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Category;
use frontend\models\lower_management\Client;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Field;
use frontend\models\lower_management\FieldGroup;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobStep;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\JobTypeStep;
use frontend\models\lower_management\Step;
use frontend\models\lower_management\SubFieldGroup;
use frontend\models\lower_management\Type;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yii;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `setting` module
 */
class JobStructureController extends Controller
{
	public function actionCategory()
	{
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$categories = Category::find()->where(["status" => 1])->orderBy('categoryName')->asArray()->all();
		return $this->render('category', [
			"categories" => $categories
		]);
	}
	public function actionCreateCategory()
	{
		$right = 'all';
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["categoryName"]) && trim($_POST["categoryName"])) {
			$category = new Category();
			$category->categoryName = $_POST["categoryName"];
			$category->status = 1;
			$category->createDateTime = new Expression('NOW()');
			$category->updateDateTime = new Expression('NOW()');
			$category->save(false);
		}
		return $this->redirect('category');
	}
	public function actionUpdateCategory()
	{
		$res = [];
		$category = Category::find()->where(["categoryId" => $_POST["categoryId"]])->one();
		$category->categoryName = $_POST["categoryName"];
		$category->updateDateTime = new Expression('NOW()');
		if ($category->save(false)) {
			$res["status"] = true;
			$res["categoryName"] = $_POST["categoryName"];
		}
		return json_encode($res);
	}
	public function actionDisableCategory()
	{
		$category = Category::find()->where(["categoryId" => $_POST["categoryId"]])->one();
		$category->status = Category::STATUS_DISABLE;
		$category->updateDateTime = new Expression('NOW()');
		if ($category->save(false)) {
			Job::updateAll(["status" => Job::STATUS_DELETED], ["categoryId" => $_POST["categoryId"]]);
		}
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionField()
	{
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranch = Employee::employeeBranch();
		if ($isAdmin == 1) {
			$branches = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
			$fields = Field::find()
				->select('field.*')
				->JOIN("LEFT JOIN", "branch b", "b.branchId=field.branchId")
				->where(["field.status" => 1])
				->orderBy('b.branchName ASC,field.fieldGroupId,field.fieldName')
				->asArray()
				->all();
		} else {
			$branches = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranch])
				->asArray()
				->all();
			$fields = Field::find()
				->select('field.*')
				->JOIN("LEFT JOIN", "branch b", "b.branchId=field.branchId")
				->where(["field.status" => 1, "field.branchId" => $employeeBranch])
				->orderBy('field.fieldGroupId,field.fieldName')
				->asArray()
				->all();
		}
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

		return $this->render('field', [
			"fields" => $fields,
			"branches" => $branches,
			"groupFields" => $groupFields,
			"isAdmin" => $isAdmin


		]);
	}
	public function actionCreateField()
	{
		$right = 'all';
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["fieldName"]) && trim($_POST["fieldName"]) != "") {
			$field = new Field();
			$field->fieldName = $_POST["fieldName"];
			$field->subFieldGroupId = $_POST["subFieldGroup"];
			$field->branchId = $_POST["branch"];
			$field->status = 1;
			$field->createDateTime = new Expression('NOW()');
			$field->updateDateTime = new Expression('NOW()');
			$field->save(false);
		}
		return $this->redirect('field');
	}
	public function actionUpdateField()
	{
		$res = [];
		$field = Field::find()->where(["fieldId" => $_POST["fieldId"]])->one();
		$field->fieldName = $_POST["fieldName"];
		$field->subFieldGroupId = $_POST["subFieldGroupId"];
		$field->branchId = $_POST["branchId"];
		$field->updateDateTime = new Expression('NOW()');
		if ($field->save(false)) {
			$res["status"] = true;
		}
		return json_encode($res);
	}
	public function actionDisableField()
	{
		$field = Field::find()->where(["fieldId" => $_POST["fieldId"]])->one();
		$field->status = Field::STATUS_DISABLE;
		$field->updateDateTime = new Expression('NOW()');
		if ($field->save(false)) {
			Job::updateAll(["status" => Job::STATUS_DELETED], ["fieldId" => $_POST["fieldId"]]);
		}
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionSearchField()
	{
		$res = [];
		$text = '';
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranch = Employee::employeeBranch();
		if ($isAdmin == 1) {
			$branches = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
		} else {
			$branches = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranch])
				->asArray()
				->all();
		}
		$field = Field::find()
			->select('fieldId,fieldName,branchId,fieldGroupId,subFieldGroupId')
			->where(["status" => Field::STATUS_ACTIVE])
			->andFilterWhere(["branchId" => $_POST["branchId"]])
			->andFilterWhere(["subFieldGroupId" => $_POST["subFieldGroupId"]])
			->orderBy("fieldName")
			->asArray()->all();
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

		$text .= $this->renderPartial('search_field', ["fields" => $field, "branch" => $branches, "groupFields" => $groupFields]);
		if ($text != '') {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}

	public function actionJobType()
	{
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$right = 'all';
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$jobTypeSteps = [];
		$steps = Step::find()->select('stepName,stepId')->where(["status" => Step::STATUS_ACTIVE])->asArray()->orderBy("sort")->all();
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranchId = Employee::employeeBranch();
		$employeeBranch = ["branchId" => $employeeBranchId, "branchName" => Branch::branchName($employeeBranchId)];
		if ($isAdmin == 1) {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->orderBy('branchName')
				->asArray()
				->all();
		} else {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranch])
				->orderBy('branchName')
				->asArray()
				->all();
		}
		$jobType = JobType::find()
			->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $employeeBranch])
			->orderBy("jobTypeName")
			->asArray()
			->all();
		if (isset($jobType) && count($jobType) > 0) {
			foreach ($jobType as $jt) :
				$typeSteps = Step::find()
					->select('stepName,stepId')
					->where(["jobTypeId" => $jt["jobTypeId"], "status" => Step::STATUS_ACTIVE])
					->orderBy('sort')
					->asArray()
					->all();
				if (isset($typeSteps) && count($typeSteps) > 0) {
					foreach ($typeSteps as $step) :
						$jobTypeSteps[$jt["jobTypeId"]][$step["stepId"]] = Step::stepName($step["stepId"]);
					endforeach;
				}
			endforeach;
		}
		return $this->render('job_type', [
			"jobType" => $jobType,
			"steps" => $steps,
			"jobTypeSteps" => $jobTypeSteps,
			"branch" => $branch,
			"employeeBranch" => $employeeBranch
		]);
	}
	public function actionCreateJobType()
	{
		$right = 'all';
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["jobTypeName"]) && trim($_POST["jobTypeName"]) != "") {
			$type = new JobType();
			$type->jobTypeName = $_POST["jobTypeName"];
			$type->jobTypeDetail = $_POST["jobTypeDetail"];
			$type->branchId = $_POST["branch"];
			$type->status = JobType::STATUS_ACTIVE;
			$type->createDateTime = new Expression('NOW()');
			$type->updateDateTime = new Expression('NOW()');
			$type->save(false);
			// if (isset($_POST["jopTypeStep"]) && count($_POST["jopTypeStep"]) > 0) {
			// 	$jobTypeId = Yii::$app->db->lastInsertID;
			// 	foreach ($_POST["jopTypeStep"] as $stepId) :
			// 		$jts = new JobTypeStep();
			// 		$jts->jobTypeId = $jobTypeId;
			// 		$jts->stepId = $stepId;
			// 		$jts->status = 1;
			// 		$jts->createDateTime = new Expression('NOW()');
			// 		$jts->updateDateTime = new Expression('NOW()');
			// 		$jts->save(false);
			// 	endforeach;
			// }
			return $this->redirect('job-type');
		}
	}
	public function actionUpdateJobType()
	{
		$res = [];
		$text = '';
		if (isset($_POST["jobTypeName"]) && trim($_POST["jobTypeName"]) != "") {
			$type = JobType::find()->where(["jobTypeId" => $_POST["jobTypeId"]])->one();
			$type->jobTypeName = $_POST["jobTypeName"];
			$type->jobTypeDetail = $_POST["jobTypeDetail"];
			$type->status = JobType::STATUS_ACTIVE;
			$type->createDateTime = new Expression('NOW()');
			$type->updateDateTime = new Expression('NOW()');
			$type->save(false);
			$res["status"] = true;
			$res["jobType"] = $_POST["jobTypeName"];
			$res["detail"] = $_POST["jobTypeDetail"];
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionDisableJobType()
	{
		$type = JobType::find()->where(["jobTypeId" => $_POST["jobTypeId"]])->one();
		$type->status = JobType::STATUS_DISABLE;
		$type->updateDateTime = new Expression('NOW()');
		if ($type->save(false)) {
			Job::updateAll(["status" => Job::STATUS_DELETED], ["jobTypeId" => $_POST["jobTypeId"]]);
			Step::updateAll(["status" => Step::STATUS_DISABLE], ["jobTypeId" => $_POST["jobTypeId"]]);
		}
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionSearchJobType()
	{


		return $this->redirect(
			Yii::$app->homeUrl . 'setting/job-structure/search-job-type-result/' .
				ModelMaster::encodeParams(
					[
						"branchId" => $_POST["branchId"],
						"jobTypeId" => $_POST["jobTypeId"]
					]
				)
		);
	}
	public function actionSearchJobTypeResult($hash)
	{
		$param = ModelMaster::decodeParams($hash);
		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$jobTypeSteps = [];
		$jobTypeFilter = [];
		$steps = Step::find()->select('stepName,stepId')->where(["status" => Step::STATUS_ACTIVE])->asArray()->orderBy("")->all();
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranchId = Employee::employeeBranch();
		$employeeBranch = ["branchId" => $employeeBranchId, "branchName" => Branch::branchName($employeeBranchId)];
		if ($isAdmin == 1) {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->orderBy('branchName')
				->asArray()
				->all();
		} else {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranchId])
				->orderBy('branchName')
				->asArray()
				->all();
		}
		$jobTypeInBranch = JobType::find()->where(["branchId" => $branchId, "jobTypeId" => $jobTypeId])->asArray()->one();
		if (isset($jobTypeInBranch) && !empty($jobTypeInBranch)) {
			$searchJobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["jobTypeId" => $jobTypeId, "status" => JobType::STATUS_ACTIVE])
				->asArray()
				->one();
		} else {
			$searchJobType = [];
		}

		$jobType = JobType::find()
			->where(["status" => JobType::STATUS_ACTIVE])
			->andFilterWhere(["branchId" => $branchId, "jobTypeId" => $jobTypeId])
			->orderBy("createDateTime DESC")
			->asArray()->all();
		if (isset($jobType) && count($jobType) > 0) {
			foreach ($jobType as $jt) :
				$typeSteps = Step::find()
					->select('stepName,stepId')
					->where(["jobTypeId" => $jt["jobTypeId"], "status" => Step::STATUS_ACTIVE])
					->asArray()
					->orderBy('sort')
					->all();
				if (isset($typeSteps) && count($typeSteps) > 0) {
					foreach ($typeSteps as $step) :
						$jobTypeSteps[$jt["jobTypeId"]][$step["stepId"]] = Step::stepName($step["stepId"]);
					endforeach;
				}
			endforeach;
		}
		$jobTypeFilter = JobType::find()
			->where(["status" => JobType::STATUS_ACTIVE])
			->andFilterWhere(["branchId" => $branchId])
			->orderBy("jobTypeName")
			->asArray()
			->all();
		$branchFilter = ["branchId" => $branchId, "branchName" => Branch::branchName($branchId)];

		return $this->render('search_job_type', [
			"branch" => $branch,
			"branchFilter" => $branchFilter,
			"jobTypeSteps" => $jobTypeSteps,
			"jobType" => $jobType,
			"employeeBranch" => $employeeBranch,
			"branchFilter" => $branchFilter,
			"searchJobType" => $searchJobType,
			"jobTypeFilter" => $jobTypeFilter
		]);
	}
	public function actionJobStep()
	{
		$right = 'all';
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$isAdmin = EmployeeType::isAdmin();
		$employee = Employee::find()->select('branchId')->where(["employeeId" => Yii::$app->user->id])->asArray()->one();
		$employeeBranch = ["branchId" => $employee["branchId"], "branchName" => Branch::branchName($employee["branchId"])];
		$jobType = [];
		if ($isAdmin == 1) {
			$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])
				->orderBy('branchName')
				->asArray()
				->all();
		} else {
			$branch = Branch::find()
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employee["branchId"]])
				->orderBy('branchName')
				->asArray()
				->all();
		}
		$steps = [];
		$jobType = JobType::find()->select('jobTypeId,jobTypeName')
			->orderBy('jobTypeName')
			->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $employee["branchId"]])->all();
		$firstJobType = JobType::find()->select('jobTypeId,jobTypeName')
			->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $employee["branchId"]])
			->asArray()
			->orderBy("jobTypeName")
			->one();
		if (isset($firstJobType) && !empty($firstJobType)) {
			$steps = Step::find()
				->select('step.stepName,step.stepId,step.jobTypeId,jt.jobTypeName,step.sort')
				->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=step.jobTypeId")
				->where(["step.status" => Step::STATUS_ACTIVE, "jt.status" => JobType::STATUS_ACTIVE, "jt.jobTypeId" => $firstJobType["jobTypeId"]])
				->asArray()
				->orderBy("jt.jobTypeName,step.sort")
				->all();
		}




		return $this->render('job_step', [
			"steps" => $steps,
			"branch" => $branch,
			"jobType" => $jobType,
			"firstJobType" => $firstJobType,
			"employeeBranch" => $employeeBranch
		]);
	}
	public function actionCreateStep()
	{
		$right = 'all';
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["stepName"]) && count($_POST["stepName"]) > 0) {
			$i = 0;
			foreach ($_POST["stepName"] as $index => $name) :
				$step = new Step();
				$step->stepName = $name;
				$step->jobTypeId = $_POST["jobTypeId"];
				$step->sort = isset($_POST["sort"][$index]) ? $_POST["sort"][$index] : $i + 1;
				$step->status = Step::STATUS_ACTIVE;
				$step->createDateTime = new Expression('NOW()');
				$step->updateDateTime = new Expression('NOW()');
				$step->save(false);
				$i++;
			endforeach;

			$res["status"] = true;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionUpdateStep()
	{
		$res = [];
		if (isset($_POST["stepName"]) && trim($_POST["stepName"]) != "") {
			$step = Step::find()->where(["stepId" => $_POST["stepId"]])->one();
			$step->stepName = $_POST["stepName"];
			$step->jobTypeId = $_POST["jobTypeId"];
			$step->sort = $_POST["sort"];
			$step->status = JobType::STATUS_ACTIVE;
			$step->createDateTime = new Expression('NOW()');
			$step->updateDateTime = new Expression('NOW()');
			if ($step->save(false)) {
				$res["status"] = true;
			} else {
				$res["status"] = false;
			}
		}
		return json_encode($res);
	}
	public function actionDisableStep()
	{
		$step = Step::find()->where(["stepId" => $_POST["stepId"]])->one();
		$step->status = JobType::STATUS_DISABLE;
		$step->updateDateTime = new Expression('NOW()');
		if ($step->save(false)) {
			JobStep::updateAll(["status" => Step::STATUS_DISABLE], ["stepId" => $_POST["stepId"]]);
			AdditionalStep::updateAll(["status" => 99], ["stepId" => $_POST["stepId"]]);
		}

		$res["status"] = true;
		return json_encode($res);
	}
	public function actionJobBranch()
	{
		$option = "";
		$jobType = JobType::find()
			->where(["branchId" => $_POST["branchId"], "status" => JobType::STATUS_ACTIVE])
			->orderBy("jobTypeName")
			->asArray()
			->all();
		if (isset($jobType) && count($jobType) > 0) {
			foreach ($jobType as $jt) :
				$option .= "<option value='" . $jt["jobTypeId"] . "'>" . $jt["jobTypeName"] . "</option>";
			endforeach;
			$res["status"] = true;
			$res["text"] = $option;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionSearchJobStep()
	{
		/*$res = [];
		$text = '';
		$jobTypeText = '<option value="">Job Type</option>';
		$step = Step::find()
			->select('b.branchName,jt.jobTypeName,step.stepName,step.sort,step.stepId,step.jobTypeId,jt.branchId')
			->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=step.jobTypeId")
			->JOIN("LEFT JOIN", "branch b", "b.branchId=jt.branchId")
			->where(["step.status" => Step::STATUS_ACTIVE])
			->andFilterWhere(["b.branchId" => $_POST["branchId"]])
			->andFilterWhere(["jt.jobTypeId" => $_POST["jobTypeId"]])
			->orderBy("b.branchName,jt.jobTypeName,step.sort")
			->asArray()->all();
		$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->asArray()->all();
		$jobTypes = JobType::find()
			->where(["branchId" => $_POST["branchId"], "status" => JobType::STATUS_ACTIVE])
			->orderBy("jobTypeName")
			->asArray()
			->all();
		if (isset($jobTypes) && count($jobTypes) > 0) {
			foreach ($jobTypes as $jt) :
				$jobTypeText .= "<option value='" . $jt["jobTypeId"] . "'>" . $jt["jobTypeName"] . "</option>";
			endforeach;
		}
		$text .= $this->renderPartial('job_step_search', ["steps" => $step, "branch" => $branch]);*/

		$branchId = $_POST["branchId"];
		$jobTypeId = $_POST["jobTypeId"];
		return $this->redirect(Yii::$app->homeUrl . 'setting/job-structure/search-result/' . ModelMaster::encodeParams(["branchId" => $branchId, "jobTypeId" => $jobTypeId]));
		// $res["status"] = true;
		// $res["text"] = $text;
		// $res["jobTypeText"] = $jobTypeText;
		// return json_encode($res);
	}
	public function actionSearchResult($hash)
	{
		$param = ModelMaster::decodeParams($hash);

		$branchId = $param["branchId"];
		$jobTypeId = $param["jobTypeId"];
		$isAdmin = EmployeeType::isAdmin();
		$employee = Employee::find()->select('branchId')->where(["employeeId" => Yii::$app->user->id])->asArray()->one();
		$employeeBranch = ["branchId" => $employee["branchId"], "branchName" => Branch::branchName($employee["branchId"])];

		if ($isAdmin == 1) {
			$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->asArray()->orderBy('branchName')->all();
		} else {
			$branch = Branch::find()
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employee["branchId"]])
				->asArray()
				->orderBy('branchName')
				->all();
		}
		$jobType = [];
		$jobType = JobType::find()->select('jobTypeId,jobTypeName')
			->orderBy('jobTypeName')
			->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $employee["branchId"]])
			->all();
		$jobTypeFilter = JobType::find()->select('jobTypeId,jobTypeName')
			->orderBy('jobTypeName')
			->where(["status" => JobType::STATUS_ACTIVE, "branchId" => $branchId])->asArray()
			->all();
		$jobTypeInBranch = JobType::find()->where(["branchId" => $branchId, "jobTypeId" => $jobTypeId])->asArray()->one();
		if (isset($jobTypeInBranch) && !empty($jobTypeInBranch)) {
			$searchJobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["jobTypeId" => $jobTypeId, "status" => JobType::STATUS_ACTIVE])
				->asArray()
				->one();
		} else {
			$searchJobType = JobType::find()
				->select('jobTypeId,jobTypeName')
				->where(["branchId" => $branchId, "status" => JobType::STATUS_ACTIVE])
				->orderBy("jobTypeName")
				->asArray()
				->one();
		}
		if (isset($searchJobType) && !empty($searchJobType)) {
			$step = Step::find()
				->select('b.branchName,jt.jobTypeName,step.stepName,step.sort,step.stepId,step.jobTypeId,jt.branchId')
				->JOIN("LEFT JOIN", "job_type jt", "jt.jobTypeId=step.jobTypeId")
				->JOIN("LEFT JOIN", "branch b", "b.branchId=jt.branchId")
				->where(["step.status" => Step::STATUS_ACTIVE])
				->andFilterWhere(["b.branchId" => $branchId])
				->andFilterWhere(["jt.jobTypeId" => $searchJobType["jobTypeId"]])
				->orderBy("b.branchName,jt.jobTypeName,step.sort")
				->asArray()->all();
		} else {
			$step = [];
		}
		$searchBranch = Branch::find()->select('branchId,branchName')->where(["branchId" => $branchId])->asArray()->one();

		return $this->render('job_step_search', [
			"steps" => $step,
			"branch" => $branch,
			"jobType" => $jobType,
			"searchBranch" => $searchBranch,
			"searchJobType" => $searchJobType,
			"employeeBranch" => $employeeBranch,
			"jobTypeFilter" => $jobTypeFilter
		]);
	}
	public function actionUpdateJobTypeDocument()
	{
		$res = [];
		$jobType = JobType::find()->where(["jobTypeId" => $_POST["jobTypeId"]])->one();
		$jobType->jobTypeDetail = $_POST["text"];
		$jobType->save(false);
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionImportJobTypeStep()
	{
		$right = 'all';
		//$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_PIC1 . "," . Type::TYPE_PIC2 . "," . Type::TYPE_CREATER . "," . Type::TYPE_APPROVER;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$new = [];
		$update = [];
		$updateJobType = 0;
		$newJobType = 0;
		$newStep = 0;
		$updateStep = 0;
		$count = 0;
		if (isset($_POST["branch"])) {
			$branchId = $_POST["branch"];
			$imageObj = UploadedFile::getInstanceByName("clientFile");
			if (isset($imageObj) && !empty($imageObj)) {
				$urlFolder = Path::getHost() . 'file/jobTypeStep/';
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
					unset($sheetData[0]);
					$i = 0;
					foreach ($sheetData as $data) :
						if ($i >= 1) {
							//$employeeNo = $data[0];
							if ($data[0] != "" && $data[1] != "") {
								$jobTypeName = $data[0];
								$jobTypeDetail = $data[1];
								$isNewJobType = JobType::checkNewJobType($branchId, $jobTypeName);
								if ($isNewJobType == 1) { //have this name
									$jobType = JobType::find()
										->where(["jobTypeName" => $jobTypeName, "branchId" => $branchId])
										->one();
									$jobTypeId = $jobType->jobTypeId;
									$jobType->jobTypeDetail = $jobTypeDetail;
									$jobType->updateDateTime = new Expression('NOW()');
									$jobType->status = 1;
									$jobType->save(false);
									$updateJobType++;
								} else {
									$jobType = new JobType();
									$jobType->jobTypeName = $jobTypeName;
									$jobType->jobTypeDetail = $jobTypeDetail;
									$jobType->branchId = $branchId;
									$jobType->createDateTime = new Expression('NOW()');
									$jobType->updateDateTime = new Expression('NOW()');
									$jobType->save(false);
									$jobType->status = 1;
									$jobTypeId = Yii::$app->db->lastInsertID;
									$newJobType++;
								}
							}
							$stepName = $data[3];
							$sort = $data[2];
							if (trim($stepName) != '') {
								$isNewStep = Step::checkNewStep($stepName, $jobTypeId);
								if ($isNewStep == 1) { //have this step name
									$step = Step::find()
										->where(["stepName" => $stepName, "jobTypeId" => $jobTypeId])
										->one();
									$stepId = $step->stepId;
									$step->sort = $sort;
									$step->updateDateTime = new Expression('NOW()');
									$step->status = Step::STATUS_ACTIVE;
									$step->save(false);
									$update[$jobTypeId][$stepId] = [
										"stepName" => $stepName,
									];
									$updateStep++;
								} else {
									$step = new Step();
									$step->stepName = $stepName;
									$step->status = Step::STATUS_ACTIVE;
									$step->jobTypeId = $jobTypeId;
									$step->sort = $sort;
									$step->createDateTime = new Expression('NOW()');
									$step->updateDateTime = new Expression('NOW()');
									$step->save(false);
									$stepId = Yii::$app->db->lastInsertID;
									$new[$jobTypeId][$stepId] = [
										"stepName" => $stepName,
									];
									$newStep++;
								}
								$count++;
							}
						}
						$i++;
					endforeach;
				}
				unlink($pathSave);
			}
		}
		$branch = Branch::find()
			->select('branchName,branchId')
			->where(["status" => 1])->asArray()
			->orderBy('branchName')
			->all();
		return $this->render('import', [
			"count" => $count,
			"branch" => $branch,
			"new" => $new,
			"update" => $update,
			"updateJobType" => $updateJobType,
			"newJobType" => $newJobType,
			"newStep" => $newStep,
			"updateStep" => $updateStep
		]);
	}
	public function actionImportField()
	{

		$right = Type::TYPE_ADMIN . "," . Type::TYPE_GM . "," . Type::TYPE_MANAGER;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$updateField = 0;
		$newField = 0;
		$count = 0;
		$branch = Branch::find()
			->select('branchName,branchId')
			->where(["status" => 1])->asArray()
			->orderBy('branchName')
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
		if (isset($_POST["branch"])) {

			$branchId = $_POST["branch"];
			$imageObj = UploadedFile::getInstanceByName("clientFile");
			if (isset($imageObj) && !empty($imageObj)) {

				$urlFolder = Path::getHost() . 'file/field/';
				if (!file_exists($urlFolder)) {
					mkdir($urlFolder, 0777, true);
				}
				$file = $imageObj->name;
				$filenameArray = explode('.', $file);
				$countArrayFile = count($filenameArray);
				$fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[$countArrayFile - 1];
				$pathSave = $urlFolder . $fileName;
				if ($imageObj->saveAs($pathSave)) {
					//throw new exception(11);
					$reader = new Xlsx();
					$spreadsheet = $reader->load($pathSave);
					$sheetData = $spreadsheet->getActiveSheet()->toArray();
					//unset($sheetData[0]);
					$i = 0;
					foreach ($sheetData as $data) :
						if ($i >= 1) {
							if ($data[0] != "" && $data[1] != "") {

								$subFieldGroupName = $data[0];
								$fieldName = $data[1];
								$subFieldGroupId = Field::SubFieldGroupId($subFieldGroupName);
								$isField = Field::checkNewField($branchId, $fieldName);
								if ($subFieldGroupId != 0) {
									if ($isField == 1) { //have this name
										$field = Field::find()
											->where(["fieldName" => $fieldName, "branchId" => $branchId])
											->one();
										$field->updateDateTime = new Expression('NOW()');
										$field->status = 1;
										$field->save(false);
										$updateField++;
									} else {
										$field = new Field();
										$field->fieldName = $fieldName;
										$field->subFieldGroupId = $subFieldGroupId;
										$field->branchId = $branchId;
										$field->createDateTime = new Expression('NOW()');
										$field->updateDateTime = new Expression('NOW()');
										$field->status = 1;
										$field->save(false);
										$newField++;
									}
								}
								$count++;
							}
						}
						$i++;
					endforeach;
				}
				unlink($pathSave);
			}
		}
		return $this->render('import_field', [
			"branch" => $branch,
			"groupFields" => $groupFields,
			"count" => $count,
			"newField" => $newField,
			"updateField" => $updateField,

		]);
	}
	public function actionSetDeleteStep()
	{
		$deleteSteps = Step::find()->where(["status" => Step::STATUS_DISABLE])->asArray()->all();
		if (isset($deleteSteps) && count($deleteSteps) > 0) {
			foreach ($deleteSteps as $step) :
				JobStep::updateAll(["status" => Step::STATUS_DISABLE], ["stepId" => $step["stepId"]]);
				AdditionalStep::updateAll(["status" => 99], ["stepId" => $step["stepId"]]);
			endforeach;
		}
	}
}
