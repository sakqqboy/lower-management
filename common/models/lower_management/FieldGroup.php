<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\FieldGroupMaster;

/**
* This is the model class for table "field_group".
*
* @property integer $fieldGroupId
* @property string $fieldGroupName
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class FieldGroup extends \common\models\lower_management\master\FieldGroupMaster{
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
