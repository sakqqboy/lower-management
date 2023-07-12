<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\CategoryMaster;

/**
* This is the model class for table "category".
*
* @property integer $categoryId
* @property string $categoryName
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Category extends \common\models\lower_management\master\CategoryMaster{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return array_merge(parent::rules(), []);
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), []);
    }
}
