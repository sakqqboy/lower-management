<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_type".
*
    * @property integer $jobTypeId
    * @property string $jobTypeName
    * @property string $jobTypeDetail
    * @property integer $branchId
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobTypeMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_type';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobTypeName', 'branchId'], 'required'],
            [['jobTypeDetail'], 'string'],
            [['branchId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['jobTypeName'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'jobTypeId' => 'Job Type ID',
    'jobTypeName' => 'Job Type Name',
    'jobTypeDetail' => 'Job Type Detail',
    'branchId' => 'Branch ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
