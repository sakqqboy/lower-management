<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\PositionMaster;

/**
* This is the model class for table "position".
*
* @property integer $positionId
* @property string $positionName
* @property string $positionDetail
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Position extends \common\models\lower_management\master\PositionMaster{
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
