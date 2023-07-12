<?php

namespace frontend\modules\setting\controllers;

use common\helpers\Path;
use common\models\ModelMaster;
use Exception;
use frontend\models\lower_management\Branch;
use frontend\models\lower_management\Employee;
use frontend\models\lower_management\EmployeeType;
use frontend\models\lower_management\Position;
use frontend\models\lower_management\Section;
use frontend\models\lower_management\SectionHasPosition;
use frontend\models\lower_management\Team;
use frontend\models\lower_management\TeamPosition;
use frontend\models\lower_management\Type;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yii;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\UploadedFile;

/**
 * Default controller for the `setting` module
 */
class EmployeeController extends Controller
{
	public function actionIndex()
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

		return $this->render('index', [
			"employee" => $employee,
			"branch" => $branch,
			"userType" => $userType
		]);
	}

	public function actionCreateEmployee()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_GM;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$sections = Section::find()->select('sectionId,sectionName')->where(["status" => 1])->orderBy('sectionName')->asArray()->all();
		$position = Position::find()->select('positionId,positionName')->where(["status" => 1])->orderBy('positionName')->asArray()->all();
		$teams = Team::find()->select('teamId,teamName')->where(["status" => 1])->orderBy('teamName')->asArray()->all();
		$branchs = Branch::find()->select('branchId,branchName')->where(["status" => 1])->orderBy('branchName')->asArray()->all();
		$teamPosition = TeamPosition::find()->select('id,name')->where(["status" => 1])->orderBy('id')->asArray()->all();
		$userType = Type::find()->where(["status" => 1])->asArray()->orderBy('typeId')->all();
		if (isset($_POST["firstName"])) {
			$employee = Employee::find()
				->where(["email" => $_POST["email"]])
				->one();
			if (!isset($employee) || empty($employee)) {
				$employee = new Employee();
			}
			$employee->employeeFirstName = $_POST["firstName"];
			$employee->employeeLastName = $_POST["lastName"] != '' ? $_POST["lastName"] : '-';
			$employee->employeeNickName = $_POST["nickName"];
			$employee->birthDate = $_POST["birthDate"];
			$employee->email = $_POST["email"];
			$employee->username = $_POST["email"];
			$employee->telephoneNumber = $_POST["telNember"];
			$employee->gender = isset($_POST["gender"]) ? $_POST["gender"] : null;
			$employee->branchId = $_POST["branch"];
			$employee->sectionId = $_POST["section"];
			$employee->teamId = isset($_POST["team"]) ? $_POST["team"] : null;
			$employee->teamPositionId = isset($_POST["teamPosition"]) ? $_POST["teamPosition"] : null;
			$employee->positionId = $_POST["position"];
			$employee->status = 1;
			$employee->createDateTime = new Expression('NOW()');
			$employee->updateDateTime = new Expression('NOW()');
			$imageObj = UploadedFile::getInstanceByName("picture");
			if (isset($imageObj) && !empty($imageObj)) {
				$urlFolder = Path::getHost() . 'images/employee/';
				if (!file_exists($urlFolder)) {
					mkdir($urlFolder, 0777, true);
				}
				$file = $imageObj->name;
				$filenameArray = explode('.', $file);
				$fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[1];
				$fileNameScreenArr = explode('.', $fileName);
				$fileNameSave =  $fileNameScreenArr[0] . '.jpg';
				$pathScreen = $urlFolder . $fileNameSave;
				$imageObj->saveAs($pathScreen);
				$employee->picture = 'employee/' . $fileNameSave;
			}
			$emailArr = explode('@', $_POST["email"]);
			$email = $emailArr[0];
			$employee->password_hash = md5($email);
			if ($employee->save(false)) {

				if (isset($_POST["userType"]) && count($_POST["userType"]) > 0) {
					$employeeId = Yii::$app->db->lastInsertID;
					EmployeeType::deleteAll(["employeeId" => $employeeId]);
					$this->saveUserType($employeeId, $_POST["userType"]);
				}
				return $this->redirect('index');
			}
		}
		return $this->render('create', [
			"sections" => $sections,
			"position" => $position,
			"teams" => $teams,
			"branchs" => $branchs,
			"teamPosition" => $teamPosition,
			"userType" => $userType
		]);
	}
	public function actionUpdateEmployee($hash = false)
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_GM;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		if (isset($_POST["firstName"])) {
			$employee = Employee::find()->where(["employeeId" => $_POST["em"]])->one();
			$employee->employeeFirstName = $_POST["firstName"];
			$employee->employeeLastName = $_POST["lastName"];
			$employee->employeeNickName = $_POST["nickName"];
			$employee->birthDate = $_POST["birthDate"];
			$employee->email = $_POST["email"];
			$employee->username = $_POST["email"];
			$employee->telephoneNumber = $_POST["telNember"];
			$employee->gender = isset($_POST["gender"]) ? $_POST["gender"] : null;
			$employee->branchId = $_POST["branch"];
			$employee->sectionId = $_POST["section"];
			$employee->teamId = isset($_POST["team"]) ? $_POST["team"] : null;
			$employee->teamPositionId = isset($_POST["teamPosition"]) ? $_POST["teamPosition"] : null;
			$employee->positionId = $_POST["position"];
			$employee->status = 1;
			$employee->updateDateTime = new Expression('NOW()');
			$imageObj = UploadedFile::getInstanceByName("picture");
			if (isset($imageObj) && !empty($imageObj)) {
				//$urlFolder = Yii::$app->getBasePath() . '/' . 'web/images/employee/';
				$urlFolder = Path::getHost() . 'images/employee/';
				if (!file_exists($urlFolder)) {
					mkdir($urlFolder, 0777, true);
				}
				$oldImage = $employee->picture;
				$file = $imageObj->name;
				$filenameArray = explode('.', $file);
				$fileName = Yii::$app->security->generateRandomString(10) . '.' . $filenameArray[1];
				$fileNameScreenArr = explode('.', $fileName);
				$fileNameSave =  $fileNameScreenArr[0] . '.jpg';
				$pathScreen = $urlFolder . $fileNameSave;
				$imageObj->saveAs($pathScreen);
				$employee->picture = 'employee/' . $fileNameSave;
				if ($oldImage != null) {
					$pathImage = Yii::$app->getBasePath() . '/' . 'web/images/' . $oldImage;
					if (is_file($pathImage) && @unlink(unlink($pathImage))) {
					}
				}
			}
			if ($employee->save(false)) {
				if (isset($_POST["userType"]) && count($_POST["userType"]) > 0) {
					$employeeId = $_POST["em"];
					EmployeeType::deleteAll(["employeeId" => $employeeId]);
					$this->saveUserType($employeeId, $_POST["userType"]);
				}
				return $this->redirect('index');
			}
		}
		$params = ModelMaster::decodeParams($hash);
		$employeeId = $params["employeeId"];
		$currentType = [];
		$employee = Employee::find()
			->where(["employeeId" => $employeeId])
			->asArray()
			->one();
		$sections = Section::find()
			->select('sectionId,sectionName')->where(["status" => Section::STATUS_ACTIVE, "branchId" => $employee["branchId"]])
			->orderBy('sectionName')
			->asArray()
			->all();
		$position = Position::find()
			->select('positionId,positionName')
			->where(["status" => Position::STATUS_ACTIVE, "branchId" => $employee["branchId"]])
			->orderBy('positionName')
			->asArray()
			->all();
		$teams = Team::find()->select('teamId,teamName')
			->where(["status" => Team::STATUS_ACTIVE, "branchId" =>  $employee["branchId"]])
			->orderBy('teamName')
			->asArray()
			->all();
		$branchs = Branch::find()->select('branchId,branchName')
			->where(["status" => Branch::STATUS_ACTIVE])
			->orderBy('branchName')
			->asArray()
			->all();

		$teamPosition = TeamPosition::find()
			->select('id,name')
			->where(["status" => 1])
			->orderBy('id')
			->asArray()
			->all();
		$userType = Type::find()
			->where(["status" => 1])
			->asArray()
			->orderBy('typeId')
			->all();
		$currentUserType = EmployeeType::find()->select('typeId')->where(["employeeId" => $employeeId, "status" => 1])->asArray()->all();
		//throw new Exception(print_r($currentUserType, true));
		if (isset($currentUserType) && count($currentUserType) > 0) {
			foreach ($currentUserType as $current) :
				$currentType[$current["typeId"]] = true;
			endforeach;
		}

		return $this->render('update', [
			"sections" => $sections,
			"position" => $position,
			"teams" => $teams,
			"branchs" => $branchs,
			"employee" => $employee,
			"teamPosition" => $teamPosition,
			"userType" => $userType,
			"currentType" => $currentType
		]);
	}
	public function actionEmployeeDetail($hash)
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_GM;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$k = base64_decode(base64_decode($hash));
		$params = ModelMaster::decodeParams($hash);
		$employeeId = $params["employeeId"];
		$employee = Employee::find()->where(["employeeId" => $employeeId])->asArray()->one();
		return $this->render('detail', [
			"employee" => $employee
		]);
	}
	public function actionPosition()
	{
		$text = '<option value="">' . $_POST["text"] . '</option>';
		$res = [];
		$positions = SectionHasPosition::find()
			->select('p.positionId,p.positionName')
			->JOIN("LEFT JOIN", "position p", "p.positionId=section_has_position.positionId")
			->where(["section_has_position.sectionId" => $_POST["id"]])
			->orderBy("p.positionName")
			->asArray()
			->all();

		if (isset($positions) && count($positions) > 0) {
			foreach ($positions as $position) :
				$text .= '<option value="' . $position["positionId"] . '">' . $position["positionName"] . '</option>';
			endforeach;
		}
		$res["status"] = true;
		$res["text"] = $text;
		return json_encode($res);
	}
	public function actionSection()
	{
		$text = '<option value="">' . $_POST["text"] . '</option>';
		$textTeam = '<option value="">Team</option>';
		$textPosition = '<option value="">Position</option>';
		$res = [];
		$section = Section::find()
			->select('sectionId,sectionName')
			->where(["branchId" => $_POST["id"]])
			->orderBy("sectionName")
			->asArray()
			->all();

		if (isset($section) && count($section) > 0) {
			foreach ($section as $s) :
				$text .= '<option value="' . $s["sectionId"] . '">' . $s["sectionName"] . '</option>';
			endforeach;
		}
		$team = Team::find()
			->select('teamId,teamName')
			->where(["branchId" => $_POST["id"], "status" => Team::STATUS_ACTIVE])
			->orderBy("teamName")
			->asArray()
			->all();
		if (isset($team) && count($team) > 0) {
			foreach ($team as $t) :
				$textTeam .= '<option value="' . $t["teamId"] . '">' . $t["teamName"] . '</option>';
			endforeach;
		}
		$position = Position::find()
			->select('positionId,positionName')
			->where(["branchId" => $_POST["id"]])
			->orderBy("positionName")
			->asArray()
			->all();
		if (isset($position) && count($position) > 0) {
			foreach ($position as $t) :
				$textPosition .= '<option value="' . $t["positionId"] . '">' . $t["positionName"] . '</option>';
			endforeach;
		}
		$res["status"] = true;
		$res["text"] = $text;
		$res["textTeam"] = $textTeam;
		$res["textPosition"] = $textPosition;
		return json_encode($res);
	}
	public function actionDisableEmployee()
	{
		$res = [];
		$employee = Employee::find()->where(["employeeId" => $_POST["id"]])->one();
		$employee->status = Employee::STATUS_DELETED;
		$employee->updateDateTime = new Expression('NOW()');
		if ($employee->save(false)) {
			$res["status"] = true;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionDisableSomeEmployee()
	{
		$res = [];
		if (isset($_POST["employeeId"]) && count($_POST["employeeId"]) > 0) {
			foreach ($_POST["employeeId"] as $employeeId) :
				if ($employeeId != "") {
					$employee = Employee::find()->where(["employeeId" => $employeeId])->one();
					$employee->status = Employee::STATUS_DELETED;
					$employee->updateDateTime = new Expression('NOW()');
					$employee->save(false);
				}
			endforeach;
			$res["status"] = true;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
	public function actionDuplicateEmail()
	{
		if ($_POST["employeeId"] == 0) {
			$employee = Employee::find()->where(["email" => $_POST["email"], "status" => 1])->one();
		} else {
			$employeeId = $_POST["employeeId"];
			$employee = Employee::find()
				->where(["email" => $_POST["email"]])
				->andWhere("employeeId!=$employeeId")
				->one();
		}
		$res = [];
		if (isset($employee) && !empty($employee)) {
			$res["status"] = false;
		} else {
			$res["status"] = true;
		}
		return json_encode($res);
	}
	public function saveUserType($employeeId, $selectType)
	{
		if (count($selectType) > 0) {
			foreach ($selectType as $type) :
				$empt = new EmployeeType();
				$empt->employeeId = $employeeId;
				$empt->typeId = $type;
				$empt->status = 1;
				$empt->updateDateTime = new Expression('NOW()');
				$empt->createDateTime = new Expression('NOW()');
				$empt->save(false);
			endforeach;
		}
	}
	public function actionSearchEmployee()
	{
		$res = [];
		if (isset($_POST["typeId"]) && $_POST["typeId"] != '') {
			if (isset($_POST["searchName"]) && $_POST["searchName"] != '') {
				$employee = Employee::find()
					->JOIN("LEFT JOIN", "employee_type et", "employee.employeeId=et.employeeId")
					->where(["et.typeId" => $_POST["typeId"]])
					->andFilterWhere(["employee.branchId" => $_POST["branchId"]])
					->andFilterWhere(["employee.sectionId" => $_POST["sectionId"]])
					->andFilterWhere(["employee.positionId" => $_POST["positionId"]])
					->andFilterWhere(["employee.teamId" => $_POST["teamId"]])
					->andWhere("employee.employeeFirstName LIKE '" . $_POST["searchName"] . "%' or employee.email LIKE '" . $_POST["searchName"] . "%'")
					->orderBy("employee.status,employee.employeeFirstName ASC")
					->asArray()
					->all();
			} else {
				$employee = Employee::find()
					->JOIN("LEFT JOIN", "employee_type et", "employee.employeeId=et.employeeId")
					->where(["et.typeId" => $_POST["typeId"]])
					->andFilterWhere(["employee.branchId" => $_POST["branchId"]])
					->andFilterWhere(["employee.sectionId" => $_POST["sectionId"]])
					->andFilterWhere(["employee.positionId" => $_POST["positionId"]])
					->andFilterWhere(["employee.teamId" => $_POST["teamId"]])
					->orderBy("employee.status,employee.employeeFirstName ASC")
					->asArray()
					->all();
			}
		} else {
			if (isset($_POST["searchName"]) && $_POST["searchName"] != '') {
				$employee = Employee::find()
					->where(["status" => Employee::STATUS_CURRENT])
					->andFilterWhere(["branchId" => $_POST["branchId"]])
					->andFilterWhere(["sectionId" => $_POST["sectionId"]])
					->andFilterWhere(["positionId" => $_POST["positionId"]])
					->andFilterWhere(["teamId" => $_POST["teamId"]])
					->andWhere("employeeFirstName LIKE '" . $_POST["searchName"] . "%' or email LIKE '" . $_POST["searchName"] . "%'")
					->orderBy("status,employeeFirstName ASC")
					->asArray()
					->all();
			} else {
				$employee = Employee::find()
					->where(["status" => Employee::STATUS_CURRENT])
					->andFilterWhere(["branchId" => $_POST["branchId"]])
					->andFilterWhere(["sectionId" => $_POST["sectionId"]])
					->andFilterWhere(["positionId" => $_POST["positionId"]])
					->andFilterWhere(["teamId" => $_POST["teamId"]])
					->orderBy("status,employeeFirstName ASC")
					->asArray()
					->all();
			}
		}
		$text = $this->renderPartial('search_result', [
			"employee" => $employee
		]);
		$res["text"] = $text;
		return json_encode($res);
	}
	public function actionEmployeeRight()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_GM;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$employee = Employee::find()
			->where(["status" => Employee::STATUS_CURRENT])
			->orderBy("status,employeeFirstName ASC")
			->asArray()
			->all();
		$branch = Branch::find()->select('branchId,branchName')
			->where(["status" => Branch::STATUS_ACTIVE])
			->asArray()
			->all();
		$userType = Type::find()->select('typeId,typeName')
			->where(["status" => Type::STATUS_ACTIVE])
			->asArray()
			->orderBy('typeId')
			->all();

		return $this->render('set_right', [
			"employee" => $employee,
			"branch" => $branch,
			"userType" => $userType
		]);
	}
	public function actionCheckRight()
	{
		$employeeId = $_POST["employeeId"];
		$typeId = $_POST["typeId"];
		$check = EmployeeType::find()->where(["employeeId" => $employeeId, "typeId" => $typeId])->one();
		if (isset($check)) {
			$check->delete();
		} else {
			$check = new EmployeeType();
			$check->employeeId = $employeeId;
			$check->typeId = $typeId;
			$check->status = 1;
			$check->createDateTime = new Expression('NOW()');
			$check->updateDateTime = new Expression('NOW()');
			$check->save(false);
		}
	}
	public function actionImportEmployee()
	{
		$right = Type::TYPE_ADMIN . "," . Type::TYPE_MANAGER . "," . Type::TYPE_HR . "," . Type::TYPE_GM;
		$access = Type::checkType($right);
		if ($access == 0) {
			return $this->redirect(Yii::$app->homeUrl . 'site/access-denied');
		}
		$count = 0;
		$new = [];
		$update = [];
		if (isset($_POST["branch"])) {
			$branchId = $_POST["branch"];
			$imageObj = UploadedFile::getInstanceByName("employeeFile");
			if (isset($imageObj) && !empty($imageObj)) {
				$urlFolder = Path::getHost() . 'file/employee/';
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
					$teamPositionId = null;
					foreach ($sheetData as $data) :
						if ($i >= 1 && $data[9] != "") {
							$nickName = $data[0];
							$nameArray = explode(" ", $data[1]);
							$sectionId = Section::sectionId($data[2], $branchId);
							$positionId = Position::positionId($data[3], $branchId);
							$teamId = Team::teamId($data[4], $branchId);
							$teamPositionId = TeamPosition::positionId($data[5]);
							$dateJoin = $data[6] . ' 00:00:00';
							$birthDate = $data[7] . ' 00:00:00';
							$telephoneNumber = $data[8];
							$email = $data[9];

							$firstName = $data[1];
							$lastName = '-';
							$prefix = '-';

							$isNewEmployee = Employee::checkNewEmployee($nickName, $firstName, $lastName);
							if ($isNewEmployee == 1) {
								$employee = Employee::find()
									->where(["employeeFirstName" => $firstName, "employeeLastName" => $lastName, "employeeNickName" => $nickName])
									->one();
								$update[$i] = [
									//"employeeNo" => $employeeNo,
									"prefix" => $prefix,
									"firstName" => $firstName,
									"lastName" => $lastName
								];
							} else {
								$employee = Employee::find()
									->where(["email" => $email, "status" => Employee::STATUS_DELETED])
									->one();
								if (isset($employee) && !empty($employee)) {
									$update[$i] = [
										"prefix" => $prefix,
										"firstName" => $firstName,
										"lastName" => $lastName
									];
									$employee->status = Employee::STATUS_CURRENT;
								} else {
									$employee = new Employee();
									$employee->createDateTime = new Expression('NOW()');
									$employee->updateDateTime = new Expression('NOW()');
									$emailArr = explode('@', $email);
									$emailName = $emailArr[0];
									$employee->password_hash = md5($emailName);
									$new[$i] = [
										"prefix" => $prefix,
										"firstName" => $firstName,
										"lastName" => $lastName
									];
								}
							}
							//$employee->employeeNo = $employeeNo;
							$employee->prefix = $prefix;
							$employee->branchId = $branchId;
							$employee->employeeNickName = $nickName;
							$employee->employeeFirstName = $firstName;
							$employee->employeeLastName = $lastName;
							$employee->birthDate = $birthDate;
							$employee->dateJoin = $dateJoin;
							$employee->email = $email;
							$employee->username = $email;
							$employee->teamPositionId = $teamPositionId;
							$employee->telephoneNumber = $telephoneNumber;
							$employee->sectionId = $sectionId;
							$employee->positionId = $positionId;
							$employee->teamId = $teamId;
							if ($employee->save(false)) {
								$count++;
							}
						}
						$i++;
					endforeach;
				}

				unlink($pathSave);
			}
		}
		$branch = Branch::find()->select('branchName,branchId')->where(["status" => 1])->asArray()->orderBy('branchName')->all();
		return $this->render('import', [
			"branch" => $branch,
			"count" => $count,
			"new" => $new,
			"update" => $update
		]);
	}
	public function actionSearchEmployeeRight()
	{
		$textReturn = "";
		$res = [];
		$text = $_POST["searchText"];
		$branchId = $_POST["branchId"];
		$employee = Employee::find()
			->where(["status" => Employee::STATUS_CURRENT])
			->andFilterWhere(["branchId" => $branchId])
			->andWhere("employeeFirstName LIKE '" . $text . "%' or employee.email LIKE '" . $text . "%' or employee.employeeNickName LIKE '" . $text . "%'")
			->orderBy("status,employeeFirstName ASC")
			->asArray()
			->all();
		$userType = Type::find()->select('typeId,typeName')
			->where(["status" => Type::STATUS_ACTIVE])
			->asArray()
			->orderBy('typeId')
			->all();

		$textReturn = $this->renderAjax('set_right_search', [
			"employee" => $employee,
			"userType" => $userType
		]);
		if ($textReturn != "") {
			$res["status"] = true;
			$res["text"] = $textReturn;
		} else {
			$res["status"] = false;
		}
		return json_encode($res);
	}
}
