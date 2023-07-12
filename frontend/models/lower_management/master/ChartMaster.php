<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "chart".
*
    * @property integer $chartId
    * @property string $chartName
    * @property integer $chartType
    * @property string $yName
    * @property integer $yUnit
    * @property integer $xType
    * @property string $resultName
    * @property integer $dataType
    * @property integer $startYear
    * @property string $formula
    * @property integer $countryId
    * @property string $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class ChartMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'chart';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['chartId', 'chartName', 'chartType'], 'required'],
            [['chartId', 'startYear', 'countryId'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['chartName', 'yName', 'resultName', 'formula'], 'string', 'max' => 255],
            [['chartType', 'yUnit', 'xType', 'dataType'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 45],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'chartId' => 'Chart ID',
    'chartName' => 'Chart Name',
    'chartType' => 'Chart Type',
    'yName' => 'Y Name',
    'yUnit' => 'Y Unit',
    'xType' => 'X Type',
    'resultName' => 'Result Name',
    'dataType' => 'Data Type',
    'startYear' => 'Start Year',
    'formula' => 'Formula',
    'countryId' => 'Country ID',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
