<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "job_category".
*
    * @property integer $jobCategoryId
    * @property integer $jobId
    * @property integer $categoryId
    * @property integer $startMonth
    * @property integer $fiscalYear
    * @property string $targetDate
    * @property string $completeDate
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class JobCategoryMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'job_category';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobId', 'categoryId'], 'required'],
            [['jobId', 'categoryId', 'fiscalYear'], 'integer'],
            [['targetDate', 'completeDate', 'createDateTime', 'updateDateTime'], 'safe'],
            [['startMonth', 'status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'jobCategoryId' => 'Job Category ID',
    'jobId' => 'Job ID',
    'categoryId' => 'Category ID',
    'startMonth' => 'Start Month',
    'fiscalYear' => 'Fiscal Year',
    'targetDate' => 'Target Date',
    'completeDate' => 'Complete Date',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
