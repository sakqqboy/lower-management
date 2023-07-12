<?php

namespace common\models\lower_management\master;

use Yii;

/**
* This is the model class for table "category".
*
    * @property integer $categoryId
    * @property string $categoryName
    * @property integer $totalRound
    * @property integer $status
    * @property string $createDateTime
    * @property string $updateDateTime
*/
class CategoryMaster extends \common\models\ModelMaster
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'category';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['categoryName'], 'required'],
            [['totalRound'], 'integer'],
            [['createDateTime', 'updateDateTime'], 'safe'],
            [['categoryName'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 10],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'categoryId' => 'Category ID',
    'categoryName' => 'Category Name',
    'totalRound' => 'Total Round',
    'status' => 'Status',
    'createDateTime' => 'Create Date Time',
    'updateDateTime' => 'Update Date Time',
];
}
}
