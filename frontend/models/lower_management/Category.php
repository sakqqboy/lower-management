<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\CategoryMaster;

/**
 * This is the model class for table "category".
 *
 * @property integer $categoryId
 * @property string $categoryName
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Category extends \frontend\models\lower_management\master\CategoryMaster
{
    /**
     * @inheritdoc
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 99;
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
    public static function muliplyfee($categoryId)
    {
        $multiply = 0;
        $category = Category::find()->select('categoryName')->where(["categoryId" => $categoryId])->asArray()->one();
        if ($category["categoryName"] == "Monthly") {
            $multiply = 12;
        }
        if ($category["categoryName"] == "Spot") {
            $multiply = 1;
        }
        if ($category["categoryName"] == "Quaterly") {
            $multiply = 4;
        }
        if ($category["categoryName"] == "Half year") {
            $multiply = 2;
        }
        if ($category["categoryName"] == "Yearly") {
            $multiply = 1;
        }
        return $multiply;
    }
    public static function categoryNameNameFilter($categoryId)
    {
        if ($categoryId != null) {
            $category = Category::find()->select('categoryName')->where(["categoryId" => $categoryId])->asArray()->one();
            if (isset($categoryId)) {
                return $category["categoryName"];
            } else {
                return 'Category';
            }
        } else {
            return 'Category';
        }
    }
    public static function categoryName($categoryId)
    {
        if ($categoryId != null) {
            $category = Category::find()->select('categoryName')->where(["categoryId" => $categoryId])->asArray()->one();
            if (isset($categoryId)) {
                return $category["categoryName"];
            } else {
                return 'Not set';
            }
        } else {
            return 'Not set';
        }
    }
    public static function categoryId($categoryName)
    {
        $category = Category::find()->select('categoryId')->where(["categoryName" => $categoryName, "status" => 1])->one();
        if (isset($category)) {
            return $category->categoryId;
        } else {
            return '';
        }
    }
}
