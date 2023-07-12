<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\TypeMaster;

/**
 * This is the model class for table "type".
 *
 * @property integer $typeId
 * @property string $typeName
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Type extends \frontend\models\lower_management\master\TypeMaster
{
    /**
     * @inheritdoc
     */
    const TYPE_ADMIN = 1; //see every thing
    const TYPE_GM = 2; //see every thing
    const TYPE_MANAGER = 3; //see every thing in their branch (change to supervisor)
    const TYPE_HR = 4;
    const TYPE_STAFF = 5;

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
    public static function userTypeId($typeName)
    {
        $type = Type::find()->select('typeId')->where(["typeName" => $typeName])->asArray()->one();
        return $type["typeId"];
    }
    public static function checkType($employeeType)
    {
        $has = 0;
        if (isset(Yii::$app->user->id)) {
            if ($employeeType == 'all') {
                $has = 1;
            } else {
                $employeeType = EmployeeType::find() //มีชื่อใน employeeType หรือยัง
                    ->select('employeeId')
                    ->where(["employeeId" => Yii::$app->user->id])
                    ->andWhere("typeId in($employeeType)")
                    ->one();
            }
            if (isset($employeeType) && !empty($employeeType)) {
                $has = 1;
            }
        }
        return $has;
    }
    public static function TypeName($typeId)
    {
        $type = Type::find()->select('typeName')->where(["typeId" => $typeId])->asArray()->one();
        return $type["typeName"];
    }
}
