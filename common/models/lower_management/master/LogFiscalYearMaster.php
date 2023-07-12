<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "log_fiscal_year".
*
    * @property integer $id
    * @property integer $jobCategoryId
    * @property integer $oldFiscalYear
    * @property integer $newFiscalYear
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class LogFiscalYearMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'log_fiscal_year';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['jobCategoryId', 'newFiscalYear'], 'required'],
            [['jobCategoryId', 'oldFiscalYear', 'newFiscalYear'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'jobCategoryId' => 'Job Category ID',
    'oldFiscalYear' => 'Old Fiscal Year',
    'newFiscalYear' => 'New Fiscal Year',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
