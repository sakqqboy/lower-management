<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\EmployeeTypeMaster;

/**
 * This is the model class for table "employee_type".
 *
 * @property integer $employeeTypeId
 * @property integer $employeeId
 * @property integer $typeId
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class EmployeeType extends \frontend\models\lower_management\master\EmployeeTypeMaster
{
    /**
     * @inheritdoc
     */
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
    public static function findEmployeeType()
    {
        $employeeType = EmployeeType::find()
            ->select('typeId')
            ->where(["employeeId" => Yii::$app->user->id])
            ->asArray()
            ->all();
        $typeId = [];
        if (isset($employeeType) && count($employeeType) > 0) {
            foreach ($employeeType as $type) :
                $typeId[$type["typeId"]] = $type["typeId"];
            endforeach;
        }
        return $typeId;
    }
    public static function employeeHasType($typeId, $employeeId)
    {
        $employeeType = EmployeeType::find()
            ->select('employeeTypeId')
            ->where(["employeeId" => $employeeId, "typeId" => $typeId])
            ->asArray()
            ->one();
        if (isset($employeeType) && !empty($employeeType) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public static function isAdmin()
    {
        $isAdmin = 0;
        $employeeType = EmployeeType::findEmployeeType();
        $rightAdmin = [Type::TYPE_ADMIN, Type::TYPE_GM];
        if (count($employeeType) > 0) {
            foreach ($employeeType as $type) :
                if (in_array($type, $rightAdmin)) {
                    $isAdmin = 1;
                }
            endforeach;
        }
        return $isAdmin;
    }
    /* public static function findCanEdit()
    {
        $canEdit = 1;
        $employeeType = EmployeeType::findEmployeeType();
        $rightCannotEdit = Type::TYPE_STAFF;
        if (count($employeeType) > 0) {
            foreach ($employeeType as $all) :
                if ($all == $rightCannotEdit) {
                    $canEdit = 0;
                } else {
                    $canEdit = 1;
                    break;
                }
            endforeach;
        } else {
            $canEdit = 1;
        }
        return $canEdit;
    }*/
}
