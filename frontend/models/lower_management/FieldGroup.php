<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\FieldGroupMaster;

/**
 * This is the model class for table "field_group".
 *
 * @property integer $fieldGroupId
 * @property string $fieldGroupName
 * @property integer $status
 * @property string $createDateTime
 * @property string $updateDateTime
 */

class FieldGroup extends \frontend\models\lower_management\master\FieldGroupMaster
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
    public static function fieldGroupName($fieldGroupId)
    {
        $name = "not set";
        if ($fieldGroupId != null && trim($fieldGroupId) != '') {
            $fieldGroup = self::find()->select('fieldGroupName')->where(["fieldGroupId" => $fieldGroupId])->asArray()->one();
            $name = $fieldGroup["fieldGroupName"];
        }
        return $name;
    }
    public static function findGroup($fieldId)
    {
        $fieldGroupId = '';
        if ($fieldId != null && trim($fieldId) != '') {
            $group = Field::find()->select('fieldGroupId')->where(["fieldId" => $fieldId])->asArray()->one();
            $fieldGroupId = $group["fieldGroupId"];
        }
        return $fieldGroupId;
    }
}
