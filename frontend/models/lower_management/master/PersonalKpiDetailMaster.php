<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "personal_kpi_detail".
*
    * @property integer $personalKpiDetailId
    * @property integer $pkpiId
    * @property integer $kpiId
    * @property integer $day
    * @property integer $month
    * @property integer $year
    * @property string $file
    * @property string $amount
    * @property string $detail
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class PersonalKpiDetailMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'personal_kpi_detail';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['pkpiId', 'kpiId', 'day', 'month', 'year', 'detail'], 'required'],
            [['pkpiId', 'kpiId', 'day', 'month', 'year'], 'integer'],
            [['amount'], 'number'],
            [['detail'], 'string'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['file'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'personalKpiDetailId' => 'Personal Kpi Detail ID',
    'pkpiId' => 'Pkpi ID',
    'kpiId' => 'Kpi ID',
    'day' => 'Day',
    'month' => 'Month',
    'year' => 'Year',
    'file' => 'File',
    'amount' => 'Amount',
    'detail' => 'Detail',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
