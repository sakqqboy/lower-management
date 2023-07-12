<?php

namespace frontend\models\lower_management\master;

use Yii;

/**
* This is the model class for table "pie_title".
*
    * @property integer $id
    * @property integer $chartId
    * @property integer $index
    * @property string $title
*/
class PieTitleMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'pie_title';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['chartId', 'index'], 'required'],
            [['chartId', 'index'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'chartId' => 'Chart ID',
    'index' => 'Index',
    'title' => 'Title',
];
}
}
