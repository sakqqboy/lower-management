<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\EmployeeMaster;

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

class Employee extends \common\models\lower_management\master\EmployeeMaster{
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
