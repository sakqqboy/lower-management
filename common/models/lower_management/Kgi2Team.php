<?php

namespace common\models\lower_management;

use Yii;
use \common\models\lower_management\master\Kgi2TeamMaster;

/**
* This is the model class for table "kgi2_team".
*
* @property integer $kgi2TeamId
* @property integer $kgi2Id
* @property integer $teamId
* @property integer $teamPositionId
* @property integer $status
* @property string $createDateTime
* @property string $updateDateTime
*/

class Kgi2Team extends \common\models\lower_management\master\Kgi2TeamMaster{
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
