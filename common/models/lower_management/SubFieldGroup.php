<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\SubFieldGroupMaster;

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

class SubFieldGroup extends \common\models\lower_management\master\SubFieldGroupMaster{
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
