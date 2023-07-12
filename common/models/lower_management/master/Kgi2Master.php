<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "kgi2".
*
    * @property integer $kgi2Id
    * @property string $kgi2Name
    * @property integer $branchId
    * @property string $targetAmount
    * @property string $code
    * @property string $detail
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class Kgi2Master extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'kgi2';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['kgi2Name', 'branchId'], 'required'],
            [['branchId'], 'integer'],
            [['targetAmount'], 'number'],
            [['detail'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['kgi2Name'], 'string', 'max' => 255],
            [['code', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'kgi2Id' => 'Kgi2id',
    'kgi2Name' => 'Kgi2name',
    'branchId' => 'Branch ID',
    'targetAmount' => 'Target Amount',
    'code' => 'Code',
    'detail' => 'Detail',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
