<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "fiscal_year".
*
    * @property integer $fiscalYearId
    * @property integer $fiscalYear
    * @property string $createDateTime
    * @property string $closeDateTime
    * @property integer $status
    * @property string $updateDateTime
*/
class FiscalYearMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'fiscal_year';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['fiscalYear'], 'required'],
            [['fiscalYear'], 'integer'],
            [['createDateTime', 'closeDateTime', 'updateDateTime'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'fiscalYearId' => 'Fiscal Year ID',
    'fiscalYear' => 'Fiscal Year',
    'createDateTime' => 'Create Date Time',
    'closeDateTime' => 'Close Date Time',
    'status' => 'Status',
    'updateDateTime' => 'Update Date Time',
];
}
}
