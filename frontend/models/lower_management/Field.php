<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\FieldMaster;

/**
 * This is the model class for table "field".
 *
 * @property integer $fieldId
 * @property string $fieldName
 * @property string $fieldDetail
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class Field extends \frontend\models\lower_management\master\FieldMaster
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
    public static function fieldNameFilter($fieldId)
    {
        if ($fieldId != null) {
            $field = Field::find()->select('fieldName')->where(["fieldId" => $fieldId])->asArray()->one();
            if (isset($field)) {
                return $field["fieldName"];
            } else {
                return 'Field';
            }
        } else {
            return 'Field';
        }
    }
    public static function fieldName($fieldId)
    {
        if ($fieldId != null) {
            $field = Field::find()->select('fieldName')->where(["fieldId" => $fieldId])->asArray()->one();
            if (isset($field)) {
                return $field["fieldName"];
            } else {
                return 'not set';
            }
        } else {
            return 'not set';
        }
    }
    public static function checkNewField($branchId, $fieldName)
    {
        $field = Field::find()->where(["branchId" => $branchId, "fieldName" => $fieldName])->one();
        if (isset($field) && !empty($field)) {
            return 1;
        } else {
            return 0;
        }
    }
    public static function SubFieldGroupId($SubFieldGroupName)
    {
        $subField = SubFieldGroup::find()->where(["subFieldGroupName" => $SubFieldGroupName])->one();
        if (isset($subField) && !empty($subField)) {
            return $subField->subFieldGroupId;
        } else {
            return 0;
        }
    }
    public static function fieldId($fieldName, $branchId)
    {
        $field = Field::find()->where(["branchId" => $branchId, "fieldName" => $fieldName, "status" => 1])->one();
        if (isset($field) && !empty($field)) {
            return $field->fieldId;
        } else {
            return '';
        }
    }
}
