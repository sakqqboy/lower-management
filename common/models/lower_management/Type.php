<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\TypeMaster;

/**
* This is the model class for table "type".
*
* @property integer $typeId
* @property string $typeName
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Type extends \common\models\lower_management\master\TypeMaster{
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
