<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\EmployeeMaster;

/**
 * This is the model class for table "employee".
 *
 * @property integer $employeeId
 * @property string $employeeFirstName
 * @property string $employeeLastName
 * @property string $employeeNickName
 * @property string $birthDate
 * @property integer $positionId
 * @property integer $sectionId
 * @property integer $teamId
 * @property string $picture
 * @property string $password_hash
 * @property string $telephoneNumber
 * @property string $email
 * @property string $address
 * @property integer $branchId
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Employee extends \frontend\models\lower_management\master\EmployeeMaster
{
    /**
     * @inheritdoc
     */
    const STATUS_CURRENT = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECT = 3;
    const STATUS_DELETED = 99; //Resign
    const STATUS_DRAF = 100;
    public function rules()
    {
        return array_merge(parent::rules(), []);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), []);
    }
    public static function employeeStatus($s)
    {
        $status = '';
        if ($s == Employee::STATUS_CURRENT) {
            $status = "Current";
        }
        if ($s == Employee::STATUS_APPROVED) {
            $status = "Approved";
        }
        if ($s == Employee::STATUS_REJECT) {

            $status = "Reject";
        }
        if ($s == Employee::STATUS_DRAF) {
            $status = "Draf";
        }
        if ($s == Employee::STATUS_DELETED) {
            $status = "Resign";
        }
        return $status;
    }
    public static function employeeAge($birthDate)
    {
        if ($birthDate != '' && $birthDate != null) {
            $today = date("Y-m-d");
            $todayArr = explode('-', $today);
            $thisYear = $todayArr[0];
            $emArr = explode('-', $birthDate);
            $emYear = $emArr[0];
            $age = $thisYear - $emYear;
        } else {
            $age = "Not set";
        }

        return $age;
    }
    public static function checkNewEmployee($nickName, $firstName, $lastName)
    {
        $employee = Employee::find()
            ->select('employeeId')
            ->where(["employeeFirstName" => $firstName, "employeeLastName" => $lastName, "employeeNickName" => $nickName, "status" => 1])
            ->one();
        if (isset($employee) && !empty($employee)) {
            return 1;
        } else {
            return 0;
        }
    }
    public static function employeeNameChat($employeeId)
    {
        $employee = Employee::find()
            ->select('employeeNickName,employeeFirstName')
            ->where(["employeeId" => $employeeId])->asArray()->one();
        if ($employee["employeeNickName"] != null) {
            return $employee["employeeNickName"];
        } else {
            return $employee["employeeFirstName"];
        }
    }
    public static function employeeTeam()
    {
        $employee = Employee::find()
            ->select('teamId')
            ->where(["employeeId" => Yii::$app->user->id])
            ->asArray()
            ->one();
        return $employee["teamId"];
    }
    public static function EmployeeNickNameFilter($employeeId)
    {
        if ($employeeId == null || $employeeId == "") {
            return 'Person';
        }
        $employee = Employee::find()
            ->select('employeeNickName')
            ->where(["employeeId" => $employeeId])
            ->asArray()
            ->one();
        if (isset($employee) && !empty($employee)) {
            return $employee["employeeNickName"];
        } else {
            return 'Person';
        }
    }
    public static function employeeBranch()
    {
        $employee = Employee::find()
            ->select('branchId')
            ->where(["employeeId" => Yii::$app->user->id])
            ->asArray()
            ->one();
        return $employee["branchId"];
    }
    public static function employeeName($employeeId)
    {
        if ($employeeId != '' && $employeeId != null) {
            $employee = Employee::find()
                ->select('employeeFirstName,employeeNickName')
                ->where(["employeeId" => $employeeId])
                ->asArray()
                ->one();
            return $employee["employeeNickName"];
        } else {
            return '-';
        }
    }
    public static function totalEmployee($branchId)
    {
        $employee = Employee::find()
            ->where(["status" => Employee::STATUS_CURRENT, "branchId" => $branchId])
            ->asArray()
            ->all();
        return count($employee);
    }
}
