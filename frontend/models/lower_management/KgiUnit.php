<?php

namespace frontend\models\lower_management;

use Yii;
use \frontend\models\lower_management\master\KgiUnitMaster;

/**
* This is the model class for table "kgi_unit".
*
* @property integer $kgiUnitId
* @property string $name
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class KgiUnit extends \frontend\models\lower_management\master\KgiUnitMaster{
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
