<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\SubFieldGroupMaster;

/**
 * This is the model class for table "sub_field_group".
 *
 * @property integer $subFieldGroupId
 * @property string $subFieldGroupName
 * @property integer $fieldGroupId
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class SubFieldGroup extends \frontend\models\lower_management\master\SubFieldGroupMaster
{
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
    public static function subFieldGroupName($subFieldGroupId)
    {
        $name = "not set";
        if ($subFieldGroupId != null && trim($subFieldGroupId) != '') {
            $subFieldGroup = self::find()->select('subFieldGroupName')->where(["subFieldGroupId" => $subFieldGroupId])->asArray()->one();
            $name = $subFieldGroup["subFieldGroupName"];
        }
        return $name;
    }
    public static function fieldName($subFieldGroupId)
    {

        $fields = Field::find()
            ->select('fieldId')
            ->where(["subFieldGroupId" => $subFieldGroupId])
            ->asArray()
            ->all();
        $fieldId = [];
        if (isset($fields) && count($fields) > 0) {
            $i = 0;
            foreach ($fields as $field) :
                $fieldId[$i] = $field["fieldId"];
                $i++;
            endforeach;
        }
        return $fieldId;
    }
    public static function findSubFileGroup($fieldId)
    {
        $subFieldGroupId = '';
        if ($fieldId != null && trim($fieldId) != '') {
            $field = Field::find()->select('subFieldGroupId')->where(["fieldId" => $fieldId])->asArray()->one();
            if (isset($field) && !empty($field)) {
                $subFieldGroupId = $field["subFieldGroupId"];
            }
        }
        return  $subFieldGroupId;
    }
}
