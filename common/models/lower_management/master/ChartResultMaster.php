<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "chart_result".
*
    * @property integer $resultId
    * @property integer $chartId
    * @property integer $index
    * @property integer $value
    * @property integer $status
*/
class ChartResultMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'chart_result';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['chartId', 'index', 'value'], 'required'],
            [['chartId', 'value'], 'integer'],
            [['index'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 6],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'resultId' => 'Result ID',
    'chartId' => 'Chart ID',
    'index' => 'Index',
    'value' => 'Value',
    'status' => 'Status',
];
}
}
