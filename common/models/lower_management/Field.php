<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\FieldMaster;

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

class Field extends \common\models\lower_management\master\FieldMaster{
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
