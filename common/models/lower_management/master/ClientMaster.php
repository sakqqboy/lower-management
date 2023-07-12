<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "client".
*
    * @property integer $clientId
    * @property string $clientName
    * @property string $clientAddress
    * @property integer $branchId
    * @property string $clientTel1
    * @property string $clientTel2
    * @property string $email
    * @property string $taxId
    * @property string $coordinator
    * @property string $fisicalYear
    * @property integer $isHeadOffice
    * @property integer $countryId
    * @property string $remark
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class ClientMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'client';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['clientName'], 'required'],
            [['clientAddress', 'remark'], 'string'],
            [['branchId', 'countryId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['clientName', 'clientTel1', 'clientTel2', 'email', 'taxId', 'coordinator'], 'string', 'max' => 200],
            [['fisicalYear'], 'string', 'max' => 100],
            [['isHeadOffice', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'clientId' => 'Client ID',
    'clientName' => 'Client Name',
    'clientAddress' => 'Client Address',
    'branchId' => 'Branch ID',
    'clientTel1' => 'Client Tel1',
    'clientTel2' => 'Client Tel2',
    'email' => 'Email',
    'taxId' => 'Tax ID',
    'coordinator' => 'Coordinator',
    'fisicalYear' => 'Fisical Year',
    'isHeadOffice' => 'Is Head Office',
    'countryId' => 'Country ID',
    'remark' => 'Remark',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
