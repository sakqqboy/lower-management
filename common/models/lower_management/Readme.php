<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\ReadmeMaster;

/**
* This is the model class for table "readme".
*
* @property integer $id
* @property string $readme
* @property string $BTC_address
* @property string $email
*/

class Readme extends \common\models\lower_management\master\ReadmeMaster{
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
