<?php

namespace frontend\modules\setting\controllers;

use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Country;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Job;
use frontend\models\lower_management\JobType;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\SectionHasPosition;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\Type;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

/**
 * Default controller for the `setting` module
 */
class StructureController extends Controller
{
	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
		//return $this->render('index');
	}
	public function actionBranch()
	{
		$branches = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->asArray()->all();
		$countries = Country::find()->select('countryId,countryName')
			->where(["hasBranch" => 1])
			->orderBy('countryName')
			->asArray()->all();
		return $this->render('branch', [
			"branches" => $branches,
			"countries" => $countries
		]);
	}
	public function actionCreateBranch()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["branchName"])) {
			$branch = new Branch();
			$branch->branchName = $_POST["branchName"];
			$branch->countryId = $_POST["countryId"];
			$branch->status = Branch::STATUS_ACTIVE;
			$branch->createDateTime = new Expression('NOW()');
			$branch->updateDateTime = new Expression('NOW()');
			$branch->save(false);
			return $this->redirect('branch');
		} else {
			return $this->redirect('branch');
		}
	}
	public function actionUpdateBranch()
	{
		$res = [];
		$branch = Branch::find()->where(["branchId" => $_POST["branchId"]])->one();
		$branch->branchName = $_POST["branchName"];
		$branch->countryId = $_POST["countryId"];
		$branch->updateDateTime = new Expression('NOW()');
		if ($branch->save(false)) {
			$res["status"] = true;
			$res["branchName"] = $_POST["branchName"];
			$res["countryName"] = Country::countryName($_POST["countryId"]);
			$res["countryId"] = $_POST["countryId"];
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionDisableBranch()
	{
		$branch = Branch::find()->where(["branchId" => $_POST["branchId"]])->one();
		$branch->status = Branch::STATUS_DISABLE;
		$branch->updateDateTime = new Expression('NOW()');
		if ($branch->save(false)) {
			Position::updateAll(["status" => Position::STATUS_DISABLE], ["branchId" => $_POST["branchId"]]);
			Section::updateAll(["status" => Section::STATUS_DISABLE], ["branchId" => $_POST["branchId"]]);
			Team::updateAll(["status" => Team::STATUS_DISABLE], ["branchId" => $_POST["branchId"]]);
			Employee::updateAll(["status" => Employee::STATUS_DELETED], ["branchId" => $_POST["branchId"]]);
			JobType::updateAll(["status" => JobType::STATUS_DISABLE], ["branchId" => $_POST["branchId"]]);
			Job::updateAll(["status" => Job::STATUS_DELETED], ["branchId" => $_POST["branchId"]]);
		}
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionPosition()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$positions = Position::find()
			->select('position.*,b.branchName')
			->JOIN("LEFT JOIN", "branch b", "b.branchId=position.branchId")
			->where(["position.status" => Position::STATUS_ACTIVE])
			->orderBy("position.branchId")
			->asArray()
			->all();
		$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->all();
		return $this->render('position', [
			"positions" => $positions,
			"branch" => $branch
		]);
	}

	public function actionCreatePosition()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["positionName"])) {
			$position = new Position();
			$position->positionName = $_POST["positionName"];
			$position->positionDetail = $_POST["positionDetail"];
			$position->branchId = $_POST["branch"];
			$position->status = Position::STATUS_ACTIVE;
			$position->createDateTime = new Expression('NOW()');
			$position->updateDateTime = new Expression('NOW()');
			$position->save(false);
			return $this->redirect('position');
		} else {
			return $this->redirect('position');
		}
	}
	public function actionUpdatePosition()
	{
		$res = [];
		$position = Position::find()->where(["positionId" => $_POST["positionId"]])->one();
		$position->positionName = $_POST["positionName"];
		$position->positionDetail = $_POST["detail"];
		$position->branchId = $_POST["branchId"];
		$position->updateDateTime = new Expression('NOW()');
		if ($position->save(false)) {
			$res["status"] = true;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionDisablePosition()
	{
		$position = Position::find()->where(["positionId" => $_POST["positionId"]])->one();
		$position->status = Position::STATUS_DISABLE;
		$position->updateDateTime = new Expression('NOW()');
		if ($position->save(false)) {
			/*$employee = Employee::find()->where(["positionId" => $_POST["positionId"]])->all();
			if (isset($employee) && count($employee) > 0) {
				foreach ($employee as $em) :
					Job::updateAll(["status" => Job::STATUS_DELETED], ["branchId" => $_POST["branchId"]]);
				endforeach;
			}*/
			Employee::updateAll(["status" => Employee::STATUS_DELETED], ["positionId" => $_POST["positionId"]]);
			SectionHasPosition::deleteAll(["positionId" => $_POST["positionId"]]);
		}
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionSearchPosition()
	{
		$res = [];
		$text = '';
		$position = Position::find()
			->select('b.branchName,position.*')

			->JOIN("LEFT JOIN", "branch b", "b.branchId=position.branchId")
			->where(["position.status" => Position::STATUS_ACTIVE])
			->andFilterWhere(["b.branchId" => $_POST["branchId"]])
			->orderBy("b.branchName")
			->asArray()->all();
		$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->asArray()->all();
		$text .= $this->renderPartial('search_position', ["positions" => $position, "branch" => $branch]);
		if ($text != '') {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionSection()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$sections = Section::find()->where(["status" => Section::STATUS_ACTIVE])->orderby('branchId')->asArray()->all();
		$positionBranch = [];
		$sp = [];
		$oldsp = [];
		$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->asArray()->all();
		$position = Position::find()->where(["status" => Position::STATUS_ACTIVE])->all();
		if (isset($position) && count($position) > 0) {
			foreach ($position as $ps) :
				$positionBranch[$ps["branchId"]][$ps["positionId"]] = $ps["positionName"];
			endforeach;
		}
		if (isset($sections) && count($sections) > 0) {
			foreach ($sections as $section) :
				$sectionPosition = SectionHasPosition::find()->select('section_has_position.positionId,p.positionName')
					->JOIN("LEFT JOIN", "position p", "p.positionId=section_has_position.positionId")
					->where(["section_has_position.status" => 1, "section_has_position.sectionId" => $section["sectionId"]])
					->andWhere(["p.status" => Position::STATUS_ACTIVE])
					->orderBy("p.level")
					->asArray()
					->all();
				if (isset($sectionPosition) && count($sectionPosition) > 0) {
					foreach ($sectionPosition as $sps) :
						$sp[$section["sectionId"]][$sps["positionId"]] = Position::positionName($sps["positionId"]);
						$oldsp[$section["sectionId"]][$sps["positionId"]] = $sps["positionId"];
					endforeach;
				}
			endforeach;
		}
		return $this->render('section', [
			"sections" => $sections,
			"sp" => $sp,
			"oldsp" => $oldsp,
			"branch" => $branch,
			"position" => $position,
			"positionBranch" => $positionBranch
		]);
	}
	public function actionCreateSection()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["sectionName"])) {
			$section = new Section();
			$section->sectionName = $_POST["sectionName"];
			$section->branchId = $_POST["branch"];
			$section->status = Section::STATUS_ACTIVE;
			$section->createDateTime = new Expression('NOW()');
			$section->updateDateTime = new Expression('NOW()');
			if ($section->save(false)) {
				$sectionId = Yii::$app->db->lastInsertID;
				if (isset($_POST["position"]) && count($_POST["position"]) > 0) {
					foreach ($_POST["position"] as $position) :
						$sp = new SectionHasPosition();
						$sp->sectionId = $sectionId;
						$sp->positionId = $position;
						$sp->status = 1;
						$sp->createDateTime = new Expression('NOW()');
						$sp->updateDateTime = new Expression('NOW()');
						$sp->save(false);
					endforeach;
				}

				return $this->redirect('section');
			} else {
				return $this->redirect('section');
			}
		}
	}
	public function actionUpdateSection()
	{
		$res = [];
		$positionText = '';
		$section = Section::find()->where(["sectionId" => $_POST["sectionId"]])->one();
		$section->sectionName = $_POST["sectionName"];
		$section->branchId = $_POST["branchId"];
		$section->updateDateTime = new Expression('NOW()');
		SectionHasPosition::deleteAll(["sectionId" => $_POST["sectionId"]]);
		if (isset($_POST["position"]) && count($_POST["position"])) {
			foreach ($_POST["position"] as $postionId) :
				$position = new SectionHasPosition();
				$position->sectionId = $_POST["sectionId"];
				$position->positionId = $postionId;
				$position->status = 1;
				$position->createDateTime = new Expression('NOW()');
				$position->updateDateTime = new Expression('NOW()');
				$position->save(false);
			endforeach;
		}

		if ($section->save(false)) {
			$res["status"] = true;
			$res["sectionName"] = $_POST["sectionName"];
			$sectionPosition = SectionHasPosition::find()->select('section_has_position.positionId,p.positionName')
				->JOIN("LEFT JOIN", "position p", "p.positionId=section_has_position.positionId")
				->where(["section_has_position.status" => 1, "section_has_position.sectionId" => $_POST["sectionId"]])
				->andWhere(["p.status" => Position::STATUS_ACTIVE])
				->orderBy("p.level DESC")
				->asArray()
				->all();
			if (isset($sectionPosition) && count($sectionPosition) > 0) {
				$i = 1;
				foreach ($sectionPosition as $sps) :
					$positionText .= $i . ". " . Position::positionName($sps["positionId"]) . '<br>';
					$i++;
				endforeach;
			}
			$res["positionText"] = $positionText;
		}
		return json_encode($res);
	}
	public function actionDisableSection()
	{
		$section = Section::find()->where(["sectionId" => $_POST["sectionId"]])->one();
		$section->status = Section::STATUS_DISABLE;
		$section->updateDateTime = new Expression('NOW()');
		$section->save(false);
		SectionHasPosition::deleteAll(["sectionId" => $_POST["sectionId"]]);
		Team::updateAll(["status" => Team::STATUS_DISABLE], ["sectionId" => $_POST["sectionId"]]);
		Employee::updateAll(["status" => Employee::STATUS_DELETED], ["sectionId" => $_POST["sectionId"]]);
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionSearchSection()
	{
		$text = '';
		$sections = Section::find()
			->where(["status" => Section::STATUS_ACTIVE])
			->andFilterWhere(["branchId" => $_POST["branchId"]])
			->orderby('branchId')
			->asArray()
			->all();
		$positionBranch = [];
		$sp = [];
		$oldsp = [];
		$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->asArray()->all();
		$position = Position::find()->where(["status" => Position::STATUS_ACTIVE])->all();
		if (isset($position) && count($position) > 0) {
			foreach ($position as $ps) :
				$positionBranch[$ps["branchId"]][$ps["positionId"]] = $ps["positionName"];
			endforeach;
		}
		if (isset($sections) && count($sections) > 0) {
			foreach ($sections as $section) :
				$sectionPosition = SectionHasPosition::find()->select('section_has_position.positionId,p.positionName')
					->JOIN("LEFT JOIN", "position p", "p.positionId=section_has_position.positionId")
					->where(["section_has_position.status" => 1, "section_has_position.sectionId" => $section["sectionId"]])
					->andWhere(["p.status" => Position::STATUS_ACTIVE])
					->orderBy("p.level")
					->asArray()
					->all();
				if (isset($sectionPosition) && count($sectionPosition) > 0) {
					foreach ($sectionPosition as $sps) :
						$sp[$section["sectionId"]][$sps["positionId"]] = Position::positionName($sps["positionId"]);
						$oldsp[$section["sectionId"]][$sps["positionId"]] = $sps["positionId"];
					endforeach;
				}
			endforeach;
		}

		$text = $this->renderPartial('search_section', [
			"sections" => $sections,
			"sp" => $sp,
			"oldsp" => $oldsp,
			"branch" => $branch,
			"position" => $position,
			"positionBranch" => $positionBranch
		]);
		if ($text != '') {
			$res["status"] = true;
			$res["text"] = $text;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionSearchSectionTeam()
	{
		$branchId = $_POST["branchId"];
		$text = "<option value=''>Section</option>";
		$section = Section::find()
			->select('sectionName,sectionId')
			->where(["status" => Section::STATUS_ACTIVE, "branchId" => $branchId])
			->asArray()
			->all();
		if (isset($section) && count($section)) {
			foreach ($section as $b) :
				$text .= "<option value='" . $b["sectionId"] . "'>" . $b["sectionName"] . "</option>";
			endforeach;
		}
		$res = [];
		$res["text"] = $text;
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionFilterSearchSectionTeam()
	{
		$branchId = $_POST["branchId"];
		$sectionId = $_POST["sectionId"];
		$text = "";
		if ($sectionId != '' && $sectionId != '' && $sectionId != null) {
			$text .= "<option value='" . $sectionId . "'>" . Section::sectionName($sectionId) . "</option>";
			$text .= "<option value=''>Section</option>";
		} else {
			$text = "<option value=''>Section</option>";
		}

		$branch = Branch::find()->where(["status" => Branch::STATUS_ACTIVE])->asArray()->all();
		$section = Section::find()
			->select('sectionName,sectionId')
			->where(["status" => Section::STATUS_ACTIVE, "branchId" => $branchId])
			->asArray()
			->all();
		if (isset($section) && count($section)) {
			foreach ($section as $b) :
				$text .= "<option value='" . $b["sectionId"] . "'>" . $b["sectionName"] . "</option>";
			endforeach;
		}
		$teams = Team::find()
			->where(["status" => 1])
			->andFilterWhere(["branchId" => $branchId])
			->andFilterWhere(["sectionId" => $sectionId])
			->orderBy("teamName")->asArray()->all();
		$textRender = $this->renderPartial('search_team', ["teams" => $teams, "branch" => $branch]);
		$res = [];
		$res["text"] = $text;
		$res["textRender"] = $textRender;
		$res["status"] = true;
		return json_encode($res);
	}
	public function actionBranchPosition()
	{
		$text = '';
		$otherPosition = [];
		$select = [];
		$allSelect = [];
		if ($_POST["branchId"] == "") {
			$allPosition = Position::find()
				->select('positionId')
				->where(["status" => Position::STATUS_ACTIVE])
				->asArray()
				->all();
			if (isset($allPosition) && count($allPosition) > 0) {
				$i = 0;
				foreach ($allPosition as $p) :
					$allSelect[$i] = $p["positionId"];
					$i++;
				endforeach;
			}
		} else {
			$position = Position::find()
				->select('positionId')
				->where(["status" => Position::STATUS_ACTIVE, "branchId" => $_POST["branchId"]])
				->asArray()
				->all();
			$otherPosition = Position::find()
				->select('positionId')
				->where(["status" => Position::STATUS_ACTIVE])
				->andWhere("branchId!=" . $_POST["branchId"])
				->asArray()
				->all();

			if (isset($position) && count($position) > 0) {
				$i = 0;
				foreach ($position as $p) :
					$select[$i] = $p["positionId"];
					$i++;
				endforeach;
			}
			if (isset($otherPosition) && count($otherPosition) > 0) {
				$i = 0;
				foreach ($otherPosition as $p) :
					$unSelect[$i] = $p["positionId"];
					$i++;
				endforeach;
			}
		}

		if (count($select) > 0) {
			$res["status"] = true;
			$res["select"] = $select;
			$res["unSelect"] = $unSelect;
		} else {
			$allPosition = Position::find()
				->select('positionId')
				->where(["status" => Position::STATUS_ACTIVE])
				->asArray()
				->all();
			if (isset($allPosition) && count($allPosition) > 0) {
				$i = 0;
				foreach ($allPosition as $p) :
					$allSelect[$i] = $p["positionId"];
					$i++;
				endforeach;
			}
			$res["status"] = false;
		}
		$res["allPosition"] = $allSelect;
		$res["countSelect"] = count($select);
		return json_encode($res);
	}
	public function actionTeam()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$isAdmin = EmployeeType::isAdmin();
		$employeeBranch = Employee::employeeBranch();

		if ($isAdmin == 1) {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE])
				->asArray()
				->all();
			$teams = Team::find()
				->where(["status" => 1])
				->orderBy("teamName")
				->asArray()
				->all();
		} else {
			$branch = Branch::find()->select('branchId,branchName')
				->where(["status" => Branch::STATUS_ACTIVE, "branchId" => $employeeBranch])
				->asArray()
				->all();
			$teams = Team::find()
				->where(["status" => 1, "branchId" => $employeeBranch])
				->orderBy("teamName")
				->asArray()
				->all();
		}



		return $this->render('team', [
			"teams" => $teams,
			"branch" => $branch
		]);
	}
	public function actionCreateTeam()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["teamName"])) {
			$team = new Team();
			$team->teamName = $_POST["teamName"];
			$team->teamDetail = $_POST["teamDetail"];
			$team->sectionId = $_POST["sectionId"];
			$team->status = Team::STATUS_ACTIVE;
			$team->branchId = $_POST["branch"];
			$team->createDateTime = new Expression('NOW()');
			$team->updateDateTime = new Expression('NOW()');
			if ($team->save(false)) {
				return $this->redirect('team');
			} else {
				return $this->redirect('team');
			}
		}
	}
	public function actionUpdateTeam()
	{
		$res = [];
		$team = Team::find()->where(["teamId" => $_POST["teamId"]])->one();
		$team->teamName = $_POST["teamName"];
		$team->teamDetail = $_POST["detail"];
		$team->sectionId = $_POST["sectionId"];
		$team->branchId = $_POST["branchId"];
		$team->updateDateTime = new Expression('NOW()');
		if ($team->save(false)) {
			$res["status"] = true;
			$res["teamName"] = $_POST["teamName"];
			$res["teamDetail"] = $_POST["detail"];
			$res["sectionName"] = Section::sectionName($_POST["sectionId"]);
		}
		return json_encode($res);
	}
	public function actionDisableTeam()
	{
		$team = Team::find()->where(["teamId" => $_POST["teamId"]])->one();
		$team->status = Team::STATUS_DISABLE;
		if ($team->save(false)) {
			Employee::updateAll(["teamId" => Null], ["teamId" => $_POST["teamId"]]);
			Job::updateAll(["status" => Job::STATUS_DELETED], ["teamId" => $_POST["teamId"]]);
		}
		$res["status"] = true;
		return json_encode($res);
	}
}
