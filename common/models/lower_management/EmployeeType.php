<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\EmployeeTypeMaster;

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

class EmployeeType extends \common\models\lower_management\master\EmployeeTypeMaster{
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
}
