<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "employee".
*
    * @property integer $employeeId
    * @property string $employeeNo
    * @property string $prefix
    * @property string $employeeFirstName
    * @property string $employeeLastName
    * @property string $employeeNickName
    * @property string $birthDate
    * @property integer $positionId
    * @property integer $sectionId
    * @property integer $teamId
    * @property integer $teamPositionId
    * @property string $picture
    * @property string $username
    * @property string $password_hash
    * @property integer $gender
    * @property string $telephoneNumber
    * @property string $dateJoin
    * @property string $email
    * @property string $tcfEmail
    * @property string $address
    * @property integer $branchId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class EmployeeMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'employee';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['employeeFirstName', 'employeeLastName', 'positionId', 'sectionId', 'username', 'email', 'branchId'], 'required'],
            [['birthDate', 'dateJoin', 'createDateTime', 'updateDateTime'], 'safe'],
            [['positionId', 'sectionId', 'teamId', 'teamPositionId', 'branchId'], 'integer'],
            [['address'], 'string'],
            [['employeeNo', 'employeeFirstName', 'employeeLastName', 'email', 'tcfEmail'], 'string', 'max' => 200],
            [['prefix'], 'string', 'max' => 45],
            [['employeeNickName', 'telephoneNumber'], 'string', 'max' => 100],
            [['picture', 'password_hash'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 6],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'employeeId' => 'Employee ID',
    'employeeNo' => 'Employee No',
    'prefix' => 'Prefix',
    'employeeFirstName' => 'Employee First Name',
    'employeeLastName' => 'Employee Last Name',
    'employeeNickName' => 'Employee Nick Name',
    'birthDate' => 'Birth Date',
    'positionId' => 'Position ID',
    'sectionId' => 'Section ID',
    'teamId' => 'Team ID',
    'teamPositionId' => 'Team Position ID',
    'picture' => 'Picture',
    'username' => 'Username',
    'password_hash' => 'Password Hash',
    'gender' => 'Gender',
    'telephoneNumber' => 'Telephone Number',
    'dateJoin' => 'Date Join',
    'email' => 'Email',
    'tcfEmail' => 'Tcf Email',
    'address' => 'Address',
    'branchId' => 'Branch ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
