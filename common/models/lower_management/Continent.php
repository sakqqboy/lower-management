<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\ContinentMaster;

/**
* This is the model class for table "continent".
*
* @property integer $continentId
* @property string $continentName
* @property integer $sort
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Continent extends \common\models\lower_management\master\ContinentMaster{
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
