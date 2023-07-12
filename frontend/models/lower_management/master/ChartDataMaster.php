<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "chart_data".
*
    * @property integer $dataId
    * @property integer $chartId
    * @property integer $row
    * @property string $rowName
    * @property integer $index
    * @property integer $value
    * @property integer $status
*/
class ChartDataMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'chart_data';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['chartId', 'row', 'index'], 'required'],
            [['chartId', 'value'], 'integer'],
            [['row', 'index', 'status'], 'string', 'max' => 10],
            [['rowName'], 'string', 'max' => 255],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'dataId' => 'Data ID',
    'chartId' => 'Chart ID',
    'row' => 'Row',
    'rowName' => 'Row Name',
    'index' => 'Index',
    'value' => 'Value',
    'status' => 'Status',
];
}
}
